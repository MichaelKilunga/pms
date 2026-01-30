<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\Invoice;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateMonthlyInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:generate-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly invoices for active contracts';

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
        $this->info('Starting invoice generation...');

        // Find active contracts
        // Criteria: status active, payment_status payed (or should we bill regardless? Usually active means paid previously or valid)
        // We need to check if today is the billing day.
        // Assuming billing cycle is monthly based on start_date day.
        
        $today = Carbon::now();
        $dayOfMonth = $today->day;
        
        $contracts = Contract::where('status', 'active')
            ->where('is_current_contract', 1)
            ->get();

        foreach ($contracts as $contract) {
            try {
                $startDate = Carbon::parse($contract->start_date);
                
                // Check if today matches the start date's day of month
                // Handle edge cases like 31st vs months with 30 days?
                // For simplicity, let's say if (start_day == current_day).
                // Or if monthly, maybe we just bill every 30 days from start?
                // The prompt says: "Check if today matches the billing day (e.g., contract started on the 5th, today is the 5th)."
                
                if ($startDate->day === $dayOfMonth) {
                    $this->customInfo("Processing contract ID: {$contract->id} for Owner: {$contract->owner_id}");

                    // Calculate Price
                    // How many months? 1 month invoice.
                    $pricingResult = $this->pricingService->calculatePrice($contract->owner, 1, $contract->package_id);
                    
                    $amount = $pricingResult['amount'];
                    $strategy = $pricingResult['strategy'];
                    $details = $pricingResult['details'];
                    
                    // Add agent markup?
                    // The service *should* handle the logic or we explicitly add it.
                    // Service returned markup as 0 in my simple implementation, but controller added it.
                    // We should replicate that consistency.
                    $agentMarkup = $contract->owner->pharmacies()->sum('agent_extra_charge');
                    $totalAmount = $amount + $agentMarkup;

                    // Create Invoice
                    Invoice::create([
                        'user_id' => $contract->owner_id,
                        'contract_id' => $contract->id,
                        'amount' => $totalAmount,
                        'status' => 'unpaid',
                        'due_date' => $today->copy()->addDays(7), // Due in 7 days
                        'pricing_strategy' => $strategy,
                        'details' => array_merge($details, ['agent_markup' => $agentMarkup]),
                    ]);

                    $this->info("Invoice generated for Contract {$contract->id}: {$totalAmount}");
                }
            } catch (\Exception $e) {
                Log::error("Failed to generate invoice for contract {$contract->id}: " . $e->getMessage());
                $this->error("Error for contract {$contract->id}: {$e->getMessage()}");
            }
        }

        $this->info('Invoice generation completed.');
    }

    protected function customInfo($message) {
        $this->info($message);
        Log::info($message);
    }
}
