<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateMonthlyContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:generate-contracts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly contracts (invoices) for active contracts';

    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        parent::__construct();
        $this->pricingService = $pricingService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting contract generation...');

        $today = Carbon::now();
        $dayOfMonth = $today->day;
        
        $contracts = Contract::where('status', 'active')
            ->where('is_current_contract', 1)
            ->get();

        foreach ($contracts as $contract) {
            try {
                $startDate = Carbon::parse($contract->start_date);
                
                // Simple billing cycle check
                if ($startDate->day === $dayOfMonth) {
                    $this->customInfo("Processing contract ID: {$contract->id} for Owner: {$contract->owner_id}");

                    // Calculate Price (1 month)
                    $pricingResult = $this->pricingService->calculatePrice($contract->owner, 1, $contract->package_id);
                    
                    $amount = $pricingResult['amount'];
                    $strategy = $pricingResult['strategy'];
                    $details = $pricingResult['details'];
                    
                    $agentMarkup = $contract->owner->pharmacies()->sum('agent_extra_charge');
                    $totalAmount = $amount + $agentMarkup;

                    // Create Pending Contract (Invoice)
                    Contract::create([
                        'owner_id' => $contract->owner_id,
                        'package_id' => $contract->package_id,
                        'start_date' => $today, // Review: does it start now or after current one? Assuming immediate billing for next period
                        'end_date' => $today->copy()->addDays(30),
                        'status' => 'inactive',
                        'payment_status' => 'pending', // Pending invoice
                        'is_current_contract' => 0, // Not current until paid
                        'amount' => $totalAmount,
                        'pricing_strategy' => $strategy,
                        'details' => $details,
                        'agent_markup' => $agentMarkup,
                    ]);

                    $this->info("Contract (Invoice) generated for Contract {$contract->id}: {$totalAmount}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to generate contract for contract {$contract->id}: " . $e->getMessage());
                $this->error("Error for contract {$contract->id}: {$e->getMessage()}");
            }
        }

        $this->info('Contract generation completed.');
    }

    protected function customInfo($message) {
        $this->info($message);
        Log::info($message);
    }
}
