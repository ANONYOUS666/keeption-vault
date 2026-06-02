<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\AuditController;

class FileController extends Controller
{
    // Plan limits
    private function planLimits(): array
    {
        return match(session('plan', 'free')) {
            'pro'   => ['storage_bytes' => 100 * 1024 ** 3, 'file_bytes' => 10 * 1024 ** 3,  'version_days' => 90],
            'teams' => ['storage_bytes' => 500 * 1024 ** 3, 'file_bytes' => 50 * 1024 ** 3,  'version_days' => 180],
            default => ['storage_bytes' => 5   * 1024 ** 3, 'file_bytes' => 500 * 1024 ** 2, 'version_days' => 7],
        };
    }

    private function getCategory(string $mime, string $name): string
    {
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (str_starts_with($mime, 'image/') || in_array($ext, ['jpg','jpeg','png','gif','webp','heic','svg'])) return 'photo';
        if (str_starts_with($mime, 'video/') || in_array($ext, ['mp4','mov','avi','mkv','webm'])) return 'video';
        if (str_starts_with($mime, 'audio/') || in_array($ext, ['mp3','wav','flac','aac','m4a','ogg'])) return 'music';
        if (in_array($ext, ['pdf','doc','docx','xls','xlsx','ppt','pptx','txt','csv'])) return 'doc';
        return 'other';
    }

    // POST /api/files/upload
    public function upload(Request $request)
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $request->validate(['file' => 'required|file|max:51200']); // 50GB max

        $userId = session('user_id');
        $limits = $this->planLimits();
        $file   = $request->file('file');

        // Per-file size check
        if ($file->getSize() > $limits['file_bytes']) {
            $limitLabel = $limits['file_bytes'] >= 1024**3
                ? round($limits['file_bytes'] / 1024**3) . ' GB'
                : round($limits['file_bytes'] / 1024**2) . ' MB';
            return response()->json(['error' => "File exceeds the {$limitLabel} per-file limit on your plan."], 422);
        }

        // Total storage check
        $usedBytes = DB::table('files')
            ->where('user_id', $userId)
            ->where('is_deleted', false)
            ->sum('size');

        if ($usedBytes + $file->getSize() > $limits['storage_bytes']) {
            return response()->json(['error' => 'Storage full. Free up space or upgrade your plan.'], 422);
        }

        $originalName = $file->getClientOriginalName();
        $mime         = $file->getMimeType() ?? 'application/octet-stream';
        $category     = $this->getCategory($mime, $originalName);
        $filename    = Str::uuid() . '_' . $originalName;
        $storagePath = "uploads/{$userId}/" . $filename;

        // Save file to local storage via stream upload to prevent memory exhaustion
        Storage::disk('local')->putFileAs("uploads/{$userId}", $file, $filename);

        // Check if a file with the same name exists (for versioning)
        $existing = DB::table('files')
            ->where('user_id', $userId)
            ->where('name', $originalName)
            ->where('is_deleted', false)
            ->first();

