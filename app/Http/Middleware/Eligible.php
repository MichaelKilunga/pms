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

        private $user;
        private $pharmacy;
        private $staff;
        private $package;
        private $medicines;
        private $admins;
        private $owner;
        private $stock;
        private $notification;
        private $reports;
        private $analytics;

        public public function __construct()
        {
            
        }

    public function handle(Request $request, Closure $next, $action): Response
    {

        if ($action == 'check number of pharmacy') {

            if($pharmacy->count())
            return redirect()->route('dashboard')->with('error', 'You must subscribe to access this resource.');
        }
        return $next($request);
    }
}
