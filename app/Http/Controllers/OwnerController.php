<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

class OwnerController extends Controller
{
    private function checkAuth() {
        if (!session('owner_authenticated')) return false;
        if (time() - session('owner_auth_time', 0) > 7200) {
            session()->forget(['owner_authenticated','owner_auth_time']);
            return false;
        }
        return true;
    }

    public function showLogin() {
        if ($this->checkAuth()) return redirect('/owner/command');
        return view('owner.login');
    }

    public function login(Request $request) {
        $ip          = $request->ip();
        $throttleKey = 'owner_login_' . $ip;

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors(['email' => 'Too many failed attempts. Locked. Try again in ' . ceil($seconds / 60) . ' minutes.']);
        }

        $ok = strtolower($request->email) === strtolower(env('OWNER_EMAIL', ''))
            && password_verify($request->password, env('OWNER_PASSWORD_HASH', ''));

        if (!$ok) {
            RateLimiter::hit($throttleKey, 3600); // Lock for 1 hour
            $attempts = RateLimiter::attempts($throttleKey);
            
            if ($attempts >= 5) {
                return back()->withErrors(['email' => 'Too many attempts. Locked for 1 hour.']);
            }
            return back()->withErrors(['email' => 'Invalid credentials (' . $attempts . '/5).'])->withInput();
        }

        RateLimiter::clear($throttleKey);

        session(['owner_authenticated' => true, 'owner_auth_time' => time()]);
        return redirect('/owner/command');
    }

    public function command() {
        if (!$this->checkAuth()) return redirect('/owner/login');

        // Real stats from MySQL
        $totalUsers  = User::count();
        $freeUsers   = User::where('plan', 'free')->count();
        $proUsers    = User::where('plan', 'pro')->count();
        $teamsUsers  = User::where('plan', 'teams')->count();
        $newToday    = User::whereDate('created_at', today())->count();
        $newThisWeek = User::where('created_at', '>=', now()->startOfWeek())->count();

        // Recent signups
        $recentUsers = User::orderByDesc('created_at')->limit(10)->get();

        $stats = compact(
            'totalUsers', 'freeUsers', 'proUsers', 'teamsUsers',
            'newToday', 'newThisWeek', 'recentUsers'
        );

        return view('owner.dashboard', $stats);
    }

    public function logout(Request $request) {
        $request->session()->forget(['owner_authenticated', 'owner_auth_time']);
        return redirect('/owner/login');
    }
}
