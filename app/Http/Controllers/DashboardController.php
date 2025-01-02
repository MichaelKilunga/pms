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
use App\Models\User;
use App\Notifications\InAppNotification;
use App\Notifications\WelcomeNotification;
use App\Notifications\SmsNotification;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;
use function PHPUnit\Framework\isEmpty;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $user = User::find(1); // Example user
        // $phoneNumber = 'user_phone_number';
        // $message = 'Your SMS notification message here';
        // // Send the SMS
        // Notification::send($user, new SmsNotification($phoneNumber, $message));

        $user = User::whereId(Auth::user()->id)->first(); 
        $notification = [
            'message'=>'Final trial message!',
            'type'=>'success',
        ];
        $user->notify(new InAppNotification( $notification));
        
        // $user->notify(new WelcomeNotification);
        // $notifyUser = Auth::user();
        // Notification::send($notifyUser, new WelcomeNotification);
        // // dd('done');

        if (Auth::user()->role == "super") {
            return view('superAdmin.index');
        }
        $sellMedicines = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('expire_date', '>', now())->with('item')->get();
        $totalMedicines = Items::where('pharmacy_id', session('current_pharmacy_id'))->count();
        $totalPharmacies = Pharmacy::where('owner_id', Auth::user()->id)->count();
        $totalSales = Sales::where('pharmacy_id', session('current_pharmacy_id'))->whereDate('created_at', Carbon::today())->sum('total_price');

        if (Auth::user()->role == "staff") {
            $staff = Staff::where('user_id', Auth::user()->id)->first();
            $totalSales = Sales::where('pharmacy_id', session('current_pharmacy_id'))->where('staff_id', $staff->id)->sum('total_price');
        }

        $totalStaff = Staff::where('pharmacy_id', session('current_pharmacy_id'))->count(); // Adjust as needed
        $lowStockCount = Stock::where('low_stock_percentage', '<', 10)->where('pharmacy_id', session('current_pharmacy_id'))->count(); // Low stock threshold
        $stockExpired = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('expire_date', '<', now())->count();
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
                DB::raw('COALESCE(SUM(sales.total_price), 0) as total_sales'),
                DB::raw('COALESCE(stocks.remain_Quantity, 0) as total_stock')
            )
            // ->whereDate('sales.created_at', Carbon::today())
            ->groupBy('items.id', 'items.name')
            ->havingRaw('SUM(sales.quantity) > 0') // Exclude items with no sales
            ->get();

        if (Auth::user()->role == "staff") {
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
                    DB::raw('COALESCE(SUM(sales.total_price), 0) as total_sales'),
                    DB::raw('COALESCE(stocks.remain_Quantity, 0) as total_stock')
                )
                ->where('sales.staff_id', Auth::user()->id)
                ->groupBy('items.id', 'items.name')
                ->havingRaw('SUM(sales.quantity) > 0') // Exclude items with no sales
                ->get();
        }
        $medicineNames = $itemsSummary->pluck('medicine_name');
        $medicineStock = $itemsSummary->pluck('total_stock');
        $medicineSales = $itemsSummary->pluck('total_sales');

        $medicines = $itemsSummary;


        $filter = 'day';
        $query = Sales::where('pharmacy_id', session('current_pharmacy_id'))->whereDate('created_at', Carbon::today());

        $filteredTotalSales = $query->sum('total_price');
        if (Auth::user()->role == "staff") {
            // $staff = Staff::where('user_id', Auth::user()->id)->first();
            $filteredTotalSales = $query->where('staff_id', Auth::user()->id)->sum('total_price');
            // dd($filteredTotalSales);
        }


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
                    'filter',
                    'sellMedicines'
                ));
            } else {
                session(['guest-owner' => true]);
                return view('guest-dashboard');
            }
        } else {
            $staff = Staff::where('user_id', Auth::user()->id)->first();
            $totalSales = Sales::where('pharmacy_id', session('current_pharmacy_id'))->where('staff_id',  Auth::user()->id)->whereDate('created_at', Carbon::today())->sum('total_price');
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
                'stockExpired',
                'sellMedicines'
            ));
        }
    }

    public function filterSales(Request $request)
    {
        $duration = $request->filter;
        $pharmacyId = session('current_pharmacy_id');

        $query = Sales::where('pharmacy_id', $pharmacyId);

        if (Auth::user()->role == "staff") {
            // $staff = Staff::where('user_id', Auth::user()->id)->first();
            // dd($staff);
            $query->where('staff_id', Auth::user()->id);
        }

        switch ($duration) {
            case 'day':
                $query->whereDate('created_at', Carbon::today());
                $filteredSales = DB::table('items')
                    ->leftJoin('sales', function ($join) use ($pharmacyId) {
                        $join->on('items.id', '=', 'sales.item_id')
                            ->where('sales.pharmacy_id', '=', $pharmacyId);
                    })
                    ->select(
                        'items.name as medicine_name',
                        DB::raw('COALESCE(SUM(sales.total_price), 0) as total_sales')
                    )
                    ->whereDate('sales.created_at', Carbon::today()) // Correctly filter by date
                    ->groupBy('items.id', 'items.name')
                    ->get();
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $filteredSales = DB::table('items')
                    ->leftJoin('sales', function ($join) use ($pharmacyId) {
                        $join->on('items.id', '=', 'sales.item_id')
                            ->where('sales.pharmacy_id', '=', $pharmacyId);
                    })
                    ->select(
                        'items.name as medicine_name',
                        DB::raw('COALESCE(SUM(sales.total_price), 0) as total_sales')
                    )
                    ->whereBetween('sales.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy('items.id', 'items.name')
                    ->get();
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month);
                $filteredSales = DB::table('items')
                    ->leftJoin('sales', function ($join) use ($pharmacyId) {
                        $join->on('items.id', '=', 'sales.item_id')
                            ->where('sales.pharmacy_id', '=', $pharmacyId);
                    })
                    ->select(
                        'items.name as medicine_name',
                        DB::raw('COALESCE(SUM(sales.total_price), 0) as total_sales')
                    )
                    ->whereMonth('sales.created_at', Carbon::now()->month)
                    ->groupBy('items.id', 'items.name')
                    ->get();
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                $filteredSales = DB::table('items')
                    ->leftJoin('sales', function ($join) use ($pharmacyId) {
                        $join->on('items.id', '=', 'sales.item_id')
                            ->where('sales.pharmacy_id', '=', $pharmacyId);
                    })
                    ->select(
                        'items.name as medicine_name',
                        DB::raw('COALESCE(SUM(sales.total_price), 0) as total_sales')
                    )
                    ->whereYear('sales.created_at', Carbon::now()->year)
                    ->groupBy('items.id', 'items.name')
                    ->get();
                break;
            default:
                return response()->json(['error' => 'Invalid filter'], 400);
        }

        $filteredTotalSales = $query->sum('total_price');

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
