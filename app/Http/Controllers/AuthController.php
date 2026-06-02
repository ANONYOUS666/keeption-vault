<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AuditController;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function showRegister(Request $request)
    {
        $plan = in_array($request->query('plan'), ['free','pro','teams']) ? $request->query('plan') : 'free';
        return view('register', compact('plan'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        // Store user info in session
        session([
            'user_id'    => $user->id,
            'user_email' => $user->email,
            'user_name'  => $user->name,
            'plan'       => $user->plan,
        ]);

        AuditController::log($user->id, $user->id, $user->name, 'login', "Logged in to Keeption Vault", $request);

        return redirect('/dashboard');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:12',
        ]);

        $plan = in_array($request->input('plan'), ['free','pro','teams']) ? $request->input('plan') : 'free';

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'plan'     => $plan,
        ]);

        // Log them straight in after signup
        session([
            'user_id'    => $user->id,
            'user_email' => $user->email,
            'user_name'  => $user->name,
            'plan'       => $user->plan,
        ]);

        AuditController::log($user->id, $user->id, $user->name, 'register', "Created account on plan: " . ucfirst($plan), $request);

        return redirect('/dashboard')->with('success', 'Welcome to Keeption Vault!');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }

    public function updateProfile(Request $request)
    {
        if (!session('user_id')) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'name'   => 'required|string|max:100',
            'bio'    => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048', // max 2MB
        ]);

        $user = \App\Models\User::find(session('user_id'));
        if (!$user) return response()->json(['error' => 'User not found'], 404);

        $avatarPath = null;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path('avatars/' . $user->avatar))) {
                unlink(public_path('avatars/' . $user->avatar));
            }
            $file     = $request->file('avatar');
            $filename = session('user_id') . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('avatars'), $filename);
            $avatarPath = $filename;
        }

        $user->name = $request->name;
        $user->bio  = $request->bio;
        if ($avatarPath) $user->avatar = $avatarPath;
        $user->save();

        // Update session
        session(['user_name' => $user->name]);

        return response()->json([
            'success'    => true,
            'name'       => $user->name,
            'bio'        => $user->bio,
            'avatar_url' => $user->avatar ? '/avatars/' . $user->avatar : null,
        ]);
    }
}
