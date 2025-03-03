<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Pharmacy;
use App\Models\Items;
use App\Models\PrinterSetting;
use App\Models\Staff;
use App\Models\Stock;
use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session as FacadesSession;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

use Illuminate\Routing\Controller as BaseController;

use function PHPUnit\Framework\isEmpty;

class SalesController extends BaseController
{

    private $errorMessage = '';
    private $successMessage = '';

    // get printer configuration from the database using setPrinterConfig function from dashboard controller
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $dashboard = new DashboardController();
            $dashboard->setPrinterSettings();
            return $next($request);
        });
    }



    //create a printer's name variable
    private $printerName;

    /**
     * Display a listing of sales.
     */
    public function index()
    {
        // Get sales data for the pharmacies owned by the authenticated user

        $sales = Sales::with('item')->where('pharmacy_id', session('current_pharmacy_id'))
            ->get();

        $printerEnabled = PrinterSetting::where('pharmacy_id', session('current_pharmacy_id'))->first();
        if ($printerEnabled) {
            $usePrinter = $printerEnabled->use_printer;
        } else {
            $usePrinter = false;
        }
        $medicines = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('expire_date', '>', now())->with('item')->get();
        // dd($medicines);

        return view('sales.index', compact('sales', 'medicines', 'usePrinter'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $pharmacies = Pharmacy::where('owner_id', auth::id())->get();
        $items = Items::all();
        $staff = Staff::whereIn('pharmacy_id', $pharmacies->pluck('id'))->get();

        return view('sales.create', compact('pharmacies', 'items', 'staff'));
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $saleDate = $request->input('date');

        // dd($saleDate[0]);

        // item_id represents the stock_id here

        // dd($request->stock_id);
        // Validate the incoming request data for all rows of sales
        try {
            $request->validate([
                //NIMEIGNORE HIVI DATA NDIO IKAFANYA KAZI
                // 'pharmacy_id' => 'required|exists:pharmacies,id',
                // 'staff_id' => 'required|exists:users,id',

                'item_id' => 'required|array',         // Ensure it's an array of item IDs
                'item_id.*' => 'required|exists:stocks,id', // Validate each item ID in the array

                'quantity' => 'required|array',        // Ensure it's an array of quantities
                'quantity.*' => 'required|integer|min:1', // Validate each quantity

                'total_price' => 'required|array',     // Ensure it's an array of prices
                'total_price.*' => 'required|numeric', // Validate each total price

                'amount' => 'required|array',     // Ensure it's an array of prices
                'amount.*' => 'required|numeric', // Validate each total price

                'date' => 'required|array',            // Ensure it's an array of dates
                'date.*' => 'required|date',           // Validate each date
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Not added because: ' . $e->getMessage());
        }


        // Retrieve the pharmacy_id and staff_id for the sale record
        $pharmacyId = session('current_pharmacy_id'); // Ensure this is set correctly in your session
        $staffId = Auth::user()->id;



        // Loop through the arrays of item data and create individual sale records
        try {
            foreach ($request->item_id as $key => $item_id) {

                //update remaning stock
                $stock = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('id', $item_id)->first();
                $remainQuantity = $stock->remain_Quantity - $request->quantity[$key];
                $stock->update(['remain_Quantity' => $remainQuantity]);

                Sales::create([
                    'pharmacy_id' => $pharmacyId,         // Use the pharmacy_id from session
                    'staff_id' => $staffId,                // Use the staff_id from the authenticated user
                    'item_id' => $stock->item_id,
                    'quantity' => $request->quantity[$key],
                    'stock_id' => $request->stock_id[$key],
                    'total_price' => $request->amount[$key],
                    'date' => $request->date[$key],
                ]);
            }

            // $printing = $this->printSaleReceipt($saleDate[0]);           
            $hasPrinter = PrinterSetting::where('pharmacy_id', session('current_pharmacy_id'))->first();
            if ($hasPrinter && $hasPrinter->use_printer) {

                // Get the sales data for the current pharmacy group for the specified date, but ensure the date is in datetime datatype to match the date in the database
                $receipt = Sales::where('pharmacy_id', session('current_pharmacy_id'))
                    ->where('date', $saleDate[0])
                    ->selectRaw('date, sum(total_price) as total_amount, staff_id')
                    ->groupBy('date', 'staff_id')
                    ->first();

                if (!$receipt) {
                    return  false;
                }


                $medicines = Sales::where('pharmacy_id', session('current_pharmacy_id'))->with('item')
                    ->where('date', $receipt->date)
                    ->get();

                $staff = User::where('id', $receipt->staff_id)->first();

                return view('sales.receipt', compact('receipt', 'medicines', 'staff'))->with('success', 'Receipt printed successfully');
            } else {
                if (!$hasPrinter) {
                    // use factory to create fake data and store in  database
                    $createPrinter = PrinterSetting::create([
                        'name' => 'Printer',
                        'ip_address' => '192.168.1.1',
                        'computer_name' => 'Computer Name',
                        'port' => 9100,
                        'pharmacy_id' => session('current_pharmacy_id'),
                        'use_printer' => false
                    ]);
                }
                // if ($hasPrinter->use_printer == false) {
                //     return redirect()->back()->with('error', 'Printer is not set up');
                // }
            }
            return redirect()->back()->with('success', 'Sale added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Not added because: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified sale.
     */
    public function show(Sales $sale)
    {
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified sale.
     */
    public function edit(Sales $sale)
    {
        return view('sales.edit', compact('sale'));
    }

    /**
     * Update the specified sale in storage.
     */
    public function update(Request $request, Sales $sale)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric',
        ]);

        $sale = Sales::where('pharmacy_id', session('current_pharmacy_id'))->where('id', $request->id)->first();
        $sale->update($request->only('quantity', 'total_price'));

        return redirect()->route('sales')->with('success', 'Sale updated successfully.');
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(Request $request, Sales $sale)
    {
        Sales::destroy($request->id);

        return redirect()->route('sales')->with('success', 'Sale deleted successfully!');
    }

    /* implement functions for listing all receipts, and printing last receipt */
    public function allReceipts()
    {
        // Get all receipts from sales data for the current pharmacy group the by date where the sales of same date belongs to the same receipt, return a collection of receipts including serial number, date, total amount and staff name
        $receipts = Sales::where('pharmacy_id', session('current_pharmacy_id'))
            ->selectRaw('date, sum(total_price) as total_amount, staff_id')
            ->groupBy('date', 'staff_id')
            ->orderBy('date', 'desc')
            ->get();

        return view('sales.receipts', compact('receipts'));
    }

    public function printLastReceipt()
    {
        // check if has enabled  receipt printing
        $hasPrinter = PrinterSetting::where('pharmacy_id', session('current_pharmacy_id'))->first();
        if (!$hasPrinter) {
            // use factory to create fake data and store in  database
            $createPrinter = PrinterSetting::create([
                'name' => 'Printer',
                'ip_address' => '192.168.1.1',
                'computer_name' => 'Computer Name',
                'port' => 9100,
                'pharmacy_id' => session('current_pharmacy_id'),
                'use_printer' => false
            ]);
            if ($createPrinter->use_printer == false) {
                return redirect()->back()->with('error', 'Receipt printing is not enabled.');
            }
        } else {
            // use factory to create fake data and store in  database
            if ($hasPrinter->use_printer == false) {
                return redirect()->back()->with('error', 'Receipt printing is not enabled.');
            }
        }

        // Get the last receipt from sales data for the current pharmacy group
        $lastReceipt = Sales::where('pharmacy_id', session('current_pharmacy_id'))
            ->selectRaw('date, sum(total_price) as total_amount, staff_id')
            ->groupBy('date', 'staff_id')
            ->latest('date')
            ->first();

        // Check if receipt exists
        if (!$lastReceipt) {
            return redirect()->back()->with('error', 'No sales data found for the last receipt.');
        }

        $staff = User::where('id', $lastReceipt->staff_id)->first();

        $medicines = Sales::where('pharmacy_id', session('current_pharmacy_id'))->with('item')
            ->where('date', $lastReceipt->date)
            ->get();


        $receipt = $lastReceipt;
        return view('sales.receipt', compact('receipt', 'medicines', 'staff'))->with('success', 'Receipt printed successfully');
    }

    //implement function for printing a specific receipt after receiving the date of sales made
    public function printReceipt(Request $request)
    {

        $hasPrinter = PrinterSetting::where('pharmacy_id', session('current_pharmacy_id'))->first();
        if (!$hasPrinter) {
            // use factory to create fake data and store in  database
            $createPrinter = PrinterSetting::create([
                'name' => 'Printer',
                'ip_address' => '192.168.1.1',
                'computer_name' => 'Computer Name',
                'port' => 9100,
                'pharmacy_id' => session('current_pharmacy_id'),
                'use_printer' => false
            ]);
            if ($createPrinter->use_printer == false) {
                return redirect()->back()->with('error', 'Receipt printing is not enabled.');
            }
        } else {
            // use factory to create fake data and store in  database
            if ($hasPrinter->use_printer == false) {
                return redirect()->back()->with('error', 'Receipt printing is not enabled.');
            }
        }

        // dd($salesDate);
        // Validate the date format
        $request->validate([
            'date' => 'required|date',
        ]);

        // dd($request->date);

        // Get the sales data for the current pharmacy group for the specified date, but ensure the date is in datetime datatype to match the date in the database
        $receipt = Sales::where('pharmacy_id', session('current_pharmacy_id'))
            ->where('date', $request->date)
            ->selectRaw('date, sum(total_price) as total_amount, staff_id')
            ->groupBy('date', 'staff_id')
            ->first();

        if (!$receipt) {
            return redirect()->back()->with('error', 'No sales data found for the specified date.');
        }


        $medicines = Sales::where('pharmacy_id', session('current_pharmacy_id'))->with('item')
            ->where('date', $receipt->date)
            ->get();

        $staff = User::where('id', $receipt->staff_id)->first();

        return view('sales.receipt', compact('receipt', 'medicines', 'staff'));
    }

    public function printSaleReceipt($salesDate)
    {

        $hasPrinter = PrinterSetting::where('pharmacy_id', session('current_pharmacy_id'))->first();
        if (!$hasPrinter) {
            // use factory to create fake data and store in  database
            $createPrinter = PrinterSetting::create([
                'name' => 'Printer',
                'ip_address' => '192.168.1.1',
                'computer_name' => 'Computer Name',
                'port' => 9100,
                'pharmacy_id' => session('current_pharmacy_id'),
                'use_printer' => false
            ]);
            if ($createPrinter->use_printer == false) {
                return false;
            }
        } else {
            // use factory to create fake data and store in  database
            if ($hasPrinter->use_printer == false) {
                return false;
            }
        }

        // Get the sales data for the current pharmacy group for the specified date, but ensure the date is in datetime datatype to match the date in the database
        $receipt = Sales::where('pharmacy_id', session('current_pharmacy_id'))
            ->where('date', $salesDate)
            ->selectRaw('date, sum(total_price) as total_amount, staff_id')
            ->groupBy('date', 'staff_id')
            ->first();

        if (!$receipt) {
            return  false;
        }


        $medicines = Sales::where('pharmacy_id', session('current_pharmacy_id'))->with('item')
            ->where('date', $receipt->date)
            ->get();

        $staff = User::where('id', $receipt->staff_id)->first();
        // dd($staff);
        return view('sales.receipt', compact('receipt', 'medicines', 'staff'))->with('success', 'Receipt printed successfully');
    }

    // Function to get the default printer name on Windows
    function getDefaultPrinterName()
    {
        // dd("fdfvd ");
        $output = shell_exec('wmic printer where "Default=True" get Name /value');


        if ($output) {
            preg_match('/Name=(.+)/', $output, $matches);
            return isset($matches[1]) ? trim($matches[1]) : null;
        }
        return null;
    }
}
