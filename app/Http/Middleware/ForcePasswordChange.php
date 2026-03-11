<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->is_password_changed) {
            if (\Illuminate\Support\Facades\Hash::check('password', auth()->user()->password)) {
                if (!$request->routeIs('password.force-change', 'password.force-change.store', 'logout')) {
                    if ($request->expectsJson()) {
                        return response()->json(['message' => 'Password change required.'], 403);
                    }
                    return redirect()->route('password.force-change');
                }
            } else {
                auth()->user()->update(['is_password_changed' => true]);
            }
        }

        return $next($request);
    }
}
