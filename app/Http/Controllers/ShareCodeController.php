<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class ShareCodeController extends Controller
{
    // Characters used in codes — excludes 0/O, 1/I, 5/S for readability
    private const CHARS = 'ABCDEFGHJKLMNPQRTUVWXYZ23467894';

    private function generateCode(int $length = 6): string
    {
        $chars = self::CHARS;
        $max   = strlen($chars) - 1;
        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $chars[random_int(0, $max)];
            }
        } while (DB::table('share_codes')->where('code', $code)->exists());

        return $code;
    }

    // POST /share-codes/generate
    public function generate(Request $request)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'file_meta'      => 'required|array|min:1|max:20',
            'expires_in'     => 'required|integer|min:1',   // minutes
            'max_uses'       => 'nullable|integer|min:1',
            'password'       => 'nullable|string',
            'allow_download' => 'boolean',
            'allow_reshare'  => 'boolean',
            'self_destruct'  => 'boolean',
            'watermarked'    => 'boolean',
            'code_length'    => 'integer|in:4,6,8',
        ]);

        $plan = session('plan', 'free');

        // Enforce expiry limits per plan
        $maxMinutes = match($plan) {
            'pro'   => 7 * 24 * 60,   // 7 days
            'teams' => 30 * 24 * 60,  // 30 days
            default => 24 * 60,        // 24 hours for free
        };

        $expiresIn = min((int) $request->expires_in, $maxMinutes);
        $length    = (int) ($request->code_length ?? 6);

        $code = $this->generateCode($length);

        $id = DB::table('share_codes')->insertGetId([
            'code'           => $code,
            'user_id'        => session('user_id'),
            'file_ids'       => json_encode($request->input('file_ids', [])),
            'file_meta'      => json_encode($request->file_meta),
            'expires_at'     => now()->addMinutes($expiresIn),
            'max_uses'       => $request->max_uses,
            'use_count'      => 0,
            'password_hash'  => $request->password ? Hash::make($request->password) : null,
            'allow_download' => $request->boolean('allow_download', true),
            'allow_reshare'  => $request->boolean('allow_reshare', false),
            'self_destruct'  => $request->boolean('self_destruct', false),
            'watermarked'    => $request->boolean('watermarked', false),
            'is_active'      => true,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        return response()->json([
            'code'     => 'KV-' . $code,
            'raw_code' => $code,
            'url'      => url('/share/KV-' . $code),
            'expires_at' => now()->addMinutes($expiresIn)->toIso8601String(),
        ]);
    }

    // GET /share/{code} — recipient page
    public function showSharePage(string $code = '')
    {
        $raw = strtoupper(str_replace('KV-', '', $code));

        // Try to load share metadata for rich OG preview tags
        $ogData = [];
        if ($raw) {
            $share = DB::table('share_codes')
                ->where('code', $raw)
                ->where('is_active', true)
                ->first();

            if ($share && !now()->isAfter($share->expires_at)) {
                $fileMeta  = json_decode($share->file_meta, true) ?: [];
                $sender    = DB::table('users')->where('id', $share->user_id)->value('name') ?? 'Someone';
                $fileCount = count($fileMeta);
                $firstName = $fileCount > 0 ? ($fileMeta[0]['name'] ?? 'file') : 'file';

                // Derive category from 'category' field OR from MIME 'type' field
                $rawCategory = $fileCount > 0 ? ($fileMeta[0]['category'] ?? $fileMeta[0]['type'] ?? 'other') : 'other';
                $mime = strtolower($rawCategory);
                if (str_starts_with($mime, 'image/') || in_array($mime, ['photo'])) {
                    $category = 'photo';
                } elseif (str_starts_with($mime, 'video/') || in_array($mime, ['video'])) {
                    $category = 'video';
                } elseif (str_starts_with($mime, 'audio/') || in_array($mime, ['music'])) {
                    $category = 'music';
                } elseif (str_contains($mime, 'pdf') || str_contains($mime, 'word') || str_contains($mime, 'document') || in_array($mime, ['doc'])) {
                    $category = 'doc';
                } else {
                    $category = 'other';
                }

                // Choose OG image based on category
                $ogImageMap = [
                    'photo' => url('/og-photo.png'),
                    'video' => url('/og-video.png'),
                    'music' => url('/og-music.png'),
                    'doc'   => url('/og-doc.png'),
                ];
                $ogImage = $ogImageMap[$category] ?? url('/og-file.png');

                // Fallback to favicon if specific OG image doesn't exist
                if (!file_exists(public_path(parse_url($ogImage, PHP_URL_PATH)))) {
                    $ogImage = url('/favicon.png');
                }

                $ogData = [
                    'title'       => $fileCount === 1
                        ? $sender . ' shared "' . $firstName . '" with you'
                        : $sender . ' shared ' . $fileCount . ' files with you',
                    'description' => 'View and download files shared securely via Keeption Vault. No account required.',
                    'image'       => $ogImage,
                    'url'         => url('/share/KV-' . $raw),
                    'type'        => 'website',
                    'site_name'   => 'Keeption Vault',
                    'sender'      => $sender,
                    'file_count'  => $fileCount,
                    'first_file'  => $firstName,
                ];
            }
        }

        return view('share.index', [
            'prefill' => $raw,
            'og'      => $ogData,
        ]);
    }

    // POST /share/access — validate code + password, return file meta
    public function access(Request $request)
    {
        $request->validate([
            'code'     => 'required|string',
            'password' => 'nullable|string',
        ]);

        $raw = strtoupper(str_replace('KV-', '', $request->code));

        $share = DB::table('share_codes')->where('code', $raw)->first();

        if (!$share || !$share->is_active) {
            return response()->json(['error' => 'This share code is invalid or has been revoked.'], 404);
        }

        if (now()->isAfter($share->expires_at)) {
            return response()->json(['error' => 'This share code has expired.'], 410);
        }

        if ($share->max_uses !== null && $share->use_count >= $share->max_uses) {
            return response()->json(['error' => 'This share code has reached its maximum uses.'], 410);
        }

        if ($share->password_hash) {
            $ip = $request->ip();
            $throttleKey = 'share_wrong_' . $share->id . '_' . $ip;

            if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
                $seconds = RateLimiter::availableIn($throttleKey);
                return response()->json([
                    'error' => 'Too many wrong password attempts. Try again in ' . ceil($seconds / 60) . ' minutes.',
                    'locked' => true
                ], 429);
            }

            if (!$request->password || !Hash::check($request->password, $share->password_hash)) {
                RateLimiter::hit($throttleKey, 900); // 15 mins lock
                $attempts = RateLimiter::attempts($throttleKey);
                return response()->json([
                    'error' => 'Incorrect password.',
                    'attempts' => $attempts
                ], 401);
            }

            RateLimiter::clear($throttleKey);
        }

        // Log the use
        DB::table('share_code_uses')->insert([
            'code_id'     => $share->id,
            'used_at'     => now(),
            'ip_address'  => $request->ip(),
            'device_type' => $this->detectDevice($request->userAgent()),
            'browser_name'=> $this->detectBrowser($request->userAgent()),
            'downloaded'  => false,
        ]);

        // Increment use count
        DB::table('share_codes')->where('id', $share->id)->increment('use_count');

        // Get sender name
        $sender = DB::table('users')->where('id', $share->user_id)->value('name');

        // Store authorized state in session to permit secure downloads of this share's files
        session()->put('unlocked_shares.' . $share->id, true);

        return response()->json([
            'success'        => true,
            'sender'         => $sender ?? 'Someone',
            'files'          => json_decode($share->file_meta, true),
            'allow_download' => (bool) $share->allow_download,
            'allow_reshare'  => (bool) $share->allow_reshare,
            'watermarked'    => (bool) $share->watermarked,
            'expires_at'     => $share->expires_at,
            'code_id'        => $share->id,
        ]);
    }

    // GET /dashboard/share-codes — list user's codes
    public function myCodes()
    {
        if (!session('user_id')) return redirect('/login');

        $codes = DB::table('share_codes')
            ->where('user_id', session('user_id'))
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($c) {
                $c->file_meta = json_decode($c->file_meta, true);
                $c->expired   = now()->isAfter($c->expires_at);
                $c->display   = 'KV-' . $c->code;
                return $c;
            });

        return response()->json($codes);
    }

    // POST /share-codes/{id}/revoke
    public function revoke(int $id)
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $affected = DB::table('share_codes')
            ->where('id', $id)
            ->where('user_id', session('user_id'))
            ->update(['is_active' => false, 'updated_at' => now()]);

        if (!$affected) {
            return response()->json(['error' => 'Code not found or not yours.'], 404);
        }

        return response()->json(['success' => true]);
    }

    // GET /share-codes/{id}/analytics
    public function analytics(int $id)
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $share = DB::table('share_codes')
            ->where('id', $id)
            ->where('user_id', session('user_id'))
            ->first();

        if (!$share) return response()->json(['error' => 'Not found'], 404);

        $uses = DB::table('share_code_uses')
            ->where('code_id', $id)
            ->orderByDesc('used_at')
            ->get();

        return response()->json([
            'code'     => 'KV-' . $share->code,
            'uses'     => $uses,
            'total'    => $uses->count(),
            'downloads'=> $uses->where('downloaded', true)->count(),
        ]);
    }

    // POST /api/share/send-email
    public function sendEmail(Request $request)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'to'        => 'required|email',
            'share_url' => 'required|string',
            'files'     => 'required|array|min:1',
            'sender'    => 'nullable|string',
            'message'   => 'nullable|string|max:500',
        ]);

        $senderName = $request->sender ?: session('user_name', 'Someone');
        $to         = $request->to;
        $shareUrl   = $request->share_url;
        $files      = $request->files;
        $message    = $request->message;

        try {
            \Mail::send([], [], function ($mail) use ($to, $senderName, $shareUrl, $files, $message) {
                $fileCount = count($files);
                $subject   = $senderName . ' shared ' . ($fileCount === 1 ? 'a file' : $fileCount . ' files') . ' with you';

                $fileListHtml = collect($files)->map(function ($f) {
                    $ico = match(true) {
                        str_contains($f['type'] ?? '', 'image') => '🖼️',
                        str_contains($f['type'] ?? '', 'video') => '🎬',
                        str_contains($f['type'] ?? '', 'audio') => '🎵',
                        str_contains($f['type'] ?? '', 'pdf')   => '📄',
                        default => '📁',
                    };
                    $size = isset($f['size']) ? ' · ' . number_format($f['size'] / 1024, 1) . ' KB' : '';
                    return '<div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8f9fa;border-radius:8px;margin-bottom:8px;">'
                        . '<span style="font-size:20px;">' . $ico . '</span>'
                        . '<div><div style="font-size:14px;font-weight:600;color:#0d0f14;">' . htmlspecialchars($f['name']) . '</div>'
                        . '<div style="font-size:12px;color:#6b7280;">' . htmlspecialchars($f['type'] ?? 'File') . $size . '</div></div>'
                        . '</div>';
                })->join('');

                $personalMsg = $message
                    ? '<div style="background:#f0fdf4;border-left:3px solid #00f5a0;padding:12px 16px;border-radius:0 8px 8px 0;margin-bottom:20px;font-size:14px;color:#374151;font-style:italic;">"' . htmlspecialchars($message) . '"</div>'
                    : '';

                $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"/></head><body style="margin:0;padding:0;background:#f4f4f5;font-family:\'Helvetica Neue\',Arial,sans-serif;">'
                    . '<div style="max-width:560px;margin:40px auto;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);">'
                    . '<div style="background:#04060a;padding:28px 32px;text-align:center;">'
                    . '<div style="font-size:22px;font-weight:700;color:#ffffff;letter-spacing:-.5px;">Keeption <span style="color:#00f5a0;">Vault</span></div>'
                    . '</div>'
                    . '<div style="padding:32px;">'
                    . '<h1 style="font-size:20px;font-weight:700;color:#0d0f14;margin:0 0 8px;">' . htmlspecialchars($senderName) . ' shared ' . ($fileCount === 1 ? 'a file' : $fileCount . ' files') . ' with you</h1>'
                    . '<p style="color:#6b7280;font-size:14px;margin:0 0 20px;">Click the button below to view ' . ($fileCount === 1 ? 'it' : 'them') . ' securely in your browser. No account needed.</p>'
                    . $personalMsg
                    . '<div style="margin-bottom:20px;">' . $fileListHtml . '</div>'
                    . '<a href="' . $shareUrl . '" style="display:block;text-align:center;background:#00f5a0;color:#04060a;font-weight:700;font-size:16px;padding:14px 24px;border-radius:10px;text-decoration:none;margin-bottom:16px;">Open Files →</a>'
                    . '<p style="font-size:12px;color:#9ca3af;text-align:center;margin:0;">This link expires in 7 days. Shared securely via Keeption Vault.</p>'
                    . '</div>'
                    . '<div style="background:#f9fafb;padding:16px 32px;text-align:center;border-top:1px solid #e5e7eb;">'
                    . '<p style="font-size:12px;color:#9ca3af;margin:0;">Store and share your own files free at <a href="https://keeption.com" style="color:#00f5a0;text-decoration:none;">keeption.com</a></p>'
                    . '</div>'
                    . '</div></body></html>';

                $mail->to($to)
                     ->subject($subject)
                     ->from(config('mail.from.address'), 'Keeption Vault')
                     ->html($html);
            });

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Share email failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send email. Please check your mail configuration.'], 500);
        }
    }

    // GET /share/{code}/download/{file_id}
    public function downloadSharedFile(string $code, int $file_id)
    {
        $raw = strtoupper(str_replace('KV-', '', $code));
        $share = DB::table('share_codes')
            ->where('code', $raw)
            ->where('is_active', true)
            ->first();

        if (!$share) abort(404, 'Share link invalid or revoked.');

        if (now()->isAfter($share->expires_at)) {
            abort(410, 'Share link has expired.');
        }

        if ($share->max_uses !== null && $share->use_count >= $share->max_uses) {
            abort(410, 'Share link has reached its maximum use limit.');
        }

        if ($share->password_hash) {
            if (!session('unlocked_shares.' . $share->id)) {
                abort(403, 'Unauthorized access. Please unlock the share first.');
            }
        }

        $fileIds = json_decode($share->file_ids, true) ?: [];
        if (!in_array($file_id, $fileIds)) {
            abort(404, 'File not found in this share.');
        }

        $file = DB::table('files')
            ->where('id', $file_id)
            ->where('is_deleted', false)
            ->first();

        if (!$file) abort(404, 'File not found.');

        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($file->path)) {
            abort(404, 'Physical file not found.');
        }

        // Record download activity
        DB::table('share_code_uses')->insert([
            'code_id'     => $share->id,
            'used_at'     => now(),
            'ip_address'  => request()->ip(),
            'device_type' => $this->detectDevice(request()->userAgent() ?? ''),
            'browser_name'=> $this->detectBrowser(request()->userAgent() ?? ''),
            'downloaded'  => true,
        ]);

        if (request()->has('inline') || request()->query('inline') == '1') {
            return response()->file(
                \Illuminate\Support\Facades\Storage::disk('local')->path($file->path),
                ['Content-Type' => $file->mime_type ?? 'application/octet-stream']
            );
        }

        return response()->download(
            \Illuminate\Support\Facades\Storage::disk('local')->path($file->path),
            $file->name,
            ['Content-Type' => $file->mime_type ?? 'application/octet-stream']
        );
    }

    private function detectDevice(string $ua): string
    {
        if (str_contains($ua, 'Mobile') || str_contains($ua, 'Android')) return 'Mobile';
        if (str_contains($ua, 'Tablet') || str_contains($ua, 'iPad')) return 'Tablet';
        return 'Desktop';
    }

    private function detectBrowser(string $ua): string
    {
        if (str_contains($ua, 'Chrome')) return 'Chrome';
        if (str_contains($ua, 'Firefox')) return 'Firefox';
        if (str_contains($ua, 'Safari')) return 'Safari';
        if (str_contains($ua, 'Edge')) return 'Edge';
        return 'Unknown';
    }
}