        if ($existing) {
            // Save current version before overwriting
            $latestVersion = DB::table('file_versions')
                ->where('file_id', $existing->id)
                ->max('version_number') ?? 0;

            DB::table('file_versions')->insert([
                'file_id'        => $existing->id,
                'user_id'        => $userId,
                'path'           => $existing->path,
                'size'           => $existing->size,
                'version_number' => $latestVersion + 1,
                'created_at'     => now(),
            ]);

            // Update the file record
            DB::table('files')->where('id', $existing->id)->update([
                'path'       => $storagePath,
                'size'       => $file->getSize(),
                'mime_type'  => $mime,
                'updated_at' => now(),
            ]);

            $fileId = $existing->id;
            AuditController::log($userId, $userId, session('user_name', 'User'), 'upload', "Uploaded new version of file: " . $originalName, $request);
        } else {
            // New file
            $fileId = DB::table('files')->insertGetId([
                'user_id'    => $userId,
                'name'       => $originalName,
                'path'       => $storagePath,
                'mime_type'  => $mime,
                'category'   => $category,
                'size'       => $file->getSize(),
                'is_deleted' => false,
                'folder_id'  => $request->input('folder_id'),
                'is_team'    => $request->input('is_team', 0) ? true : false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            AuditController::log($userId, $userId, session('user_name', 'User'), 'upload', "Uploaded file: " . $originalName, $request);
        }

        // Purge old versions beyond plan window
        $this->purgeOldVersions($fileId, $limits['version_days']);

        $fileRecord = DB::table('files')->find($fileId);

        return response()->json([
            'success'   => true,
            'file'      => [
                'id'        => $fileRecord->id,
                'name'      => $fileRecord->name,
                'size'      => $fileRecord->size,
                'mime_type' => $fileRecord->mime_type,
                'category'  => $fileRecord->category,
                'is_team'   => $fileRecord->is_team ? true : false,
                'created_at'=> $fileRecord->created_at,
                'url'       => route('files.download', $fileRecord->id),
            ],
        ]);
    }

    private function getTeamUserIds(int $userId): array
    {
        $teamUserIds = [$userId];

        // Check if the current user is a team member
        $memberRecord = DB::table('team_members')
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        if ($memberRecord) {
            $ownerId = $memberRecord->owner_id;
            $teamUserIds[] = $ownerId;
            
            // Get all other active members of the same team
            $otherMembers = DB::table('team_members')
                ->where('owner_id', $ownerId)
                ->where('status', 'active')
                ->whereNotNull('user_id')
                ->pluck('user_id')
                ->toArray();
            $teamUserIds = array_unique(array_merge($teamUserIds, $otherMembers));
        } else {
            // Check if current user is the team owner themselves
            $user = DB::table('users')->where('id', $userId)->first();
            if ($user && $user->plan === 'teams') {
                $members = DB::table('team_members')
                    ->where('owner_id', $userId)
                    ->where('status', 'active')
                    ->whereNotNull('user_id')
                    ->pluck('user_id')
                    ->toArray();
                $teamUserIds = array_unique(array_merge($teamUserIds, $members));
            }
        }

        return $teamUserIds;
    }

    // GET /api/files
    public function index()
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $userId = session('user_id');
        $teamUserIds = $this->getTeamUserIds($userId);

        $files = DB::table('files')
            ->where('is_deleted', false)
            ->where(function($query) use ($userId, $teamUserIds) {
                $query->where(function($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->where('is_team', false);
                })->orWhere(function($q) use ($teamUserIds) {
                    $q->where('is_team', true)
                      ->whereIn('user_id', $teamUserIds);
                });
            })
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn($f) => [
                'id'         => $f->id,
                'name'       => $f->name,
                'size'       => $f->size,
                'mime_type'  => $f->mime_type,
                'category'   => $f->category,
                'created_at' => $f->created_at,
                'folder_id'  => $f->folder_id,
                'is_team'    => $f->is_team ? true : false,
                'url'        => route('files.download', $f->id),
            ]);

        $usedBytes = $files->sum('size');

