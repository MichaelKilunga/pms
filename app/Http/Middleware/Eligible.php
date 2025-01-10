<?php

namespace App\Http\Middleware;

use App\Models\Pharmacy;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Eligible
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $action): Response
    {
        $user = Auth::user();

        if ($action == 'check number of pharmacy') {
            $pharmacy = Pharmacy::where('owner->id', $user->id)
            return redirect()->route('dashboard')->with('error', 'You must subscribe to access this resource.');
        }
        return $next($request);
    }
}
