<?php

namespace App\Http\Middleware;

use App\Models\Agent;
use App\Models\Contract;
use App\Models\Items;
use App\Models\Package;
use App\Models\Pharmacy;
use App\Models\Staff;
use App\Models\User;
use App\Notifications\InAppNotification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Eligible
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $action
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $action): Response
    {
        // Super Admin bypass
        if (Auth::user()->role === 'super') {
            return $next($request);
        }

        // check if agent has completed registration
        if ($action === 'registered') {
            if (Auth::user()->role === 'agent') {
                $agent = Auth::user()->isAgent;
                if ($agent != null) {
                    if ($agent->registration_status != 'complete') {
                        return redirect()->route('agent.completeRegistration', [
                            'action' => 'index'
                        ])->with('info', 'Please complete your registration to continue!');
                    }
                } else {
                    // create new agent
                    $agent = Agent::create([
                        'user_id' => Auth::user()->id,
                        'registration_status' => 'step_1'
                    ]);
                    return redirect()->route('agent.completeRegistration', [
                        'action' => 'index'
                    ])->with('info', 'Please complete your registration to continue!');
                }
                if ($agent->registration_status === 'complete') {
                    return $next($request);
                }
            }
        }

        // Current user
        $user = Auth::user();
        $pharmacy = Pharmacy::find(session('current_pharmacy_id'));

        if (!$pharmacy && Auth::user()->role == 'owner') {
            // return redirect()->route('dashboard')->with('error', 'No pharmacy found in the session.');
            // $owner = Auth::user();
            if (Auth::user()->pharmacies->count() < 1) {
                if ($action == "create pharmacy") {
                    return $next($request);
                }

                if ($action != "create pharmacy") {
                    return redirect()->route('dashboard')->with('info', 'You don\'t have any pharmacy, select or create one to continue!');
                }
            } else {
                return redirect()->route('dashboard')->with('info', 'Select a pharmacy to continue!');
            }
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


        switch ($action) {
            case 'hasContract':
                if (!$contract) {
                    $this->notify(
                        $owner,
                        'You do not have an active subscription plan. Please subscribe to access this resource!',
                        'warning'
                    );

                    $redirectRoute = $user->role == 'staff' ? 'dashboard' : 'myContracts';
                    return redirect()->route($redirectRoute)->with('error', 'You do not have an active subscription plan.');
                }
                break;

            case 'create pharmacy':
                if (!$contract) {
                    return redirect()->route('myContracts')->with('error', 'You need an active subscription plan to add pharmacies.');
                }

                $package = Package::find($contract->package_id);
                $pharmaciesCount = Pharmacy::where('owner_id', $owner->id)->count();

                if (!$package) {
                    return redirect()->route('myContracts')->with('error', 'Invalid package associated with your subscription.');
                }

                if ($pharmaciesCount >= $package->number_of_pharmacies) {
                    return redirect()->route('myContracts')->with('error', 'You have reached the maximum number of pharmacies allowed by your subscription.');
                }
                break;

            case 'add staff':
                $package = Package::find($contract->package_id);
                $staffCount = Staff::where('pharmacy_id', $pharmacy->id)->count();

                if (!$package) {
                    return redirect()->route('myContracts')->with('error', 'Invalid package associated with your subscription.');
                }

                if ($staffCount >= $package->number_of_pharmacists) {
                    return redirect()->route('staff')->with('error', 'You have reached the maximum number of pharmacists allowed by your subscription.');
                }
                break;
            case 'add medicine':
                $package = Package::find($contract->package_id);
                $medicineCount = Items::where('pharmacy_id', $pharmacy->id)->count();

                if (!$package) {
                    return redirect()->route('myContracts')->with('error', 'Invalid package associated with your subscription.');
                }

                if ($medicineCount >= $package->number_of_medicines) {

                    //check if request is ajax
                    if ($request->ajax())
                        return response()->json(['message' => 'maximum'], 200);

                    //check if not ajax
                    return redirect()->route('medicines')->with('error', 'You have reached the maximum number of medicines allowed by your subscription.');
                }
                break;
            case 'view reports':
                $package = Package::find($contract->package_id);

                if (!$package) {
                    return redirect()->route('myContracts')->with('error', 'Invalid package associated with your subscription.');
                }

                if ($package->reports != 1) {
                    return redirect()->back()->with('error', 'You\'re not allowed to see reports with your subscription plan, please upgrade!');
                }
                break;

            default:
                return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        }

        // Always return the next middleware
        return $next($request);
    }

    /**
     * Send an in-app notification.
     *
     * @param  \App\Models\User  $user
     * @param  string  $message
     * @param  string  $type
     */
    private function notify(User $user, string $message, string $type): void
    {
        $notification = [
            'message' => $message,
            'type' => $type,
        ];
        $user->notify(new InAppNotification($notification));
    }
}