        return response()->json([
            'files'      => $files,
            'used_bytes' => $usedBytes,
        ]);
    }

    // GET /api/folders
    public function getFolders()
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $userId = session('user_id');
        $teamUserIds = $this->getTeamUserIds($userId);

        $folders = DB::table('folders')
            ->where(function($query) use ($userId, $teamUserIds) {
                $query->where(function($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->where('is_team', false);
                })->orWhere(function($q) use ($teamUserIds) {
                    $q->where('is_team', true)
                      ->whereIn('user_id', $teamUserIds);
                });
            })
            ->get()
            ->map(fn($f) => [
                'id'         => $f->id,
                'name'       => $f->name,
                'parent_id'  => $f->parent_id,
                'page'       => $f->page,
                'is_team'    => $f->is_team ? true : false,
                'created_at' => $f->created_at,
            ]);

        return response()->json([
            'folders' => $folders
        ]);
    }

    // POST /api/folders
    public function createFolder(Request $request)
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $request->validate([
            'name'      => 'required|string|max:100',
            'parent_id' => 'nullable',
            'page'      => 'required|string|max:50',
            'is_team'   => 'nullable|boolean',
        ]);

        $userId = session('user_id');
        $isTeam = $request->input('is_team', false) ? true : false;

        $folderId = DB::table('folders')->insertGetId([
            'user_id'    => $userId,
            'name'       => $request->input('name'),
            'parent_id'  => $request->input('parent_id'),
            'page'       => $request->input('page'),
            'is_team'    => $isTeam,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $folder = DB::table('folders')->find($folderId);

        AuditController::log($userId, $userId, session('user_name', 'User'), 'create_folder', "Created folder: " . $folder->name . " on page " . $folder->page, $request);

        return response()->json([
            'success' => true,
            'folder'  => [
                'id'         => $folder->id,
                'name'         => $folder->name,
                'parent_id'  => $folder->parent_id,
                'page'       => $folder->page,
                'is_team'    => $folder->is_team ? true : false,
                'created_at' => $folder->created_at,
            ]
        ]);
    }

    // POST /api/folders/{id}/rename
    public function renameFolder(Request $request, int $id)
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $request->validate(['name' => 'required|string|max:100']);

        $userId = session('user_id');
        $folder = DB::table('folders')->where('id', $id)->first();
        if (!$folder) return response()->json(['error' => 'Folder not found'], 404);

        DB::table('folders')->where('id', $id)->update([
            'name'       => $request->input('name'),
            'updated_at' => now(),
        ]);

        AuditController::log($userId, session('user_id'), session('user_name', 'User'), 'rename_folder', "Renamed folder from " . $folder->name . " to " . $request->input('name'), $request);

        return response()->json(['success' => true]);
    }

    // DELETE /api/folders/{id}
    public function deleteFolder(int $id)
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $userId = session('user_id');
        $folder = DB::table('folders')->where('id', $id)->first();
        if (!$folder) return response()->json(['error' => 'Folder not found'], 404);

        // Delete all files inside this folder (and recursively inside subfolders)
        $folderIds = $this->getRecursiveFolderIds($id);
        
        // Find all files in these folders
        $files = DB::table('files')
            ->whereIn('folder_id', $folderIds)
            ->where('is_deleted', false)
            ->get();

        foreach ($files as $file) {
            DB::table('files')->where('id', $file->id)->update([
                'is_deleted' => true,
                'deleted_at' => now(),
            ]);
            AuditController::log($file->user_id, session('user_id'), session('user_name', 'User'), 'delete', "Deleted file inside folder deletion: " . $file->name, request());
        }

        // Delete the folders themselves
        DB::table('folders')->whereIn('id', $folderIds)->delete();

        AuditController::log($userId, session('user_id'), session('user_name', 'User'), 'delete_folder', "Deleted folder and its contents: " . $folder->name, request());

        return response()->json(['success' => true]);
    }

    private function getRecursiveFolderIds(int $folderId): array
    {
        $ids = [$folderId];
        $subfolders = DB::table('folders')->where('parent_id', $folderId)->pluck('id')->toArray();
        foreach ($subfolders as $subId) {
            $ids = array_merge($ids, $this->getRecursiveFolderIds($subId));
        }
        return $ids;
    }


    // GET /api/files/{id}/download
    public function download(int $id)
    {
        if (!session('user_id')) return redirect('/login');

        $file = DB::table('files')
            ->where('id', $id)
            ->where('user_id', session('user_id'))
            ->where('is_deleted', false)
            ->first();

        if (!$file) abort(404);

        if (!Storage::disk('local')->exists($file->path)) abort(404);

        AuditController::log($file->user_id, session('user_id'), session('user_name', 'User'), 'download', "Downloaded file: " . $file->name, request());

        if (request()->has('download') || request()->query('download') == '1') {
            return response()->download(
                Storage::disk('local')->path($file->path),
                $file->name,
                ['Content-Type' => $file->mime_type ?? 'application/octet-stream']
            );
        }

        return response()->file(
            Storage::disk('local')->path($file->path),
            ['Content-Type' => $file->mime_type ?? 'application/octet-stream']
        );
    }

    // DELETE /api/files/{id}
    public function destroy(int $id)
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $file = DB::table('files')->where('id', $id)->first();
        if ($file) {
            AuditController::log($file->user_id, session('user_id'), session('user_name', 'User'), 'delete', "Deleted file: " . $file->name, request());
        }

        $affected = DB::table('files')
            ->where('id', $id)
            ->where('user_id', session('user_id'))
            ->update(['is_deleted' => true, 'deleted_at' => now()]);

        if (!$affected) return response()->json(['error' => 'File not found'], 404);

        return response()->json(['success' => true]);
    }

    // GET /api/files/{id}/versions
    public function versions(int $id)
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $file = DB::table('files')
            ->where('id', $id)
            ->where('user_id', session('user_id'))
            ->first();

        if (!$file) return response()->json(['error' => 'File not found'], 404);

        $versions = DB::table('file_versions')
            ->where('file_id', $id)
            ->orderByDesc('version_number')
            ->get()
            ->map(fn($v) => [
                'id'             => $v->id,
                'version_number' => $v->version_number,
                'size'           => $v->size,
                'created_at'     => $v->created_at,
                'restore_url'    => route('files.restore', [$id, $v->id]),
            ]);

        return response()->json([
            'file'     => ['id' => $file->id, 'name' => $file->name],
            'versions' => $versions,
            'plan_days'=> $this->planLimits()['version_days'],
        ]);
    }

    // POST /api/files/{id}/restore/{versionId}
    public function restore(int $id, int $versionId)
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $file = DB::table('files')
            ->where('id', $id)
            ->where('user_id', session('user_id'))
            ->first();

        if (!$file) return response()->json(['error' => 'File not found'], 404);

        $version = DB::table('file_versions')
            ->where('id', $versionId)
            ->where('file_id', $id)
            ->first();

        if (!$version) return response()->json(['error' => 'Version not found'], 404);

        // Save current as a new version
        $latestVersion = DB::table('file_versions')
            ->where('file_id', $id)
            ->max('version_number') ?? 0;

        DB::table('file_versions')->insert([
            'file_id'        => $id,
            'user_id'        => session('user_id'),
            'path'           => $file->path,
            'size'           => $file->size,
            'version_number' => $latestVersion + 1,
            'created_at'     => now(),
        ]);

        // Restore the selected version
        DB::table('files')->where('id', $id)->update([
            'path'       => $version->path,
            'size'       => $version->size,
            'updated_at' => now(),
        ]);

        AuditController::log($file->user_id, session('user_id'), session('user_name', 'User'), 'restore', "Restored version #" . $version->version_number . " of file: " . $file->name, request());

        return response()->json(['success' => true, 'message' => 'Version restored successfully.']);
    }

    // POST /api/files/{id}/rename
    public function rename(Request $request, int $id)
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $request->validate(['name' => 'required|string|max:255']);

        $file = DB::table('files')->where('id', $id)->first();
        if ($file) {
            AuditController::log($file->user_id, session('user_id'), session('user_name', 'User'), 'rename', "Renamed file from " . $file->name . " to " . $request->name, $request);
        }

        $affected = DB::table('files')
            ->where('id', $id)
            ->where('user_id', session('user_id'))
            ->update(['name' => $request->name, 'updated_at' => now()]);

        if (!$affected) return response()->json(['error' => 'File not found'], 404);

        return response()->json(['success' => true]);
    }

    // POST /api/files/{id}/move
    public function move(Request $request, int $id)
    {
        if (!session('user_id')) return response()->json(['error' => 'Unauthenticated'], 401);

        $request->validate(['folder_id' => 'nullable']);

        $file = DB::table('files')->where('id', $id)->first();
        if ($file) {
            AuditController::log($file->user_id, session('user_id'), session('user_name', 'User'), 'move', "Moved file " . $file->name . " to folder ID: " . ($request->folder_id ?: 'Root'), $request);
        }

        $affected = DB::table('files')
            ->where('id', $id)
            ->where('user_id', session('user_id'))
            ->update(['folder_id' => $request->folder_id, 'updated_at' => now()]);

        if (!$affected) return response()->json(['error' => 'File not found'], 404);

        return response()->json(['success' => true]);
    }

    private function purgeOldVersions(int $fileId, int $days): void
    {
        DB::table('file_versions')
            ->where('file_id', $fileId)
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }
}
