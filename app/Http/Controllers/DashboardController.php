<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use  Illuminate\Support\Carbon;
use App\Models\Staff;
use App\Models\Items;
use App\Models\Package;
use App\Models\PrinterSetting;
use App\Models\Sales;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\InAppNotification;
use App\Notifications\WelcomeNotification;
use App\Notifications\SmsNotification;
use Illuminate\Support\Facades\Log;
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

        // $user = User::whereId(Auth::user()->id)->first(); 
        // $notification = [
        //     'message'=>'Final trial message!',
        //     'type'=>'success',
        // ];
        // $user->notify(new InAppNotification( $notification));

        // $user->notify(new WelcomeNotification);
        // $notifyUser = Auth::user();
        // Notification::send($notifyUser, new WelcomeNotification);
        // // dd('done');

        if (Auth::user()->role == "super") {
            return view('superAdmin.index');
        }

        if (Auth::user()->role == "agent") {
            // get all pharmacies under this agent
            $totalPharmacies = Pharmacy::where('agent_id', Auth::user()->id)->count();
            $totalPackages = Package::count();

            $activePharmacies  = Pharmacy::where('agent_id', Auth::user()->id)
                ->whereHas('owner', function ($query) {
                    $query->whereHas('contracts', function ($query) {
                        $query->where('is_current_contract', 1)
                            ->where('status', 'active');
                    });
                })
                ->count();

            // inactive pharmacies
            $inactivePharmacies = $totalPharmacies - $activePharmacies;

            $totalMessages = 0;
            $totalCases = 0;

            return view('agent.index', compact('totalPharmacies', 'totalPackages', 'activePharmacies', 'inactivePharmacies', 'totalMessages', 'totalCases'));
        }

        // Group all stocks by items and calculate the total quantity for each item
        // $sellMedicines = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('expire_date', '>', now())->where('remain_Quantity', '>', 0)->with('item')->get();
        $sellMedicines = Stock::select(
            // generate a new id for each row by concatenating item_id and selling_price
                            DB::raw("item_id || selling_price as id"),
                            'item_id',
                            DB::raw("SUM(remain_Quantity) as remain_Quantity"),
                            'selling_price'
                        )
                        ->where('pharmacy_id', session('current_pharmacy_id'))
                        ->where('expire_date', '>', now())
                        ->groupBy('item_id', 'selling_price')
                        ->with('item')
                        ->get();

                        // dd($sellMedicines);

        $totalMedicines = Items::where('pharmacy_id', session('current_pharmacy_id'))->count();
        $totalPharmacies = Pharmacy::where('owner_id', Auth::user()->id)->count();
        $totalSales = Sales::where('pharmacy_id', session('current_pharmacy_id'))->whereDate('created_at', Carbon::today())->sum('total_price');

        if (Auth::user()->role == "staff") {
            $staff = Staff::where('user_id', Auth::user()->id)->first();
            $totalSales = Sales::where('pharmacy_id', session('current_pharmacy_id'))->where('staff_id', $staff->id)->sum('total_price');
        }

        $totalStaff = Staff::where('pharmacy_id', session('current_pharmacy_id'))->count(); // Adjust as needed
        $lowStockCount = Stock::whereColumn('low_stock_percentage', '>', 'remain_Quantity')->where('pharmacy_id', session('current_pharmacy_id'))->count(); // Low stock threshold
        $stockExpired = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('expire_date', '<', now())->count();
        // dd($lowStockCount);

        if (Auth::user()->role == "staff" || Auth::user()->role == "admin") {
            $staff = Staff::where('user_id', Auth::user()->id)->first();
            $pharmacy = Pharmacy::where('id', $staff->pharmacy_id)->first();
            session(['current_pharmacy_id' => $pharmacy->id]);
        }
        $pharmacyId = session('current_pharmacy_id');
        session(['pharmacy_name' => Pharmacy::where('id', session('current_pharmacy_id'))->value('name')]);
        session(['location' => Pharmacy::where('id', session('current_pharmacy_id'))->value('location')]);

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

            // Get pharmacies that belong to the authenticated owner
            $pharmacies = Pharmacy::where('owner_id', Auth::user()->id)->get();

            if (Pharmacy::where('owner_id', Auth::user()->id)->count() > 0) {
                // Detect the agent responsible for these pharmacies
                $agent = User::whereHas('agent', function ($query) use ($pharmacies) {
                    $query->whereIn('id', $pharmacies->pluck('id'));
                })->first();

                // dd($agent);
                // Store agent ID in the session if found
                if ($agent) {
                    session(['agent' => $agent->id]);
                    session(['agentData' => $agent]);
                    // dd(session('agentData'));
                    if (Auth::user()->contracts->where('is_current_contract', 1)->count() < 1) {
                        return redirect()->route('myContracts')->with('info', "Contact your agent to subscribe you first!");
                    }
                } else {
                    // clear session
                    session()->forget('agent');
                    session()->forget('agentData');
                }
            }


            if (Auth::user()->contracts->where('is_current_contract', 1)->count() < 1) {
                return redirect()->route('myContracts')->with('info', 'Welcome on board! Subscribe first to continue using our products!');
            }

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

    public function storePrinterSettings(Request $request)
    {
        try {
            $request->validate([
                'printer' => 'required|string',
                'ip_address' => 'required|ip',
                'computer_name' => 'required|string',
                'port' => 'nullable|numeric'
            ]);

            // Save printer configuration (e.g., in the database)
            $printerName = $request->input('printer');
            $ipAddress = $request->input('ip_address');
            $computer_name = $request->input('computer_name');
            $port = $request->input('port');

            // check if pharmacy has printer configuration
            $printer = PrinterSetting::where('pharmacy_id', session('current_pharmacy_id'))->first();

            if ($printer) {
                // Update printer configuration
                $printer->update([
                    'name' => $printerName,
                    'ip_address' => $ipAddress,
                    'computer_name' => $computer_name,
                    'port' => $port
                ]);
                // Example logic for updating or logging printer details
                Log::info("Printer updated: $printerName by IP: $ipAddress");
                return redirect()->back()->with('success', 'Printer configuration updated successfully.');
            } else {
                // Save printer configuration
                PrinterSetting::create([
                    'name' => $printerName,
                    'ip_address' => $ipAddress,
                    'computer_name' => $computer_name,
                    'port' => $port,
                    'pharmacy_id' => session('current_pharmacy_id')
                ]);

                // set printer configuration session
                session(['printer' => $printerName]);
                session(['printer_ip_address' => $ipAddress]);
                session(['computer_name' => $computer_name]);

                // Example logic for storing or logging printer details
                Log::info("Printer selected: $printerName by IP: $ipAddress");
                return redirect()->back()->with('success', 'Printer configuration saved successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // function to get printer settings
    public function setPrinterSettings()
    {
        $printer = PrinterSetting::where('pharmacy_id', session('current_pharmacy_id'))->first();
        if ($printer) {
            session(['printer' => $printer->name]);
            session(['printer_ip_address' => $printer->ip_address]);
            session(['computer_name' => $printer->computer_name]);
            session(['printer_port' => $printer->port]);
            session(['use_printer' => $printer->use_printer]);
        } else {
            session(['printer' => null]);
            session(['printer_ip_address' => null]);
            session(['computer_name' => null]);
            session(['printer_port' => null]);
            // session(['use_printer' => false]);
            redirect()->route('sales');
            // ->with('error', 'No printer configuration found.');
        }
        return;
    }

    // function to update printer use status
    public function updatePrinterStatus(Request $request)
    {
        $usePrinter = false;

        try {
            $request->validate([
                'new_status' => 'required|boolean',
                'current_status' => 'required|boolean'
            ]);

            $printer = PrinterSetting::where('pharmacy_id', session('current_pharmacy_id'))->first();
            if ($request->input('new_status')) {
                if (!$printer) {
                    session(['use_printer' => true]);

                    // use factory to create fake data and store in  database
                    $printer = PrinterSetting::create([
                        'name' => 'Printer',
                        'ip_address' => '192.168.1.1',
                        'computer_name' => 'Computer Name',
                        'port' => 9100,
                        'pharmacy_id' => session('current_pharmacy_id'),
                        'use_printer' => true
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'successfully enabled!'
                    ]);
                }
                if ($printer) {
                    session(['printer' => $printer->name]);
                    session(['printer_ip_address' => $printer->ip_address]);
                    session(['computer_name' => $printer->computer_name]);
                    session(['printer_port' => $printer->port]);
                    session(['use_printer' => true]);

                    $printer->update([
                        'use_printer' => $request->input('new_status')
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'successfully enabled!'
                    ]);
                }
            } else {
                if (!$printer) {
                    session(['printer' => null]);
                    session(['printer_ip_address' => null]);
                    session(['computer_name' => null]);
                    session(['printer_port' => null]);
                    session(['use_printer' => false]);

                    // use factory to create fake data and store in  database
                    $printer = PrinterSetting::create([
                        'name' => 'Printer',
                        'ip_address' => '192.168.1.1',
                        'computer_name' => 'Computer Name',
                        'port' => 9100,
                        'pharmacy_id' => session('current_pharmacy_id'),
                        'use_printer' => true
                    ]);

                    // return json response
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Disabled successfully!'
                    ]);
                }
                if ($printer) {
                    session(['printer' => $printer->name]);
                    session(['printer_ip_address' => $printer->ip_address]);
                    session(['computer_name' => $printer->computer_name]);
                    session(['printer_port' => $printer->port]);
                    session(['use_printer' => false]);

                    $printer->update([
                        'use_printer' => $request->input('new_status')
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'successfully disabled!'
                    ]);
                }
            }
        } catch (\Exception $e) {
            // return json response
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
