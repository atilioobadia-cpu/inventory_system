<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:1',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact an administrator.');
            }

            $user->update(['last_login_at' => now()]);

            $activityService = app(ActivityService::class);
            $activityService->log(
                user: $user,
                action: 'login',
                subject: $user,
                description: 'User logged in'
            );

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return redirect()->route('login')->withInput($request->only('email'))->with('error', 'Invalid email or password.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $activityService = app(ActivityService::class);
            $activityService->log(
                user: $user,
                action: 'logout',
                subject: $user,
                description: 'User logged out'
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
