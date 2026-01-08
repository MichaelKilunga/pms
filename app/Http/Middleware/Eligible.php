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
     * @param  string  $action
     */
    public function handle(Request $request, Closure $next, $action): Response
    {
        // Super Admin bypass
        if (Auth::user()->hasRole('Superadmin')) {
            return $next($request);
        }

        // check if agent has completed registration
        if ($action === 'registered') {
            if (Auth::user()->hasRole('Agent')) {
                $agent = Auth::user()->isAgent;
                if ($agent != null) {
                    if ($agent->registration_status != 'complete') {
                        return redirect()->route('agent.completeRegistration', [
                            'action' => 'index',
                        ])->with('info', 'Please complete your registration to continue!');
                    }
                } else {
                    // create new agent
                    $agent = Agent::create([
                        'user_id' => Auth::user()->id,
                        'registration_status' => 'step_1',
                    ]);

                    return redirect()->route('agent.completeRegistration', [
                        'action' => 'index',
                    ])->with('info', 'Please complete your registration to continue!');
                }
                if ($agent->registration_status === 'complete') {
                    return $next($request);
                }
            }
            // if is owner
            if (Auth::user()->hasRole(['Owner', 'Staff'])) {
                return $next($request);
            }
        }

        // Current user
        $user = Auth::user();
        $pharmacy = Pharmacy::find(session('current_pharmacy_id'));

        if (! $pharmacy && Auth::user()->hasRole('Owner')) {
            // return redirect()->route('dashboard')->with('error', 'No pharmacy found in the session.');
            // $owner = Auth::user();
            if (Auth::user()->pharmacies->count() < 1) {
                if ($action == 'create pharmacy') {
                    return $next($request);
                }

                if ($action != 'create pharmacy') {
                    return redirect()->route('dashboard')->with('info', 'You don\'t have any pharmacy, select or create one to continue!');
                }
            } else {
                return redirect()->route('dashboard')->with('info', 'Select a pharmacy to continue!');
            }
        }

        // Determine the owner
        $owner = null;
        if ($user->hasRole('Staff')) {
            $owner = User::find($pharmacy->owner_id);
        } elseif ($user->hasRole('Owner')) {
            $owner = $user;
        }

        if (! $owner) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Fetch active contract
        $contract = Contract::where('owner_id', $owner->id)
            ->where('is_current_contract', 1)
            ->whereIn('status', ['active', 'graced'])
            ->first();

        switch ($action) {
            case 'hasContract':
                if (! $contract) {
                    $pricingMode = $owner->pricing_mode ?? \App\Models\SystemSetting::where('key', 'pricing_mode')->value('value') ?? 'standard';

                    // Allow setup routes for Dynamic Pricing users so they can populate items
                    if ($pricingMode === 'dynamic') {
                        $allowedRoutes = [
                            'dashboard',
                            'pharmacies', 'pharmacies.create', 'pharmacies.store', 'pharmacies.show', 'pharmacies.update',
                            'medicines', 'medicines.create', 'medicines.store', 'medicines.show', 'medicines.update', 'medicines.search',
                            'stock', 'stock.create', 'stock.store', 'stock.show', 'stock.update',
                            'import', 'importStore', 'medicines.import-form', 'medicines.import',
                        ];

                        // Check if current route name matches allowed list or patterns
                        $currentRoute = $request->route()->getName();

                        // Simple check if route is in allowed list
                        if (in_array($currentRoute, $allowedRoutes) ||
                            str_starts_with($currentRoute, 'medicines.') ||
                            str_starts_with($currentRoute, 'stock.') ||
                            str_starts_with($currentRoute, 'pharmacies.')) {
                            return $next($request);
                        }
                    }

                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => 'Subscription required for this action.'], 403);
                    }

                    $this->notify(
                        $owner,
                        'You do not have an active subscription plan. Please subscribe to access this resource!',
                        'warning'
                    );

                    $redirectRoute = $user->hasRole('Staff') ? 'dashboard' : 'myContracts';

                    return redirect()->route($redirectRoute)->with('error', 'You do not have an active subscription plan.');
                }
                break;

            case 'create pharmacy':
                if (! $contract) {
                    return redirect()->route('myContracts')->with('error', 'You need an active subscription plan to add pharmacies.');
                }

                $package = Package::find($contract->package_id);
                $pharmaciesCount = Pharmacy::where('owner_id', $owner->id)->count();

                if (! $package) {
                    return redirect()->route('myContracts')->with('error', 'Invalid package associated with your subscription.');
                }

                if ($pharmaciesCount >= $package->number_of_pharmacies) {
                    return redirect()->route('myContracts')->with('error', 'You have reached the maximum number of pharmacies allowed by your subscription.');
                }
                break;

            case 'add staff':
                $package = Package::find($contract->package_id);

                if (! $package) {
                    return redirect()->route('myContracts')->with('error', 'Invalid package associated with your subscription.');
                }

                // Check strict feature flag first
                if (! $package->staff_management) {
                    return redirect()->route('staff')->with('error', 'Your subscription plan does not support Staff Management.');
                }

                $staffCount = Staff::where('pharmacy_id', $pharmacy->id)->count();

                if ($staffCount >= $package->number_of_pharmacists) {
                    return redirect()->route('staff')->with('error', 'You have reached the maximum number of pharmacists allowed by your subscription.');
                }
                break;

            case 'add medicine':
                $package = Package::find($contract->package_id);
                $medicineCount = Items::where('pharmacy_id', $pharmacy->id)->count();

                if (! $package) {
                    return redirect()->route('myContracts')->with('error', 'Invalid package associated with your subscription.');
                }

                if ($medicineCount >= $package->number_of_medicines) {
                    // check if request is ajax
                    if ($request->ajax()) {
                        return response()->json(['message' => 'maximum'], 200);
                    }

                    // check if not ajax
                    return redirect()->route('medicines')->with('error', 'You have reached the maximum number of medicines allowed by your subscription.');
                }
                break;

            case 'view reports':
                $package = Package::find($contract->package_id);

                if (! $package) {
                    return redirect()->route('myContracts')->with('error', 'Invalid package associated with your subscription.');
                }

                if ($package->reports != 1) {
                    return redirect()->back()->with('error', 'You\'re not allowed to see reports with your subscription plan, please upgrade!');
                }
                break;

            case 'stock':
                $package = Package::find($contract->package_id);
                if (! $package || ! $package->stock_management) {
                    return redirect()->route('dashboard')->with('error', 'Stock Management is not available in your current plan.');
                }
                break;

            case 'transfers':
                $package = Package::find($contract->package_id);
                if (! $package || ! $package->stock_transfer) {
                    return redirect()->route('dashboard')->with('error', 'Stock Transfers are not available in your current plan.');
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
