<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Public pages
Route::get('/', fn() => view('welcome'));
Route::get('/features', fn() => view('features'));
Route::get('/file-types', fn() => view('file-types'));
Route::get('/pricing', fn() => view('pricing'));
Route::get('/security', fn() => view('security'));
Route::get('/community', fn() => view('community'));

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

// Password reset
use App\Http\Controllers\PasswordResetController;
Route::get('/forgot-password',  [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/verify-otp',       [PasswordResetController::class, 'showOtpForm'])->name('password.otp');
Route::post('/verify-otp',      [PasswordResetController::class, 'verifyOtp'])->name('password.otp.verify');
Route::get('/reset-password',   [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password',  [PasswordResetController::class, 'resetPassword'])->name('password.update');

// Protected routes
Route::get('/dashboard', function () {
    if (!session('user_id')) {
        return redirect('/login');
    }
    $plan = session('plan', 'free');
    $planConfig = [
        'free'  => ['storage_gb' => 5,   'file_limit_mb' => 500,  'links' => 5,    'version_days' => 7,   'label' => 'Free'],
        'pro'   => ['storage_gb' => 100,  'file_limit_mb' => 10240,'links' => -1,   'version_days' => 90,  'label' => 'Pro'],
        'teams' => ['storage_gb' => 500,  'file_limit_mb' => 51200,'links' => -1,   'version_days' => 180, 'label' => 'Teams'],
    ];
    return view('dashboard', [
        'plan'       => $plan,
        'planConfig' => $planConfig[$plan] ?? $planConfig['free'],
        'userName'   => session('user_name', 'User'),
        'userEmail'  => session('user_email', ''),
    ]);
})->name('dashboard');
Route::get('/auth/callback', [App\Http\Controllers\AuthController::class, 'handleEmailVerification']);

// Owner Dashboard — private command center
use App\Http\Controllers\OwnerController;
Route::get('/owner/login',  [OwnerController::class, 'showLogin']);
Route::post('/owner/login', [OwnerController::class, 'login']);
Route::get('/owner/command',[OwnerController::class, 'command']);
Route::post('/owner/logout',[OwnerController::class, 'logout']);

// File storage API
use App\Http\Controllers\FileController;
Route::post('/api/files/upload',                    [FileController::class, 'upload']);
Route::get('/api/files',                            [FileController::class, 'index']);
Route::get('/files/{id}/download',                  [FileController::class, 'download'])->name('files.download');
Route::delete('/api/files/{id}',                    [FileController::class, 'destroy']);
Route::get('/api/files/{id}/versions',              [FileController::class, 'versions']);
Route::post('/api/files/{id}/restore/{versionId}',  [FileController::class, 'restore'])->name('files.restore');
Route::post('/api/files/{id}/rename',               [FileController::class, 'rename']);
Route::post('/api/files/{id}/move',                 [FileController::class, 'move']);
Route::get('/api/folders',                          [FileController::class, 'getFolders']);
Route::post('/api/folders',                         [FileController::class, 'createFolder']);
Route::post('/api/folders/{id}/rename',             [FileController::class, 'renameFolder']);
Route::delete('/api/folders/{id}',                  [FileController::class, 'deleteFolder']);
use App\Http\Controllers\ShareCodeController;
Route::get('/share',              [ShareCodeController::class, 'showSharePage']);
Route::get('/share/{code}',       [ShareCodeController::class, 'showSharePage']);
Route::post('/share/access',      [ShareCodeController::class, 'access']);
Route::get('/share/{code}/download/{file_id}', [ShareCodeController::class, 'downloadSharedFile'])->name('share.download');

// Share Codes — authenticated API
Route::post('/api/share-codes/generate',        [ShareCodeController::class, 'generate']);
Route::get('/api/share-codes',                  [ShareCodeController::class, 'myCodes']);
Route::post('/api/share-codes/{id}/revoke',     [ShareCodeController::class, 'revoke']);
Route::get('/api/share-codes/{id}/analytics',   [ShareCodeController::class, 'analytics']);
Route::post('/api/share/send-email',            [ShareCodeController::class, 'sendEmail']);

// Teams & Audit Trail — authenticated API
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AuditController;
Route::get('/api/team/members',             [TeamController::class, 'getMembers']);
Route::post('/api/team/invite',             [TeamController::class, 'invite']);
Route::post('/api/team/role',               [TeamController::class, 'updateRole']);
Route::delete('/api/team/remove/{id}',      [TeamController::class, 'removeMember']);
Route::post('/api/team/add-seat',           [TeamController::class, 'addSeat']);
Route::post('/api/team/update-settings',    [TeamController::class, 'updateSettings']);
Route::get('/api/team/audit',               [AuditController::class, 'index']);
Route::get('/api/team/audit/export',        [AuditController::class, 'export']);
