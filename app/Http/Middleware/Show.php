<?php

namespace App\Http\Middleware;

use App\Models\Contract;
use App\Models\Package;
use App\Models\Pharmacy;
use App\Models\User;
use App\Notifications\InAppNotification;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Show
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $action
     * @return bool
     */
    public function handle(Request $request, Closure $next, $action): bool
    {
        // Example condition: Allow super admins to see all content
        if (Auth::user() && Auth::user()->role === 'super') {
            return true;
        }

        // Current user
        $user = Auth::user();
        $pharmacy = Pharmacy::find(session('current_pharmacy_id'));

        if (!$pharmacy) {
            return redirect()->route('dashboard')->with('error', 'No pharmacy found in the session.');
        }

        // Determine the owner
        $owner = match ($user->role) {
            'staff' => User::find($pharmacy->owner_id),
            'owner' => $user,
            default => null,
        };

        if (!$owner) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Fetch active contract
        $contract = Contract::where('owner_id', $owner->id)
            ->where('is_current_contract', 1)
            ->whereIn('status', ['active', 'graced'])
            ->first();

        $pharmaciesCount = Pharmacy::where('owner_id', $owner->id)->count();

        // Implement your specific conditions based on $action
        switch ($action) {
            case 'create pharmacy button':
                if (!$contract) {
                    return false;
                }

                $package = Package::find($contract->package_id);
                if (!$package) {
                    return false;
                }

                if ($pharmaciesCount >= $package->number_of_pharmacies) {
                    return false;
                }
                return true;
                break;
        }
    }
}
