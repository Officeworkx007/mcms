<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('user.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $key = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many login attempts. Try again in {$seconds} seconds.",
            ])->withInput();
        }

        \Log::info('Remember debug', [
            'raw_remember' => $request->input('remember'),
            'boolean_remember' => $request->boolean('remember'),
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = Auth::user();

            // Block admin from logging in here
            if ($user->hasRole('admin')) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'This portal is for Authorized Staff only. Admins must use the admin login.',
                ])->withInput();
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Contact the administrator.',
                ])->withInput();
            }

            if (!$user->hasRole('editor')) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Access denied.',
                ])->withInput();
            }

            RateLimiter::clear($key);
            $request->session()->regenerate();

            // Invalidate all other sessions for this user
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', session()->getId())
                ->delete();

            return redirect()->route('user.dashboard');
        }

        RateLimiter::hit($key);

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('editor')) {
            return redirect()->route('user.dashboard');
        }

        Auth::logout();
        return redirect()->route('user.login')->withErrors([
            'email' => 'No valid role assigned to your account.',
        ]);
    }
}
