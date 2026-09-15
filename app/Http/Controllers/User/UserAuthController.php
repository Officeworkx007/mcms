<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
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
            'login'    => 'required|string',
            'password' => 'required',
        ]);

        $key = Str::lower($request->input('login')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'login' => "Too many login attempts. Try again in {$seconds} seconds.",
            ])->withInput();
        }

        $loginInput = $request->input('login');
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $loginInput, 'password' => $request->input('password')], $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'This portal is for Authorized Staff only. Admins must use the admin login.',
                ])->withInput();
            }

            if ($user->roles->isEmpty()) {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'No role has been assigned to your account. Contact the administrator.',
                ])->withInput();
            }

            RateLimiter::clear($key);
            $request->session()->regenerate();

            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', session()->getId())
                ->delete();

            return redirect()->route('user.dashboard');
        }

        RateLimiter::hit($key);

        return back()->withErrors([
            'login' => 'Invalid credentials.',
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

        if ($user->roles->isNotEmpty()) {
            return redirect()->route('user.dashboard');
        }

        Auth::logout();
        return redirect()->route('user.login')->withErrors([
            'email' => 'No valid role assigned to your account.',
        ]);
    }
}
