<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureHasAnyCompany
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->to('/login');
        }

        if (!$user->companies()->exists()) {
            // Sem empresa, sem acesso ao painel user
            return redirect()->to('/login');
        }

        return $next($request);
    }
}

