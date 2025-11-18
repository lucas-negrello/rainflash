<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectAfterLogin(auth()->user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
            'remember' => ['nullable','boolean'],
        ]);

        $key = 'login:'.sha1($request->ip().'|'.$request->input('email'));
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Muitas tentativas. Tente novamente em alguns segundos.',
            ])->status(429);
        }

        $credentials = $request->only('email', 'password');
        $remember = (bool) $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();
        RateLimiter::clear($key);

        return $this->redirectAfterLogin(auth()->user());
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->to('/login');
    }

    protected function redirectAfterLogin($user)
    {
        $slug = config('admin.company.slug');
        $isAdminCompanyMember = $user->companies()->where('slug', $slug)->exists();
        return redirect()->intended($isAdminCompanyMember ? '/admin' : '/');
    }
}

