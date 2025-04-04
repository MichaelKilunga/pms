<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        // Log only authenticated user actions
        if (Auth::check()) {
            activity('user-activity')
                ->causedBy(Auth::user())
                ->withProperties([
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'input' => $request->except(['password', 'password_confirmation'])
                ])
                ->log('User accessed ' . $request->fullUrl());
        }

        return $next($request);
    }
}
