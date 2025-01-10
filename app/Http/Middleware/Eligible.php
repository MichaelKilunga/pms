<?php

namespace App\Http\Middleware;

use App\Models\Contract;
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
        //Ignore  if is super admin
        if (Auth::user()->role == 'super') {
            return $next($request);
        }

        //capture loged in user
        $user = User::whereId(Auth::user()->id)->first();

        //capture  pharmacy owner
        if (Auth::user()->role == 'staff') {
            $owner = User::where('id', Pharmacy::where('id', session('current_pharmacy_id'))->first()->owner_id)->first();
        } elseif (Auth::user()->role == 'owner') {
            $owner = $user;
        }


        // Fetch the active contract and related package
        $contract = Contract::where('owner_id', $owner->id)
            ->where('is_current_contract', 1)
            ->whereIn('status', ['active', 'graced'])
            ->first();

        if ($action == 'hasContract') {
            if (!$contract &&  Auth::user()->role != 'super') {
                if (Auth::user()->role == 'staff') {

                    //send notification
                    $this->notify($user, 'Hello '.$user->name.', Your pharmacy has no active plan, Please  contact your employer to subscribe!', 'warning');
                    $this->notify($owner, 'Your pharmacist '.Auth::user()->name.', tried to access resource you don\'t have subscription plan, subscribe or upgrade!', 'warning');

                    return redirect()->route('dashboard')->with('error', 'Your pharmacy has no active plan, Please  contact your employer!');
                }

                if (Auth::user()->role == 'owner') {

                    //send notification
                    $this->notify($owner, 'Hello '.$owner->name.', You\'ve tried to access a resource you don\'t have subscription plan for, please, subscribe first!', 'warning');

                    //return to dashboard
                    return redirect()->route('myContracts')->with('error', 'You don\'t  have an active plan currently, Please choose a plan first and subscribe to continue using our services!');
                }
            }
        }

        // $package = Package::find($contract->package_id);

        // if (!$package) {
        //     return redirect()->route('dashboard')->with('error', 'Invalid package associated with your contract.');
        // }

        // // Perform checks based on the action
        // switch ($action) {
        //     case 'pharmacies':
        //         $pharmaciesCount = Pharmacy::where('owner_id', $user->id)->count();
        //         if ($pharmaciesCount >= $package->pharmacy_limit) {
        //             return redirect()->route('dashboard')->with('error', 'You have reached the maximum number of pharmacies for your plan.');
        //         }
        //         break;

        //     case 'medicines':
        //         $medicineCount = $this->getTotalMedicines($user);
        //         if ($medicineCount >= $package->medicine_limit) {
        //             return redirect()->route('dashboard')->with('error', 'You have reached the maximum number of medicines for your plan.');
        //         }
        //         break;

        //     case 'admins':
        //         $adminsCount = $this->getAdminsCount($user);
        //         if ($adminsCount >= $package->admin_limit) {
        //             return redirect()->route('dashboard')->with('error', 'You have reached the maximum number of admin accounts for your plan.');
        //         }
        //         break;

        //     case 'notifications':
        //         if (!$package->in_app_notifications) {
        //             return redirect()->route('dashboard')->with('error', 'Your package does not support in-app notifications.');
        //         }
        //         break;

        //     case 'analytics':
        //         if (!$package->sales_analytics) {
        //             return redirect()->route('dashboard')->with('error', 'Your package does not include sales analytics.');
        //         }
        //         break;

        //     default:
        //         return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
        // }

        return $next($request);
    }

    // /**
    //  * Get the total medicines owned by the user across all pharmacies.
    //  *
    //  * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
    //  * @return int
    //  */
    // private function getTotalMedicines($user)
    // {
    //     $pharmacies = Pharmacy::where('owner_id', $user->id)->pluck('id');
    //     return \DB::table('medicines')->whereIn('pharmacy_id', $pharmacies)->count();
    // }

    // /**
    //  * Get the total admin accounts under the user.
    //  *
    //  * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
    //  * @return int
    //  */
    // private function getAdminsCount($user)
    // {
    //     return \DB::table('users')->where('role', 'admin')->where('owner_id', $user->id)->count();
    // }

    private function notify(User $user, $message, $type)
    {
        //send notification
        $notification = [
            'message' => $message,
            'type' => $type,
        ];
        $user->notify(new InAppNotification($notification));
    }
}
