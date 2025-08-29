<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ContractUpdaterService;

use App\Models\Contract;
use App\Models\Notification;
use Carbon\Carbon;

class TriggerContractUpdate extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'contracts:auto-update';

    /**
     * The console command description.
     */
    protected $description = 'Trigger automatic updates for contracts based on date conditions.';

    protected $contractUpdater;

    public function __construct(ContractUpdaterService $contractUpdater)
    {
        parent::__construct();
        $this->contractUpdater = $contractUpdater;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now();
        // $this->contractUpdater->updateContracts();
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

            $deleteReadNotifications =  Notification::where('read_at', '!=', null)->delete();

            $this->info("Deactivated {$contractEnd} contracts. Graced {$contractGrace} contracts, Removed {$pendingRemoved} pending contracts, and deleted {$deleteReadNotifications} notifications.");
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->info('Contracts have been updated successfully!');
    }
}

