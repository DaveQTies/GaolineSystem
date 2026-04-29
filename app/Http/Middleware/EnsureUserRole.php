<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $currentRole = $request->session()->get('account_role');

        if (!$currentRole || ($currentRole !== 'Admin' && !in_array($currentRole, $roles, true))) {
            return redirect()
                ->route('login')
                ->withErrors(['role' => 'Your account is not allowed to access that page yet.']);
        }

        return $next($request);
    }
}
