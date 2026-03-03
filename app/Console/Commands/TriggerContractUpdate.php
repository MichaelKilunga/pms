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
                
                if ($expiringContracts->count() > 0) {
                    $this->info("Found {$expiringContracts->count()} contracts expiring in {$days} days.");
                }

                foreach ($expiringContracts as $contract) {
                    try {
                        if ($contract->owner) {
                            // Smart Overlap Check: Check if owner has ANY other active contract that ends LATER
                            $hasOverlap = Contract::where('owner_id', $contract->owner_id)
                                ->where('id', '!=', $contract->id)
                                ->where('status', 'active')
                                ->where('end_date', '>', $contract->end_date)
                                ->exists();

                            if ($hasOverlap) {
                                $this->line(" - Skipped owner notification for contract #{$contract->id} (Found overlapping active contract)");
                                continue;
                            }

                            $contract->owner->notify(new \App\Notifications\ContractExpiringNotification($contract, $days));
                            $this->line(" - Notified owner of contract #{$contract->id} ({$days} days remaining)");

                            // Notify Super Admins
                            $superAdmins = \App\Models\User::where('role', 'super')->get();
                            foreach ($superAdmins as $admin) {
                                $admin->notify(new \App\Notifications\ContractExpiringNotification($contract, $days));
                            }
                        }
                    } catch (\Exception $e) {
                         \Log::error("Failed to notify contract expiry for contract {$contract->id}: " . $e->getMessage());
                         $this->error("Failed to notify contract expiry for contract #{$contract->id}");
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error("Update failed: " . $e->getMessage());
            \Log::error("Console Command contracts:auto-update failed: " . $e->getMessage());
            return 1;
        }
        
        $this->info('Contracts have been updated successfully!');
        return 0;
    }
}

