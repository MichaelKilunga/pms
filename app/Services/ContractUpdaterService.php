<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Notification;
use Carbon\Carbon;

class ContractUpdaterService
{
    /**
     * Automatically update contract details based on date conditions.
     */
    public function updateContracts()
    {
        $today = Carbon::now();

        try {
            // Update contracts that have reached their end date
            $contractEnd = Contract::where('end_date', '<', $today)
                ->where('is_current_contract', true)
                ->where('status', 'active')
                ->where('payment_status', 'payed')
                ->update(['status' => 'inactive']);

            $contractGrace = Contract::where('grace_end_date', '<', $today)
                ->where('is_current_contract', 1)
                ->where('status', 'graced')
                ->where('payment_status', 'payed')
                ->update(['status' => 'inactive', 'is_current_contract' => 0]);

            // Delete all contracts with pending payments where the start date exceeds 3 days
            $pendingRemoved = Contract::where('payment_status', 'pending')
                ->where('start_date', '<', $today->subDays(3))
                ->delete();

            $deleteReadNotifications =  Notification::where('read_at','!=', null )->delete();

            throw new \Exception("Deactivated {$contractEnd} contracts. Graced {$contractGrace} contracts, Removed {$pendingRemoved} pending contracts, and deleted {$deleteReadNotifications} notifications.");
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
