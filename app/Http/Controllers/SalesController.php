<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Pharmacy;
use App\Models\Items;
use App\Models\PrinterSetting;
use App\Models\Staff;
use App\Models\Stock;
use App\Models\User;
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

                // dd($request);
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
            //call the function to print the last receipt
            try {
                $this->printLastReceipt();
            } catch (\Exception $e) {
                // 
            }

            return redirect()->route('sales')->with('success', 'Sales recorded successfully.');
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

        try {
            // Retrieve printer details from the session
            $printerName = session('printer');
            $printerIp = session('printer_ip_address');
            $computer_name = session('computer_name');

            // Validate printer details
            if (!$printerName || !$printerIp) {
                throw new \Exception("Printer details are missing. Please select a printer first.");
            }

            // Get the network printer path
            $printerPath = $this->getConnectedPrinterName($printerName, $printerIp, $computer_name);

            // Log the printer path for debugging
            Log::info('Using Printer Path: ' . $printerPath);

            // Initialize the printer connection
            $connector = new WindowsPrintConnector($printerPath, 9100); // Port 9100 is standard for network printers
            $printer = new Printer($connector);

            // Prepare the receipt content
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Pharmacy: \n" . session('pharmacy_name') . "\n");
            $printer->text("Address: \n" . session('location') . "\n");
            $printer->text("----------------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Date:   " . $lastReceipt->date . "\n");
            $printer->text("Pharmacist:   " . $staff->name . "\n");
            $printer->text("Medicines:\n");

            // List medicines sold
            foreach ($medicines as $medicine) {
                $printer->text("\t" . $medicine->item->name . "\n");
            }

            $printer->text("Total Amount:  TZS " . number_format($lastReceipt->total_amount, 0) . "\n");
            $printer->text("----------------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Thank you for your purchase!\n");
            $printer->feed(3); // Feed 3 lines

            // Cut the paper
            $printer->cut();

            // Close the printer connection
            $printer->close();

            return redirect()->back()->with('success', 'Last receipt printed successfully.');
        } catch (\Exception $e) {
            Log::error('Error printing receipt: ' . $e->getMessage());
            // return redirect()->back()->with('error', 'Error printing last receipt: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    private function getConnectedPrinterName($printerName, $printerIp, $computer_name)
    {
        if (!$printerName || !$printerIp) {
            throw new \Exception("Printer details are missing.");
        }

        // Construct the network printer path
        // Windows network printer path using SMB notation
        // $computerName = $printerIp; // Using the IP address as the computer name
        $computerName = isEmpty(getenv('COMPUTERNAME')) ? $computer_name : getenv('COMPUTERNAME'); // or hard-code your Windows computer name

        if (PHP_OS_FAMILY === 'Windows') {
            // $printerPath = 'smb://' . $computerName . '/' . $printerName;
            $printerPath = 'smb://' . $printerIp . '/' . $printerName;
        } elseif (PHP_OS_FAMILY === 'Linux') {
            // Linux network printer path
            // $printerPath = '/dev/usb/' . $printerName;
            // $printerPath = 'smb://' . $computerName . '/' . $printerName;
            $printerPath = 'smb://' . $printerIp . '/' . $printerName;
        } else {
            // Fallback for other environments
            // $printerPath = 'smb://' . $printerIp . '/' . $printerName;
            $printerPath = 'smb://' . $printerIp . '/' . $printerName;
        }

        Log::info('Constructed Printer Path: ' . $printerPath);
        return $printerPath;
    }


    //implement function for printing a specific receipt after receiving the date of sales made
    public function printReceipt(Request $request)
    {

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

        // dd($staff->name);

        try {
            // Automatically detect the active printer name and path
            $printerPath = $this->getConnectedPrinterName(session('printer'), session('printer_ip_address'), session('computer_name'));

            if (!$printerPath) {
                throw new \Exception("No connected printer detected.");
            }

            // Log the printer path for debugging
            Log::info('Using Printer Path: ' . $printerPath);

            // Initialize the printer connection using the network printer path
            $connector = new WindowsPrintConnector($printerPath);
            $printer = new Printer($connector);

            // Prepare the receipt content
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("------START OF RECEIPT------\n");
            $printer->text("Pharmacy: " . session('pharmacy_name') . "\n");
            $printer->text("Address: " . session('location') . "\n");
            $printer->text("Description: Medicine  Purchases\n");
            $printer->text("----------------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Date:" . $receipt->date . "\n");
            $printer->text("Pharmacist:" . $staff->name . "\n");

            $printer->text("Medicines:");
            //list medicines sold
            foreach ($medicines as $medicine) {
                $printer->text($medicine->item->name . "\n" . "\r            \r");
            }

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("----------------------------------\n");
            $printer->text("\r        \rTotal Amount:  TZS " . number_format($receipt->total_amount, 0) . "/= \n");
            // $printer->text("For: Medicine \n");
            $printer->text("----------------------------------\n");

            $printer->text("Thank you for your purchase!\n");
            $printer->feed(1); // Feed 3 lines
            $printer->text("------END OF RECEIPT------\n");

            // Cut the paper
            $printer->cut();

            // Close the printer connection
            $printer->close();

            //clear the printer queue
            // $this->clearPrinterQueue($this->printerName);

            return redirect()->back()->with('success', 'Receipt printed successfully.');
        } catch (\Exception $e) {

            //clear the printer queue
            // $this->clearPrinterQueue($this->printerName);

            // return redirect()->back()->with('error', 'Error printing receipt: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    //implement function for clearing the printer queue
    public function clearPrinterQueue($printerName)
    {
        try {
            // Stop the Spooler service
            exec('net stop spooler', $output, $statusStop);
            if ($statusStop !== 0) {
                throw new \Exception("Failed to stop spooler service. Output: " . implode("\n", $output));
            }

            // Get the printer queue folder path
            $printerSpoolPath = 'C:\\Windows\\System32\\spool\\PRINTERS\\*';

            // Clear only the jobs related to the specific printer
            exec('del /Q /F ' . $printerSpoolPath, $output, $statusDel);
            if ($statusDel !== 0) {
                throw new \Exception("Failed to clear printer queue. Output: " . implode("\n", $output));
            }

            // Restart the Spooler service
            exec('net start spooler', $output, $statusStart);
            if ($statusStart !== 0) {
                throw new \Exception("Failed to restart spooler service. Output: " . implode("\n", $output));
            }

            // return "Printer queue cleared successfully for printer: $printerName";
            return;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
