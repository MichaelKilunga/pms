<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Notification;
use Carbon\Carbon;

class ContractUpdaterService
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    /**
     * Automatically update contract details based on date conditions.
     */
    public function updateContracts()
    {
        $today = Carbon::now();

        try {
            // 1. Identify active contracts that just ended
            $expiredContracts = Contract::where('end_date', '<', $today)
                ->where('is_current_contract', true)
                ->where('status', 'active')
                ->where('payment_status', 'payed')
                ->get();

            foreach ($expiredContracts as $contract) {
                // Deactivate current
                $contract->update(['status' => 'inactive', 'is_current_contract' => false]);

                // 2. Auto-generate new bill for the next period
                $owner = $contract->owner;
                if ($owner) {
                    $pricingResult = $this->pricingService->calculatePrice($owner, 1, $contract->package_id);
                    
                    $newContract = Contract::create([
                        'owner_id' => $owner->id,
                        'package_id' => $contract->package_id,
                        'start_date' => $today, // Tentative
                        'end_date' => $today->copy()->addDays(30),
                        'status' => 'inactive',
                        'payment_status' => 'pending',
                        'is_current_contract' => 0,
                        'amount' => $pricingResult['amount'] + $contract->agent_markup, // Preserve markup
                        'agent_markup' => $contract->agent_markup,
                        'pricing_strategy' => $pricingResult['strategy'],
                        'details' => $pricingResult['details'],
                        'payment_notified' => false,
                    ]);

                    // 3. Notify Owner
                    Notification::create([
                        'id' => \Illuminate\Support\Str::uuid(),
                        'type' => 'App\Notifications\BillingNotification',
                        'notifiable_type' => 'App\Models\User',
                        'notifiable_id' => $owner->id,
                        'data' => [
                            'title' => 'New Billing Generated',
                            'message' => "Your contract has expired. A new bill (TZS " . number_format($newContract->amount) . ") has been generated for the next period.",
                        ],
                        'read_at' => null,
                    ]);
                }
            }

            $contractGrace = Contract::where('grace_end_date', '<', $today)
                ->where('is_current_contract', 1)
                ->where('status', 'graced')
                ->where('payment_status', 'payed')
                ->update(['status' => 'inactive', 'is_current_contract' => 0]);

            // Delete all contracts with pending payments where the start date exceeds 30 days (extended from 3)
            $pendingRemoved = Contract::where('payment_status', 'pending')
                ->where('start_date', '<', $today->copy()->subDays(30))
                ->delete();

            $deleteReadNotifications =  Notification::where('read_at','!=', null )->delete();

            // Note: Removed the throw new exception here to allow silent execution in scheduler
            return "Processed " . count($expiredContracts) . " expired contracts. Graced {$contractGrace} contracts.";
        } catch (\Exception $e) {
            \Log::error("Contract Update Error: " . $e->getMessage());
            throw $e;
        }
    }
}
