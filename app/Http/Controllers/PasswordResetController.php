<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // Step 1 — Show forgot password form
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Step 1 — Send OTP to email
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        // Always show success and redirect to verify OTP page to prevent email enumeration leaks
        if (!$user) {
            return redirect('/verify-otp?email=' . urlencode($request->email))
                ->with('success', 'A 6-digit code has been sent to your email.');
        }

        // Generate 6-digit OTP
        $otp   = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $token = Str::random(64);

        // Delete any existing token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Store token + OTP
        DB::table('password_reset_tokens')->insert([
            'email'        => $request->email,
            'token'        => Hash::make($token),
            'otp'          => $otp,
            'otp_attempts' => 0,
            'created_at'   => now(),
        ]);

        // Send OTP email
        try {
            Mail::send([], [], function ($mail) use ($request, $otp, $user) {
                $name = $user->name ?? 'there';
                $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"/></head><body style="margin:0;padding:0;background:#f4f4f5;font-family:\'Helvetica Neue\',Arial,sans-serif;">'
                    . '<div style="max-width:480px;margin:40px auto;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);">'
                    . '<div style="background:#04060a;padding:24px 32px;text-align:center;">'
                    . '<div style="font-size:20px;font-weight:700;color:#ffffff;">Keeption <span style="color:#00f5a0;">Vault</span></div>'
                    . '</div>'
                    . '<div style="padding:32px;text-align:center;">'
                    . '<div style="font-size:40px;margin-bottom:16px;">🔐</div>'
                    . '<h1 style="font-size:20px;font-weight:700;color:#0d0f14;margin:0 0 8px;">Password Reset Code</h1>'
                    . '<p style="color:#6b7280;font-size:14px;margin:0 0 28px;">Hi ' . htmlspecialchars($name) . ', use the code below to reset your password. It expires in 15 minutes.</p>'
                    . '<div style="background:#f0fdf4;border:2px solid #00f5a0;border-radius:12px;padding:20px;margin-bottom:24px;">'
                    . '<div style="font-size:40px;font-weight:700;letter-spacing:12px;color:#04060a;font-family:monospace;">' . $otp . '</div>'
                    . '</div>'
                    . '<p style="color:#9ca3af;font-size:12px;margin:0;">If you did not request this, ignore this email. Your password will not change.</p>'
                    . '</div>'
                    . '<div style="background:#f9fafb;padding:16px 32px;text-align:center;border-top:1px solid #e5e7eb;">'
                    . '<p style="font-size:12px;color:#9ca3af;margin:0;">Keeption Vault — Your Files. Your Rules.</p>'
                    . '</div>'
                    . '</div></body></html>';

                $mail->to($request->email)
                     ->subject('Your Keeption Vault password reset code')
                     ->from(config('mail.from.address', 'noreply@keeptionvault.com'), 'Keeption Vault')
                     ->html($html);
            });
        } catch (\Exception $e) {
            \Log::error('Password reset email failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send email. Please try again.']);
        }

        // Redirect to OTP verification page
        return redirect('/verify-otp?email=' . urlencode($request->email))
            ->with('success', 'A 6-digit code has been sent to your email.');
    }

    // Step 2 — Show OTP verification form
    public function showOtpForm(Request $request)
    {
        $email = $request->query('email');
        if (!$email) return redirect('/forgot-password');
        return view('auth.verify-otp', ['email' => $email]);
    }

    // Step 2 — Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Invalid or expired code. Please request a new one.']);
        }

        // Check expiry (15 minutes)
        if (now()->diffInMinutes($record->created_at) > 15) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['otp' => 'This code has expired. Please request a new one.']);
        }

        // Check attempts
        if ($record->otp_attempts >= 5) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['otp' => 'Too many incorrect attempts. Please request a new code.']);
        }

        // Verify OTP
        if ($record->otp !== $request->otp) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->increment('otp_attempts');
            $remaining = 5 - ($record->otp_attempts + 1);
            return back()->withErrors(['otp' => 'Incorrect code. ' . $remaining . ' attempt' . ($remaining === 1 ? '' : 's') . ' remaining.']);
        }

        // OTP correct — redirect to reset password form with token
        $token = Str::random(64);
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->update(['token' => Hash::make($token), 'otp' => null]);

        return redirect('/reset-password?token=' . $token . '&email=' . urlencode($request->email));
    }

    // Step 3 — Show reset password form
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->query('token'),
            'email' => $request->query('email'),
        ]);
    }

    // Step 3 — Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'token'                 => 'required',
            'password'              => 'required|min:12|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['token' => 'This reset link is invalid or has expired.']);
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'This reset link has expired. Please request a new one.']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'No account found with that email.']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password reset successfully. You can now sign in.');
    }
}
