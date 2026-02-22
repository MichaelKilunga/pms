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
        $this->info('Starting contract updates...');
        
        try {
            // Use the centralized service
            $summary = $this->contractUpdater->updateContracts();
            $this->info($summary);

            // Notify about Expiring Contracts (7, 3, 1 days remaining)
            $notifyDays = [7, 3, 1];
            foreach ($notifyDays as $days) {
                $targetDate = Carbon::now()->addDays($days)->format('Y-m-d');
                $expiringContracts = Contract::whereDate('end_date', $targetDate)
                    ->where('status', 'active')
                    ->where('is_current_contract', true)
                    ->get();
                
                foreach ($expiringContracts as $contract) {
                    try {
                        if ($contract->owner) {
                            // Assuming the notification class exists and is configured
                            // If not, we could fall back to our custom Notification model like in the service
                            // $contract->owner->notify(new \App\Notifications\ContractExpiringNotification($contract, $days));
                        }
                    } catch (\Exception $e) {
                         \Log::error("Failed to notify contract expiry for contract {$contract->id}: " . $e->getMessage());
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error("Update failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('Contracts have been updated successfully!');
        return 0;
    }
}

