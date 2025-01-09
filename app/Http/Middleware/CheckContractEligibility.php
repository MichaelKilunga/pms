<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckContractEligibility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $requiredPackageType
     * @return mixed
     */
    public function handle($request, Closure $next, $requiredPackageType)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this resource.');
        }

        // $contract = $user->contract;

        // // Check if the user has an active contract
        // if (!$contract || $contract->status !== 'active') {
        //     return redirect()->route('home')->with('error', 'You do not have an active contract.');
        // }

        // // Check if the user has the required package type
        // if ($contract->package_type !== $requiredPackageType) {
        //     return redirect()->route('home')->with('error', 'You are not eligible to access this resource.');
        // }

        // // Check if the contract is still valid
        // if ($contract->end_date < Carbon::today()) {
        //     return redirect()->route('home')->with('error', 'Your contract has expired.');
        // }

        return $next($request);
    }
}
