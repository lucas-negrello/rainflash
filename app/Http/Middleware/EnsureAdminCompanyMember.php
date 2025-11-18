<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdminCompanyMember
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->to('/login');
        }

        $slug = (string) config('admin.company.slug');
        $isAdminCompanyMember = $user->companies()->where('slug', $slug)->exists();
        if (!$isAdminCompanyMember) {
            return redirect()->to('/');
        }

        return $next($request);
    }
}
