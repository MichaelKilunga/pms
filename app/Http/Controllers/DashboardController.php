<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use  Illuminate\Support\Carbon;
use App\Models\Staff;
use App\Models\Items;
use App\Models\Sales;
use App\Models\Stock;

use function Pest\Laravel\get;
use function PHPUnit\Framework\isEmpty;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalMedicines = Items::where('pharmacy_id', session('current_pharmacy_id'))->count();
        $totalPharmacies = Pharmacy::where('owner_id', Auth::user()->id)->count();
        $totalSales = Sales::where('pharmacy_id', session('current_pharmacy_id'))->sum('total_price'); // Adjust as needed
        $totalStaff = Staff::where('pharmacy_id', session('current_pharmacy_id'))->count(); // Adjust as needed
        $lowStockCount = Stock::where('quantity', '<', 10)->where('pharmacy_id', session('current_pharmacy_id'))->count(); // Low stock threshold
        $stockExpired = Stock::where('expire_date', '<', now())->count();
        // dd($lowStockCount);

        if (Auth::user()->role == "staff" || Auth::user()->role == "admin") {
            $staff = Staff::where('user_id', Auth::user()->id)->first();
            $pharmacy = Pharmacy::where('id', $staff->pharmacy_id)->first();
            session(['current_pharmacy_id' => $pharmacy->id]);
        }
        $pharmacyId = session('current_pharmacy_id');
        session(['pharmacy_name' => Pharmacy::where('id', session('current_pharmacy_id'))->value('name')]);

        $itemsSummary = DB::table('items')
            ->leftJoin('sales', function ($join) use ($pharmacyId) {
                $join->on('items.id', '=', 'sales.item_id')
                    ->where('sales.pharmacy_id', '=', $pharmacyId);
            })
            ->leftJoin('stocks', function ($join) use ($pharmacyId) {
                $join->on('items.id', '=', 'stocks.item_id')
                    ->where('stocks.pharmacy_id', '=', $pharmacyId);
            })
            ->select(
                'items.name as medicine_name',
                DB::raw('COALESCE(SUM(sales.quantity), 0) as total_sales'),
                DB::raw('COALESCE(SUM(stocks.quantity), 0) as total_stock')
            )
            ->groupBy('items.id', 'items.name')
            ->havingRaw('SUM(sales.quantity) > 0') // Exclude items with no sales
            ->get();

        $medicineNames = $itemsSummary->pluck('medicine_name');
        $medicineStock = $itemsSummary->pluck('total_stock');
        $medicineSales = $itemsSummary->pluck('total_sales');

        $medicines = $itemsSummary;


        $filter = 'day';
        $query = Sales::where('pharmacy_id', session('current_pharmacy_id'))->whereDate('created_at', Carbon::today());
        $filteredTotalSales = $query->sum('total_price');
        // dd($filteredTotalSales);

        if (Auth::user()->role == 'owner') {

            $pharmacies = Pharmacy::where('owner_id', Auth::user()->id)->get();
            // dd($pharmacies->count());
            if ($pharmacies->count() > 0) {
                return view('dashboard', compact(
                    'pharmacies',
                    'totalMedicines',
                    'totalSales',
                    'lowStockCount',
                    'medicines',
                    'medicineNames',
                    'medicineStock',
                    'medicineSales',
                    'totalStaff',
                    'totalPharmacies',
                    'stockExpired',
                    'filteredTotalSales',
                    'filter'
                ));
            } else {
                session(['guest-owner' => true]);
                return view('guest-dashboard');
            }
        } else {
            // $staff = Staff::where('user_id', Auth::user()->id)->first();
            // $pharmacy = Pharmacy::where('id', $staff->pharmacy_id)->get();
            // session(['current_pharmacy_id'=>$pharmacy->id]);
            return view('dashboard', compact(
                'pharmacy',
                'totalMedicines',
                'totalSales',
                'lowStockCount',
                'medicines',
                'medicineNames',
                'medicineStock',
                'medicineSales',
                'totalStaff',
                'totalPharmacies',
                'filteredTotalSales',
                'filter',
                'stockExpired'
            ));
        }
    }

    public function filterSales(Request $request)
    {
        $duration = $request->filter;
        $pharmacyId = session('current_pharmacy_id');

        $query = Sales::where('pharmacy_id', $pharmacyId);

        switch ($duration) {
            case 'day':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            default:
                return response()->json(['error' => 'Invalid filter'], 400);
        }

        $filteredTotalSales = $query->sum('total_price');

        // Fetch filtered sales data grouped by medicine
        $filteredSales = DB::table('items')
            ->leftJoin('sales', function ($join) use ($pharmacyId, $query) {
                $join->on('items.id', '=', 'sales.item_id')
                    ->where('sales.pharmacy_id', '=', $pharmacyId)
                    ->whereIn('sales.id', $query->pluck('id')); // Use filtered sales IDs
            })
            ->select(
                'items.name as medicine_name',
                DB::raw('COALESCE(SUM(sales.quantity), 0) as total_sales')
            )
            ->groupBy('items.id', 'items.name')
            ->get();

        return response()->json([
            'filteredTotalSales' => $filteredTotalSales ?? 0,
            'medicineNames' => $filteredSales->pluck('medicine_name'),
            'medicineSales' => $filteredSales->pluck('total_sales')
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
