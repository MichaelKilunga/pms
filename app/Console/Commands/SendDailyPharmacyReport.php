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
    public function handle()
    {
        $pharmacyId = $this->option('pharmacy_id');
        
        if ($pharmacyId) {
            $pharmacies = Pharmacy::where('id', $pharmacyId)->with('owner')->get();
        } else {
            $pharmacies = Pharmacy::with('owner')->get();
        }

        foreach ($pharmacies as $pharmacy) {
            $this->sendReportForPharmacy($pharmacy);
        }

        $this->info('Daily pharmacy reports sent successfully');
    }

    private function sendReportForPharmacy(Pharmacy $pharmacy)
    {
        $today = Carbon::today();
        
        // Calculate sales data for today
        $todaySales = Sales::where('pharmacy_id', $pharmacy->id)
            ->whereDate('date', $today)
            ->with(['stock.item'])
            ->get();

        $salesSummary = [
            'total_revenue' => $todaySales->sum('total_price'),
            'total_cost' => $todaySales->sum(function($sale) {
                return $sale->stock ? $sale->stock->buying_price * $sale->quantity : 0;
            }),
            'total_transactions' => $todaySales->count(),
        ];
        
        $salesSummary['profit_loss'] = $salesSummary['total_revenue'] - $salesSummary['total_cost'];

        // Get stock status
        $stocks = Stock::where('pharmacy_id', $pharmacy->id)
            ->with('item')
            ->get();

        $stockStatus = [
            'out_of_stock' => $stocks->where('remain_Quantity', 0)->values(),
            'low_stock' => $stocks->filter(function($stock) {
                if ($stock->remain_Quantity <= 0) return false;
                $threshold = ($stock->quantity * $stock->low_stock_percentage) / 100;
                return $stock->remain_Quantity <= $threshold;
            })->values(),
            'expired' => $stocks->filter(function($stock) {
                return Carbon::parse($stock->expire_date)->isPast();
            })->values(),
            'good_stock' => $stocks->filter(function($stock) {
                if ($stock->remain_Quantity <= 0) return false;
                if (Carbon::parse($stock->expire_date)->isPast()) return false;
                $threshold = ($stock->quantity * $stock->low_stock_percentage) / 100;
                return $stock->remain_Quantity > $threshold;
            })->values(),
        ];

        // Send email if pharmacy has an owner with email
        if ($pharmacy->owner && $pharmacy->owner->email) {
            Mail::to($pharmacy->owner->email)
                ->send(new DailyPharmacyReport($pharmacy, $salesSummary, $stockStatus));
            
            $this->info("Report sent to {$pharmacy->name} owner: {$pharmacy->owner->email}");
        } else {
            $this->warn("No email found for pharmacy: {$pharmacy->name}");
        }
    }
}
