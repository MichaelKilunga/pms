<?php

namespace App\Console\Commands;

use App\Mail\DailyPharmacyReport;
use App\Models\Pharmacy;
use App\Models\Sales;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Services\SmsService;
use App\Services\MetaWhatsAppService;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InAppNotification;

class SendDailyPharmacyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string  
     */
    protected $signature = 'pharmacy:send-daily-report {--pharmacy_id= : Send report for specific pharmacy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily pharmacy report with sales and stock status to pharmacy owners';

    /**
     * Execute the console command.
     */
    public function handle(SmsService $smsService, MetaWhatsAppService $whatsAppService)
    {
        $pharmacyId = $this->option('pharmacy_id');
        
        if ($pharmacyId) {
            $pharmacies = Pharmacy::where('id', $pharmacyId)->with('owner')->get();
        } else {
            $pharmacies = Pharmacy::with('owner')->get();
        }

        foreach ($pharmacies as $pharmacy) {
            $this->sendReportForPharmacy($pharmacy, $smsService, $whatsAppService);
        }

        $this->info('Daily pharmacy reports sent successfully');
    }

    // private function sendReportForPharmacy(Pharmacy $pharmacy)
    // {
    //     $today = Carbon::today();

    //     // Calculate sales data for today
    //     $todaySales = Sales::where('pharmacy_id', $pharmacy->id)
    //         ->whereDate('date', $today)
    //         ->with(['stock.item'])
    //         ->get();

    //     $totalRevenue = Sales::where('pharmacy_id', $pharmacy->id)
    //         ->whereDate('date', $today)
    //         ->selectRaw('SUM(total_price * quantity) as total_revenue')
    //         ->value('total_revenue');

    //     $salesSummary = [
    //         'total_revenue' => $totalRevenue,
    //         'total_cost' => $todaySales->sum(function($sale) {
    //             return $sale->stock ? $sale->stock->buying_price * $sale->quantity : 0;
    //         }),
    //         'total_transactions' => $todaySales->count(),
    //     ];

    //     $salesSummary['profit_loss'] = $salesSummary['total_revenue'] - $salesSummary['total_cost'];

    //     // Get stock status
    //     $stocks = Stock::where('pharmacy_id', $pharmacy->id)
    //         ->with('item')
    //         ->get();

    //     $stockStatus = [
    //         'out_of_stock' => $stocks->where('remain_Quantity', 0)->values(),
    //         'low_stock' => $stocks->filter(function($stock) {
    //             if ($stock->remain_Quantity <= 0) return false;
    //             $threshold = ($stock->quantity * $stock->low_stock_percentage) / 100;
    //             return $stock->remain_Quantity <= $threshold;
    //         })->values(),
    //         'expired' => $stocks->filter(function($stock) {
    //             return Carbon::parse($stock->expire_date)->isPast();
    //         })->values(),
    //         'good_stock' => $stocks->filter(function($stock) {
    //             if ($stock->remain_Quantity <= 0) return false;
    //             if (Carbon::parse($stock->expire_date)->isPast()) return false;
    //             $threshold = ($stock->quantity * $stock->low_stock_percentage) / 100;
    //             return $stock->remain_Quantity > $threshold;
    //         })->values(),
    //     ];

    //     // Send email if pharmacy has an owner with email
    //     if ($pharmacy->owner && $pharmacy->owner->email) {
    //         Mail::to($pharmacy->owner->email)
    //             ->send(new DailyPharmacyReport($pharmacy, $salesSummary, $stockStatus));

    //         $this->info("Report sent to {$pharmacy->name} owner: {$pharmacy->owner->email}");
    //     } else {
    //         $this->warn("No email found for pharmacy: {$pharmacy->name}");
    //     }
    // }

    /**
     * Send daily report for a single pharmacy.
     *
     * @param \App\Models\Pharmacy $pharmacy
     * @return void
     */
    private function sendReportForPharmacy(Pharmacy $pharmacy, SmsService $smsService, MetaWhatsAppService $whatsAppService): void
    {
        $today = Carbon::today();
        $reportDate = $today->format('F j, Y');
        $message = 'daily';

        // ----------------------
        // Sales summary (aggregated in SQL)
        // ----------------------
        $salesData = Sales::where('pharmacy_id', $pharmacy->id)
            ->whereDate('date', $today)
            ->selectRaw('
            COALESCE(SUM(total_price * quantity), 0) as total_revenue,
            COALESCE(SUM(quantity), 0) as total_quantity,
            COUNT(*) as total_transactions
        ')
            ->first();

        // Compute total cost directly with a join (avoid per-row loops)
        $totalCost = Sales::where('sales.pharmacy_id', $pharmacy->id)
            ->whereDate('sales.date', $today)
            ->join('stocks', 'sales.stock_id', '=', 'stocks.id')
            ->selectRaw('COALESCE(SUM(stocks.buying_price * sales.quantity), 0) as total_cost')
            ->value('total_cost');

        /** 
         * @var array<string, int|float> $salesSummary 
         */
        $salesSummary = [
            'total_revenue'      => (float) ($salesData->total_revenue ?? 0),
            'total_cost'         => (float) ($totalCost ?? 0),
            'total_transactions' => (int)   ($salesData->total_transactions ?? 0),
            'profit_loss'        => (float) (($salesData->total_revenue ?? 0) - ($totalCost ?? 0)),
        ];
        

        // ----------------------
        // Stock status (categorized in PHP but fetched once)
        // ----------------------
        $stocks = Stock::where('pharmacy_id', $pharmacy->id)
            ->select(['id', 'item_id', 'quantity', 'remain_Quantity', 'low_stock_percentage', 'expire_date', 'batch_number', 'supplier'])
            ->with('item:id,name') // load only what’s needed
            ->get();

            // dd($stocks);

        /** 
         * @var array<string, \Illuminate\Support\Collection<int, \App\Models\Stock>> $stockStatus 
         */
        $stockStatus = [
            'out_of_stock' => $stocks->where('remain_quantity', 0)->values(),
            'low_stock' => $stocks->filter(function ($stock) {
                if ($stock->remain_quantity <= 0) return false;
                $threshold = ($stock->quantity * $stock->low_stock_percentage) / 100;
                return $stock->remain_quantity <= $threshold;
            })->values(),
            'expired' => $stocks->filter(fn($stock) => Carbon::parse($stock->expire_date)->isPast())->values(),
            'good_stock' => $stocks->filter(function ($stock) {
                if ($stock->remain_quantity <= 0) return false;
                if (Carbon::parse($stock->expire_date)->isPast()) return false;
                $threshold = ($stock->quantity * $stock->low_stock_percentage) / 100;
                return $stock->remain_quantity > $threshold;
            })->values(),
        ];

        // ----------------------
        // Queue email (non-blocking)
        // ----------------------

        // ----------------------
        // Send Notifications
        // ----------------------
        $owner = $pharmacy->owner;

        if (!$owner) {
             $this->warn("⚠️ No owner found for pharmacy: {$pharmacy->name}");
             return;
        }

        // 1. Email
        if ($owner->email && $owner->wantsNotificationChannel('email')) {
             try {
                Mail::to($owner->email)
                    ->send(new DailyPharmacyReport($pharmacy, $salesSummary, $stockStatus, $reportDate, $message));
                $this->info("📧 Queued email for {$pharmacy->name} owner: {$owner->email}");
             } catch (\Exception $e) {
                $this->error("❌ Email failed for {$pharmacy->name}: " . $e->getMessage());
             }
        }

        // 2. Push SMS
        if ($owner->phone && $owner->wantsNotificationChannel('sms')) {
            try {
                $smsMsg = "Daily Report: {$pharmacy->name}\nDate: {$reportDate}\nSales: " . number_format($salesSummary['total_revenue']) . " TZS\nProfit: " . number_format($salesSummary['profit_loss']) . " TZS";
                $sent = $smsService->send($owner->phone, $smsMsg);
                if ($sent) {
                    $this->info("📱 SMS sent to {$owner->phone}");
                } else {
                     $this->error("❌ SMS failed for {$pharmacy->name}");
                }
            } catch (\Exception $e) {
                 $this->error("❌ SMS Exception for {$pharmacy->name}: " . $e->getMessage());
            }
        }

         // 3. WhatsApp
        if ($owner->phone && $owner->wantsNotificationChannel('whatsapp')) {
             try {
                // Construct WhatsApp Message
                $waMsg = "*Daily Report*\n";
                $waMsg .= "Pharmacy: {$pharmacy->name}\n";
                $waMsg .= "Date: {$reportDate}\n\n";
                $waMsg .= "*Summary:*\n";
                $waMsg .= "Total Sales: " . number_format($salesSummary['total_revenue']) . " TZS\n";
                $waMsg .= "Total Cost: " . number_format($salesSummary['total_cost']) . " TZS\n";
                $waMsg .= "Profit/Loss: " . number_format($salesSummary['profit_loss']) . " TZS\n";
                $waMsg .= "Transactions: " . $salesSummary['total_transactions'] . "\n\n";
                $waMsg .= "*Stock Alerts:*\n";
                $waMsg .= "Out of Stock: " . $stockStatus['out_of_stock']->count() . "\n";
                $waMsg .= "Low Stock: " . $stockStatus['low_stock']->count() . "\n";
                $waMsg .= "Expired: " . $stockStatus['expired']->count() . "\n";
                
                $result = $whatsAppService->sendMessage($owner->phone, $waMsg);
                if ($result['success']) {
                      $this->info("💬 WhatsApp sent to {$owner->phone}");
                } else {
                      $this->error("❌ WhatsApp failed for {$pharmacy->name}: " . ($result['error'] ?? 'Unknown error'));
                }

             } catch (\Exception $e) {
                  $this->error("❌ WhatsApp Exception for {$pharmacy->name}: " . $e->getMessage());
             }
        }

        // 4. In-App
        if ($owner->wantsNotificationChannel('database')) {
            try {
                Notification::send($owner, new InAppNotification([
                    'message' => "Daily Report Sent for {$reportDate}", 
                    'type' => 'info'
                ]));
                 $this->info("🔔 In-App notification sent to owner");
            } catch (\Exception $e) {
                 $this->error("❌ In-App failed for {$pharmacy->name}: " . $e->getMessage());
            }
        }
    }
}
