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
use Yajra\DataTables\Facades\DataTables;

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
    public function index(Request $request)
    {
        // Get sales data for the pharmacies owned by the authenticated user

        // $sales = Sales::with(['item','salesReturn'])->where('pharmacy_id', session('current_pharmacy_id'))->orderBy('date', 'desc')
        //     ->get();

        if ($request->ajax()) {
            $sales = Sales::with(['item', 'salesReturn'])
                ->where('sales.pharmacy_id', session('current_pharmacy_id'))
                ->orderBy('date', 'desc');

            return DataTables::of($sales)
                ->addIndexColumn() // Adds an index column
                ->addColumn('sales_name', function ($sale) {
                    return $sale->item->name;
                })
                ->addColumn('price', function ($sale) {
                    return number_format($sale->total_price, 0);
                })
                ->addColumn('quantity', function ($sale) {
                    return $sale->quantity;
                })
                ->addColumn('total_price', function ($sale) {
                    return number_format($sale->total_price*$sale->quantity, 0);
                })
                ->addColumn('date', function ($sale) {
                    return $sale->date;
                })
                ->addColumn('actions', function ($sale) {
                    return '
                        <div class="d-flex justify-content-between">
                            <!-- View Modal -->
                            <a href="#" class="btn btn-primary  btn-sm" data-bs-toggle="modal"
                                data-bs-target="#viewSaleModal'. $sale->id .'">
                                <i class="bi bi-eye"></i>
                            </a>
                
                            <!-- View Sale Modal -->
                            <div class="modal fade" id="viewSaleModal'. $sale->id .'" tabindex="-1"
                                aria-labelledby="viewSaleModalLabel'. $sale->id .'" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewSaleModalLabel'. $sale->id .'">Sale Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div><strong>Sales Name:</strong> '. htmlspecialchars($sale->item->name, ENT_QUOTES, 'UTF-8') .'</div>
                                            <div><strong>Price:</strong> '. ($sale->quantity > 0 ? number_format($sale->total_price / 1, 0) : 'N/A') .'</div>
                                            <div><strong>Quantity:</strong> '. $sale->quantity .'</div>
                                            <div><strong>Amount:</strong> '. number_format($sale->total_price*$sale->quantity, 0) .'</div>
                                            <div><strong>Date:</strong> '. $sale->date .'</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <!-- Sales Return Link (Only if salesReturn is NULL) -->
                            '. ((($sale->salesReturn == null) || ($sale->salesReturn->return_status == 'rejected')) ? '
                                <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#salesReturnModal'. $sale->id .'">
                                    <i class="bi bi-arrow-return-left"></i>
                                </a>
                            ' : '') .'
                
                            <!-- Sales Return Modal -->
                            <div class="modal fade" id="salesReturnModal'. $sale->id .'" tabindex="-1"
                                aria-labelledby="salesReturnModalLabel'. $sale->id .'" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="salesReturnModalLabel'. $sale->id .'">Return Sales for 
                                                <span class="text-primary">'. htmlspecialchars($sale->item->name, ENT_QUOTES, 'UTF-8') .'</span>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                
                                        <form id="salesReturnForm" method="POST" action="'. route('salesReturns.store') .'">
                                            '. csrf_field() .'
                                            <div class="modal-body">
                                                <input type="hidden" name="sale_id" value="'. $sale->id .'">
                
                                                <div class="mb-3">
                                                    <label for="quantity" class="form-label">Quantity to Return</label>
                                                    <input type="number" name="quantity" class="form-control" readonly min="1"
                                                        max="'. $sale->quantity .'" value="'. $sale->quantity .'"
                                                        required>
                                                </div>
                
                                                <div class="mb-3">
                                                    <label for="reason" class="form-label">Reason for Return</label>
                                                    <textarea name="reason" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit Return</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                
                            <!-- Edit Button (Hidden) -->
                            <a href="#" class="btn btn-success btn-sm" hidden data-bs-toggle="modal"
                                data-bs-target="#editSaleModal'. $sale->id .'">
                                <i class="bi bi-pencil"></i>
                            </a>
                
                            <!-- Delete Form (Hidden) -->
                            <form action="'. route('sales.destroy', $sale->id) .'" method="POST" style="display:inline;">
                                '. csrf_field() .'
                                '. method_field('DELETE') .'
                                <button type="submit" hidden class="btn btn-sm btn-danger"
                                    onclick="return confirm(\'Are you sure to delete this sale?\')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    ';
                })                
                ->rawColumns(['actions'])
                ->make(true);
        }

        // dd($sales);

        // $printerEnabled = PrinterSetting::where('pharmacy_id', session('current_pharmacy_id'))->first();
        // if ($printerEnabled) {
        //     $usePrinter = $printerEnabled->use_printer;
        // } else {
        //     $usePrinter = false;
        // }
        // $medicines = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('expire_date', '>', now())->where('remain_Quantity', '>', 0)->with('item')->get();
        // // dd($medicines);

        // return view('sales.index', compact('sales', 'medicines', 'usePrinter'));
        return view('sales.index');

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
        // dd($request->all());

        // item_id represents the stock_id here

        
        /* extract the id from "item_id" request data by detecting price digits from right to left of item_id, and then get the remained right digits as id
            if the price is 1500 and item_id is 231000 then the id will be 23 */
            // $newItemIds = [];

            // foreach ($request->item_id as $key => $item_id) {
            //     $price = $request->total_price[$key];
            //     $priceDigits = strlen((string) $price);
            //     $id = substr($item_id, 0, -$priceDigits); // Extract from left
            //     $newItemIds[$key] = $id;
            // }
            
            // $request->merge(['item_id' => $newItemIds]);
            
            // dd($request->all());

        // Validate the incoming request data for all rows of sales
        try {
            $request->validate([
                //NIMEIGNORE HIVI DATA NDIO IKAFANYA KAZI
                // 'pharmacy_id' => 'required|exists:pharmacies,id',
                // 'staff_id' => 'required|exists:users,id',

                'item_id' => 'required|array',         // Ensure it's an array of item IDs
                // 'item_id.*' => 'required|exists:stocks,id', // Validate each item ID in the array
                'item_id.*' => 'required|exists:items,id', // Validate each item ID in the array

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

        // dd($request->all());

        // Loop through the arrays of item data and create individual sale records
        try {
            foreach ($request->item_id as $key => $item_id) {

                // store quantity in a temporary variable
                $temp_quantity = $request->quantity[$key];
                
                while($temp_quantity > 0){
                    // dd($total_price[$key]);
                    // fetch all unxpired stocks of this item_id where the quantity is greater than 0, order them by the expire date ascendingly
                    $stocks = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('selling_price',$request->total_price[$key])->where('item_id', $request->item_id[$key])->where('expire_date', '>', now())->where('remain_Quantity', '>', 0)->orderBy('expire_date', 'asc')->first();
                    // dd($stocks);

                    // check if temporary quantity is greater than the stock quantity
                    if($temp_quantity > $stocks->remain_Quantity){
                        // update the temporary quantity to the remaining quantity
                        $temp_quantity = $temp_quantity - $stocks->remain_Quantity;

                        // make sales
                        $thisSale = Sales::create([
                            'pharmacy_id' => $pharmacyId,
                            'staff_id' => $staffId,
                            'item_id' => $item_id,
                            'quantity' => $stocks->remain_Quantity,
                            'total_price' => $request->total_price[$key],
                            'amount' => $request->total_price[$key]*$stocks->remain_Quantity,
                            'date' => $request->date[$key],
                            'stock_id' => $stocks->id,
                        ]);

                        if($thisSale){
                            // update the stock quantity to 0
                            $stocks->update(['remain_Quantity' => 0]);
                        }
                    }else{
                        
                        // make sales
                        $thisSale =  Sales::create([
                            'pharmacy_id' => $pharmacyId,
                            'staff_id' => $staffId,
                            'item_id' => $item_id,
                            'quantity' => $temp_quantity,
                            'total_price' => $request->total_price[$key],
                            'amount' => $request->total_price[$key]*$temp_quantity,
                            'date' => $request->date[$key],
                            'stock_id' => $stocks->id,
                        ]);

                        if($thisSale){
                            // update the stock quantity to the remaining quantity
                            $stocks->update(['remain_Quantity' => $stocks->remain_Quantity - $temp_quantity]);
                            // update the temporary quantity to 0
                            $temp_quantity = 0;
                        }
                    }

                //update remaning stock
                // $stock = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('id', $item_id)->first();
                // $remainQuantity = $stock->remain_Quantity - $request->quantity[$key];
                // $stock->update(['remain_Quantity' => $remainQuantity]);

                // Sales::create([
                //     'pharmacy_id' => $pharmacyId,         // Use the pharmacy_id from session
                //     'staff_id' => $staffId,                // Use the staff_id from the authenticated user
                //     'item_id' => $stock->item_id,
                //     'quantity' => $request->quantity[$key],
                //     'stock_id' => $request->stock_id[$key],
                //     'total_price' => $request->amount[$key],
                //     'date' => $request->date[$key],
                // ]);
            }
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
