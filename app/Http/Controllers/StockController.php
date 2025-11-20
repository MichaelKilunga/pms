<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Pharmacy;
use App\Models\Items;
use App\Models\Medicine;
use App\Models\MedicineStore;
use App\Models\Staff;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Sales;

class StockController extends Controller
{

    public function index(Request $request)
    {
        // $medicines = Items::where('pharmacy_id', session('current_pharmacy_id'))->get();
        if ($request->ajax()) {
            $stocks = Stock::with('item', 'staff') // Load relationships
                ->where('pharmacy_id', session('current_pharmacy_id'))
                // if not remain
                ->orderBy('created_at', 'desc')
                ->orderBy('batch_number', 'desc');

            return DataTables::of($stocks)
                ->addIndexColumn() // Adds auto-incrementing column
                ->editColumn('medicine_name', function ($stock) {
                    return \Illuminate\Support\Str::words($stock->item->name, 3, '...');
                })
                ->editColumn('status', function ($stock) {
                    if ($stock->expire_date < now()) {
                        return '<span class="text-danger">Expired</span>';
                    }
                    if ($stock->remain_Quantity < 1) {
                        return '<span class="text-danger">Out of Stock</span>';
                    }
                    if ($stock->low_stock_percentage > $stock->remain_Quantity) {
                        return '<span class="text-danger">Low stock threshold</span>';
                    }
                    return '<span class="text-success"><i class="bi bi-check fs-3"></i>Fine</span>';
                })
                ->addColumn('actions', function ($stock) {
                    return '
                        <div class="d-flex">
                            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#viewStockModal' . $stock->id . '">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-success btn-sm ms-1" data-bs-toggle="modal"
                                data-bs-target="#editStockModal' . $stock->id . '">
                                <i class="bi bi-pencil"></i>
                            </a>
                            ' . ($stock->remain_Quantity != $stock->quantity
                        ? '<button type="button" class="btn btn-danger btn-sm ms-1" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>'
                        : '<form action="' . route("stock.destroy", $stock->id) . '" method="POST" style="display:inline;">
                                    ' . csrf_field() . '
                                    ' . method_field("DELETE") . '
                                    <button type="submit" onclick="return confirm(\'Do you want to delete this stock?\')" 
                                        class="btn btn-danger btn-sm ms-1">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>'
                    ) . '
                            ' . ($stock->selling_price < $stock->buying_price ?
                        '<button class="btn btn-warning btn-sm ms-1" data-bs-toggle="modal"
                                    data-bs-target="#editBuyingSellingPrice' . $stock->id . '">
                                    <i class="bi bi-recycle"></i>
                                </button>' : '') . '
                        </div>
    
                        <!-- View Stock Modal -->
                      <!-- View Stock Modal -->
                        <div class="modal fade" id="viewStockModal' . $stock->id . '" tabindex="-1"
                            aria-labelledby="viewStockModalLabel' . $stock->id . '" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content shadow-lg rounded-3">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-box-seam me-2"></i> Stock Details
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="p-2 border rounded bg-light">
                                                        <strong>Stock Name:</strong>
                                                        <div>' . ($stock->item->name ?? 'N/A') . '</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-2 border rounded bg-light">
                                                        <strong>Batch Number:</strong>
                                                        <div>' . $stock->batch_number . '</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-2 border rounded bg-light">
                                                        <strong>Supplier:</strong>
                                                        <div>' . $stock->supplier . '</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-2 border rounded bg-light">
                                                        <strong>Buying Price:</strong>
                                                        <div>' . number_format($stock->buying_price, 2) . '</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-2 border rounded bg-light">
                                                        <strong>Selling Price:</strong>
                                                        <div>' . number_format($stock->selling_price, 2) . '</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-2 border rounded bg-light">
                                                        <strong>Stocked Quantity:</strong>
                                                        <div>' . $stock->quantity . '</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-2 border rounded bg-light">
                                                        <strong>Remain Quantity:</strong>
                                                        <div>' . $stock->remain_Quantity . '</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-2 border rounded bg-light">
                                                        <strong>Low Stock (%):</strong>
                                                        <div>' . $stock->low_stock_percentage . '</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-2 border rounded bg-light">
                                                        <strong>In Date:</strong>
                                                        <div>' . $stock->in_date . '</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="p-2 border rounded bg-light">
                                                        <strong>Expire Date:</strong>
                                                        <div>' . $stock->expire_date . '</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle me-1"></i> Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Edit full Stock Modal -->
                        <div class="modal fade modal-lg" id="editStockModal' . $stock->id . '" tabindex="-1"
                            aria-labelledby="editStockModalLabel' . $stock->id . '" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Edit Stock</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="' . route('stock.update', $stock->id) . '" method="POST">
                                            ' . csrf_field() . '
                                            ' . method_field('PUT') . '
                                            <input type="hidden" name="id" value="' . $stock->id . '">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="item" class="form-label">Stock Name</label>
                                                        <input type="text" class="form-control" name="item_name"
                                                            value="' . ($stock->item->name ?? '') . '" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="batch_number" class="form-label">Batch Number</label>
                                                        <input type="text" class="form-control" name="batch_number"
                                                            value="' . $stock->batch_number . '" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label for="supplier" class="form-label">Supplier Name</label>
                                                        <input type="text" class="form-control" name="supplier"
                                                            value="' . $stock->supplier . '" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="buying_price" class="form-label">Buying Price</label>
                                                        <input type="number" class="form-control" name="buying_price"
                                                            value="' . $stock->buying_price . '" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="selling_price" class="form-label">Selling Price</label>
                                                        <input type="number" class="form-control" name="selling_price"
                                                            value="' . $stock->selling_price . '" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="quantity" class="form-label">Stocked Quantity</label>
                                                        <input type="number" class="form-control" name="quantity"
                                                            value="' . $stock->quantity . '" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="remain_Quantity" class="form-label">Remain Quantity</label>
                                                        <input type="number" class="form-control" name="remain_Quantity"
                                                            value="' . $stock->remain_Quantity . '" readonly required>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="low_stock_percentage" class="form-label">Low Stock</label>
                                                        <input type="number" class="form-control" name="low_stock_percentage"
                                                            value="' . $stock->low_stock_percentage . '" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="expire_date" class="form-label">Expire Date</label>
                                                        <input type="text" class="form-control" name="expire_date"
                                                            value="' . $stock->expire_date . '" required>
                                                    </div>
                                                </div>

                                            <div class="col-md-6">
                                                    <div class="mb-3 hidden">
                                                        <label for="in_date" class="form-label">In Date</label>
                                                        <input type="text" class="form-control" name="in_date"
                                                            value="' . $stock->in_date . '" readonly required>
                                                    </div>
                                                </div>
                                                
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <button type="submit" class="btn btn-success">Update Stock</button>
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Buying/Selling Price Stock Modal -->
                        <div class="modal fade modal-lg" id="editBuyingSellingPrice' . $stock->id . '" tabindex="-1"
                            aria-labelledby="editBuyingSellingPriceLabel' . $stock->id . '" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="editBuyingSellingPriceLabel' . $stock->id . '">Edit Seling Vs  Buyinig Price of ' . $stock->item->name . '</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="' . route('stock.updateSBP', $stock->id) . '" method="POST">
                                            ' . csrf_field() . '
                                            ' . method_field('PUT') . '
                                            <input type="hidden" name="id" value="' . $stock->id . '">                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="buying_price" class="form-label">Buying Price</label>
                                                    <input type="number" class="form-control" name="buying_price"
                                                        value="' . $stock->buying_price . '" required>
                                                </div>
                                                </div>
                                                <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="selling_price" class="form-label">Selling Price</label>
                                                    <input type="number" class="form-control" name="selling_price"
                                                        value="' . $stock->selling_price . '" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="quantity" class="form-label">Stocked Quantity</label>
                                                        <input type="number" readonly class="form-control" name="quantity"
                                                            value="' . $stock->quantity . '" required>
                                                    </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="remain_Quantity" class="form-label">Remain Quantity</label>
                                                        <input type="number" readonly class="form-control" name="remain_Quantity"
                                                            value="' . $stock->remain_Quantity . '" readonly required>
                                                    </div>
                                            </div>                                            
                                            <div class="d-flex align-items-center justify-content-between">
                                            <button type="submit" class="btn btn-success">Update Stock</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
                })
                ->rawColumns(['status', 'actions']) // Allow HTML rendering
                ->filterColumn('medicine_name', function ($query, $keyword) {
                    $query->whereHas('item', function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%{$keyword}%");
                    });
                })
                ->make(true);
        }

        return view('stock.index');
    }


    /**
     * Show the form for creating a new stock entry.
     */
    public function create()
    {
        $pharmacies = Pharmacy::where('owner_id', auth::id())->get();
        $items = Items::all();
        $staff = Staff::whereIn('pharmacy_id', $pharmacies->pluck('id'))->get();

        return view('stock.create', compact('pharmacies', 'items', 'staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|array',
            'item_id.*' => 'required|exists:items,id',

            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',

            // 'remain_Quantity' => 'required|array',
            // 'remain_Quantity.*' => 'required|integer|min:1',

            'low_stock_percentage' => 'required|array',
            'low_stock_percentage.*' => 'required|integer|min:1',

            'buying_price' => 'required|array',
            'buying_price.*' => 'required|numeric',

            'selling_price' => 'required|array',
            'selling_price.*' => 'required|numeric',

            'in_date' => 'required|array',
            'in_date.*' => 'required|date',

            'expire_date' => 'required|array',
            'expire_date.*' => 'required|date',

            'batch_number' => 'required|integer',

            'supplier' => 'required|string|max:255',
        ]);

        // dd($request);

        $pharmacy_id = session('current_pharmacy_id');
        $staff_id = Auth::user()->id;

        foreach ($request->item_id as $index => $item_id) {
            Stock::create([
                'pharmacy_id' => $pharmacy_id,
                'staff_id' => $staff_id,
                'item_id' => $item_id,
                'quantity' => $request->quantity[$index],
                'buying_price' => $request->buying_price[$index],
                'selling_price' => $request->selling_price[$index],
                'remain_Quantity' =>  $request->quantity[$index],
                'low_stock_percentage' => $request->low_stock_percentage[$index],
                'in_date' => $request->in_date[$index],
                'batch_number' => $request->batch_number,
                'supplier' => $request->supplier,
                'expire_date' => $request->expire_date[$index],
            ]);
        }

        return redirect()->route('stock')->with('success', 'Stock added successfully!');
    }

    // add stock and medicines at a time
    public function MS_store(Request $request)
    {
        try {
            $request->validate([
                'item_name' => 'required|array',
                // 'item_name.*' => 'required|string|unique:items,name',

                'quantity' => 'required|array',
                'quantity.*' => 'required|integer|min:1',

                // 'remain_Quantity' => 'required|array',
                // 'remain_Quantity.*' => 'required|integer|min:1',

                'low_stock_percentage' => 'required|array',
                'low_stock_percentage.*' => 'required|integer|min:1',

                'buying_price' => 'required|array',
                'buying_price.*' => 'required|numeric',

                'selling_price' => 'required|array',
                'selling_price.*' => 'required|numeric',

                'in_date' => 'required|array',
                'in_date.*' => 'required|date',

                'expire_date' => 'required|array',
                'expire_date.*' => 'required|date',

                'batch_number' => 'required|integer',

                'supplier' => 'required|string|max:255',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', "Data  not added because: " . $e->getMessage());
        }


        $pharmacy_id = session('current_pharmacy_id');
        $staff_id = Auth::user()->id;

        //a loop to add the medicine name to the items table and stock to the stock table
        foreach ($request->item_name as $index => $item_name) {
            $item = Items::create([
                'pharmacy_id' => $pharmacy_id,
                'category_id' => 1,
                'name' => $item_name,
            ]);

            Stock::create([
                'pharmacy_id' => $pharmacy_id,
                'staff_id' => $staff_id,
                'item_id' => $item->id,
                'quantity' => $request->quantity[$index],
                'buying_price' => $request->buying_price[$index],
                'selling_price' => $request->selling_price[$index],
                'remain_Quantity' =>  $request->quantity[$index],
                'low_stock_percentage' => $request->low_stock_percentage[$index],
                'in_date' => $request->in_date[$index],
                'batch_number' => $request->batch_number,
                'supplier' => $request->supplier,
                'expire_date' => $request->expire_date[$index],
            ]);
        }

        return redirect()->route('stock')->with('success', 'Stock & Medicine were added successfully!');
    }


    /**
     * Display the specified stock entry.
     */
    public function show(Stock $stock)
    {
        return view('stock.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified stock entry.
     */
    public function edit(Stock $stock)
    {
        return view('stock.edit', compact('stock'));
    }

    /**
     * Update the specified stock entry in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        try {
            $message = null;
            $stock = Stock::find($request->id);

            // start transaction
            DB::beginTransaction();

            $request->validate([
                'id' => 'required|integer|exists:stocks,id',
                'quantity' => 'required|integer|min:1',
                'remain_Quantity' => 'required|integer',
                'low_stock_percentage' => 'required|integer|min:1',
                'buying_price' => 'required|numeric',
                'selling_price' => 'required|numeric',
                'supplier' => 'required|string|max:255',
                'in_date' => 'required|date',
                'expire_date' => 'required|date',
            ]);
            //if original quantity is greater than remain_Quantity

            if ($stock->quantity > $stock->remain_Quantity) {

                //if here, it means has already started to make sales on this stock, so we can't  modify buying price and selling price
                //what to do then? modify only the quantity=remain_Quantity and remain_Quantity=0; as a way to close this stock and create a new one
                // with a new batch number, selling price and buying price;

                //check if he wants to modify prices
                if ((($request->selling_price != $stock->selling_price) || ($request->buying_price != $stock->buying_price)) && ($request->quantity != $stock->quantity)) {

                    //check if new stocked quantity is greater than sold quanity
                    if (!($request->quantity >= ($stock->quantity - $stock->remain_Quantity))) {
                        throw new Exception('New quantity is less than the sold quantity.');
                    }

                    //close this stock
                    $stock->update([
                        'quantity' => $stock->quantity - $stock->remain_Quantity,
                        'remain_Quantity' => 0,
                    ]);


                    // create a new similar stock with new prices and quantity
                    if (($request->quantity - ($stock->quantity - $stock->remain_Quantity)) > 0) {
                        $newStock = Stock::create([
                            'pharmacy_id' => session('current_pharmacy_id'),
                            'quantity' => $request->quantity - ($stock->quantity - $stock->remain_Quantity),
                            'remain_Quantity' => $request->quantity - ($stock->quantity - $stock->remain_Quantity),
                            'batch_number' => $stock->batch_number,
                            'low_stock_percentage' => round(($request->quantity - ($stock->quantity - $stock->remain_Quantity)) * 0.25, 0),
                            'buying_price' => $request->buying_price,
                            'selling_price' => $request->selling_price,
                            'supplier' => $request->supplier,
                            'in_date' => $request->in_date,
                            'expire_date' => $request->expire_date,
                            'staff_id' => Auth::user()->id,
                            'item_id' => $stock->item_id,
                        ]);
                    }
                    $message = 'Price and quantity updated successfully.';
                } elseif ((($request->selling_price != $stock->selling_price) || ($request->buying_price != $stock->buying_price)) && !($request->quantity != $stock->quantity)) {

                    $newStock_quantity = $stock->remain_Quantity;

                    //close this stock
                    $stock->update([
                        'quantity' => $stock->quantity - $stock->remain_Quantity,
                        'remain_Quantity' => 0,
                    ]);

                    // create a new similar stock with new prices and quantity
                    $newStock = Stock::create([
                        'pharmacy_id' => session('current_pharmacy_id'),
                        'quantity' => $newStock_quantity,
                        'remain_Quantity' => $newStock_quantity,
                        'batch_number' => $stock->batch_number,
                        'low_stock_percentage' => round($newStock_quantity * 0.25, 0),
                        'buying_price' => $request->buying_price,
                        'selling_price' => $request->selling_price,
                        'supplier' => $request->supplier,
                        'in_date' => $request->in_date,
                        'staff_id' => Auth::user()->id,
                        'expire_date' => $request->expire_date,
                        'item_id' => $stock->item_id,
                    ]);
                    $message = 'Price updated successfully.';
                } elseif (!(($request->selling_price != $stock->selling_price) || ($request->buying_price != $stock->buying_price)) && ($request->quantity != $stock->quantity)) {
                    //check if new stocked quantity is greater than sold quanity
                    if ($request->quantity >= ($stock->quantity - $stock->remain_Quantity)) {
                        $stock->update([
                            'quantity' => $request->quantity,
                            'remain_Quantity' => $request->quantity - ($stock->quantity - $stock->remain_Quantity),
                        ]);
                    } else {
                        throw new Exception('New quantity is less than the sold quantity.');
                    }
                    $message = 'Quantity updated successfully.';
                } elseif (!(($request->selling_price != $stock->selling_price) || ($request->buying_price != $stock->buying_price)) && !($request->quantity != $stock->quantity) && ($request->expire_date != $stock->expire_date)) {
                    $stock->update([
                        'expire_date' => $request->expire_date,
                    ]);
                    $message = 'Expire date updated successfully.';
                }

                DB::commit();
                return redirect()->route('stock')->with('success', $message);
            } else {
                $request['remain_Quantity'] = $request['quantity'];

                $stock = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('id', $request->id)->first();
                $stock->update($request->only('quantity', 'low_stock_percentage', 'remain_Quantity', 'buying_price', 'selling_price', 'supplier', 'in_date', 'expire_date'));

                DB::commit();
                return redirect()->route('stock')->with('success', 'Stock updated successfully.');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', "Data  not added because: " . $e->getMessage());
        }
    }

    /**
     * Remove the specified stock entry from storage.
     */
    public function destroy(Request $request)
    {
        $stock = Stock::destroy($request->id);

        return redirect()->route('stock')->with('success', 'Stock deleted successfully.');
    }

    /**
     * Import stock from a CSV file.
     */
    public function import(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'file' => 'required|file|mimes:csv,txt',
                'batch_number__' => 'required|integer',
                'in_date' => 'required|date',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error processing the file: ' . $e->getMessage());
        }

        try {
            $file = $request->file('file');
            $data = array_map('str_getcsv', file($file));

            // Extract the header and rows
            $header = array_map('strtolower', $data[0]);
            unset($data[0]);
            $rows = $data;

            $pharmacy_id = session('current_pharmacy_id');
            $staff_id = Auth::user()->id;

            foreach ($rows as $row) {
                $row = array_combine($header, $row);

                // Validate each row
                try {
                    $validator = Validator::make($row, [
                        'item_name' => 'required|string|max:255',
                        'buying_price' => 'required|numeric|min:0',
                        'selling_price' => 'required|numeric|min:0',
                        'quantity' => 'required|integer|min:1',
                        'low_stock_percentage' => 'required|integer|min:1',
                        'expire_date' => 'required|date',
                        'supplier' => 'required|string|max:255',
                    ]);
                } catch (Exception $e) {
                    throw new Exception('Validate File input\'s error: ' . $e->getMessage());
                }

                // Check if the item exists
                try {
                    $item = Items::firstOrCreate(
                        ['name' => $row['item_name'], 'pharmacy_id' => $pharmacy_id],
                        ['category_id' => 1]
                    );
                } catch (Exception $e) {
                    throw new Exception('Create Medicine Error: ' . $e->getMessage());
                }

                // Add stock
                try {
                    Stock::create([
                        'pharmacy_id' => $pharmacy_id,
                        'staff_id' => $staff_id,
                        'item_id' => $item->id,
                        'quantity' => $row['quantity'],
                        'remain_Quantity' => $row['quantity'],
                        'low_stock_percentage' => $row['low_stock_percentage'],
                        'buying_price' => $row['buying_price'],
                        'selling_price' => $row['selling_price'],
                        'expire_date' => $row['expire_date'],
                        'supplier' => $row['supplier'],
                        'in_date' => $request->in_date,
                        'batch_number' => $request->batch_number__,
                    ]);
                } catch (Exception $e) {
                    throw new Exception('Create Stock Error: ' . $e->getMessage());
                }
            }

            return redirect()->route('stock')->with('success', 'Medicines and stock imported successfully!');
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Error processing the file: ' . $e->getMessage());
        }
    }


    public function stockBalancesPage()
    {
        return view('stock.balance');
    }

    public function viewStockBalances()
    {
        $pharmacyId = session('current_pharmacy_id');

        $stockBalances = Stock::select(
            'stocks.item_id',
            DB::raw('SUM(stocks.quantity) as quantity'),
            DB::raw("SUM(CASE WHEN stocks.expire_date > NOW() THEN stocks.remain_quantity ELSE 0 END) as remain_quantity"),
            DB::raw("SUM(CASE WHEN stocks.expire_date <= NOW() THEN stocks.remain_quantity ELSE 0 END) as expired_remain_quantity")
        )
            ->where('stocks.pharmacy_id', $pharmacyId)  // <-- prefix table
            ->groupBy('stocks.item_id')
            ->with('item','latestStockCheck');
        
            // dd($stockBalances->get());

        return DataTables::eloquent($stockBalances)
            ->addIndexColumn()
            ->addColumn('medicine_name', fn($row) => $row->item->name ?? 'Unknown')
            ->addColumn('status', function ($row) {
                if ($row->remain_quantity > 0) {
                    return '<span class="text-success fw-bold">Available</span>';
                } elseif ($row->expired_remain_quantity > 0) {
                    return '<span class="text-danger fw-bold">Expired</span>';
                } else {
                    return '<span class="text-warning fw-bold">Finished</span>';
                }
            })
            // customize stock-check status
            /*
                render: function(data, type, row) {
                            return `
                                <div class="d-flex">
                                    <div class="row">
                                        <div class="col-6 mb-1">
                                            Status:<strong class="">${row.latestStockCheck?.updated_at}</strong>
                                        </div>
                                        <div class="col-6 mb-1">
                                            Check date:<strong class="text-secondary">05-10-2025</strong>
                                        </div>
                                    </div>
                                    <div  class="row">
                                        <div  class="col-md-6">
                                            <input type="number" class="form-control form-control-sm me-2 physical-qty" 
                                                style="width:100px;" 
                                                min="0" 
                                                placeholder="Qty">
                                        </div>
                                        <div  class="col-md-6">
                                            <button class="btn btn-success btn-sm save-stock-check" 
                                                data-item-id="${row.item.id}" 
                                                data-stock-id="${row.id}">
                                                Save
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            `;
                        }
            */
            ->addColumn('stock_check_status', function ($row) {
                // if has last stock check and the stock remain quantity is not consistency with the physical quantity, report status here
                return '<div class="d-flex gap-2 justify-content-between">
                                    <div class="d-flex justify-content-start">
                                        <div class="col-4 mb-1 mx-1">
                                            <small class="badge '. ($row->latestStockCheck ? ($row->latestStockCheck->physical_quantity == $row->remain_quantity ? 'bg-success' : 'bg-danger') : "bg-warning").' text-smaller">'. ($row->latestStockCheck ? ($row->latestStockCheck->physical_quantity == $row->remain_quantity ? 'Fine ' : (($row->latestStockCheck->physical_quantity > $row->remain_quantity) ? ($row->latestStockCheck->physical_quantity . ' Over ') : ($row->latestStockCheck->physical_quantity . ' Under '))) : "Unchecked").'</small>
                                        </div>                                    
                                        <div class="col-8 mb-1'. ($row->latestStockCheck ? "": "d-none").'">
                                            <small class="badge text-secondary text-smallest">'. ($row->latestStockCheck ? Carbon::parse($row->latestStockCheck->checked_at)->format('d M Y H:i') : '') .'</small>
                                        </div>
                                    </div>
                                    <div  class="d-flex justify-content-end">
                                        <div  class="col-md-6">
                                            <input type="number" class="form-control form-control-sm me-2 physical-qty" 
                                                style="width:90%;" 
                                                min="0" 
                                                placeholder="Qty">
                                        </div>
                                        <div  class="col-md-6">
                                            <button class="btn btn-success btn-sm save-stock-check" 
                                                data-item-id="'.$row->item_id.'" 
                                                data-stock-id="'.$row->id.'">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>';
            })
            ->addColumn('action', function ($row) {
                return '<button type="button" class="btn btn-primary btn-sm view-stock-btn" 
                        data-bs-toggle="modal" 
                        data-bs-target="#stockModal" 
                        data-item-id="' . $row->item_id . '">
                        <i class="bi bi-eye"></i></button>';
            })
            ->rawColumns(['status', 'action','stock_check_status']) // Allow HTML rendering
            ->make(true);
    }


    public function getStockDetails(Request $request)
    {
        try {
            $request->validate(['item_id' => 'required|integer']);

            $pharmacyId = session('current_pharmacy_id');
            $itemId = $request->query('item_id');

            $stocks = Stock::where('item_id', $itemId)
                ->where('pharmacy_id', $pharmacyId)
                ->orderBy('expire_date', 'asc')
                ->get();

            $now = Carbon::now();

            // Fine stocks
            $fine = $stocks->filter(function ($s) use ($now) {
                return  $s->remain_Quantity > $s->low_stock_percentage && $s->remain_Quantity > 0 && $s->expire_date && Carbon::parse($s->expire_date)->gt($now);
            })->map(function ($s) {
                return [
                    'batch_no' => $s->batch_number,
                    'qty' => (int) $s->remain_Quantity,
                    'supplier' => $s->supplier,
                    'expiry_date' => $s->expire_date ? Carbon::parse($s->expire_date)->format('d-m-Y') : null,
                    'stocked_on' => $s->in_date ? Carbon::parse($s->in_date)->format('d-m-Y') : null,
                ];
            })->values();

            // Expired stocks
            $expired = $stocks->filter(function ($s) use ($now) {
                return $s->expire_date && Carbon::parse($s->expire_date)->lte($now);
            })->map(function ($s) {
                return [
                    'batch_no' => $s->batch_number,
                    'qty' => (int) $s->remain_Quantity,
                    'supplier' => $s->supplier,
                    'expiry_date' => $s->expire_date ? Carbon::parse($s->expire_date)->format('d-m-Y') : null,
                    'stocked_on' => $s->in_date ? Carbon::parse($s->in_date)->format('d-m-Y') : null,
                ];
            })->values();

            //low stocks
            $lowStock = $stocks->filter(function ($s) use ($now) {
                return $s->remain_Quantity > 0 && $s->remain_Quantity <= $s->low_stock_percentage && $s->expire_date && Carbon::parse($s->expire_date)->gt($now);
            })->map(function ($s) {
                return [
                    'batch_no' => $s->batch_number,
                    'qty' => (int) $s->remain_Quantity,
                    'supplier' => $s->supplier,
                    'expiry_date' => $s->expire_date ? Carbon::parse($s->expire_date)->format('d-m-Y') : null,
                    'stocked_on' => $s->in_date ? Carbon::parse($s->in_date)->format('d-m-Y') : null,
                ];
            })->values();

            //finished stocks
            $finished = $stocks->filter(function ($s) {
                return $s->remain_Quantity == 0;
            })->map(function ($s) {
                return [
                    'batch_no' => $s->batch_number,
                    'qty' => (int) $s->remain_Quantity,
                    'supplier' => $s->supplier,
                    'expiry_date' => $s->expire_date ? Carbon::parse($s->expire_date)->format('d-m-Y') : null,
                    'stocked_on' => $s->in_date ? Carbon::parse($s->in_date)->format('d-m-Y') : null,
                ];
            })->values();

            $item = Items::find($itemId);

            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $itemId,
                    'name' => $item ? $item->name : 'Unknown'
                ],
                'fine' => $fine,
                'expired' => $expired,
                'lowStock' => $lowStock,
                'finished' => $finished
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateSBP(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'id' => 'required|exists:stocks,id',
                'buying_price' => 'required|numeric',
                'selling_price' => 'required|numeric'
            ]);

            $stock = Stock::find($request->id);

            $stock->buying_price = $request->buying_price;
            $stock->selling_price = $request->selling_price;
            $stock->save();

            // pull all sales under this stock and modify their selling price to the new selling price
            $sales = Sales::where('stock_id', $stock->id)->get();
            foreach ($sales as $sale) {
                // unit price == total_price here
                $sale->total_price = $request->selling_price;
                $sale->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Stock updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function saveStockCheck(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'physical_quantity' => 'required|numeric|min:0',
        ]);

        //    find records with item id if exists delete
        DB::table('stock_checks')->where('item_id', $request->item_id)->delete();

        // Example: record the check in a stock_checks table or update existing record
        DB::table('stock_checks')->updateOrInsert(
            ['item_id' => $request->item_id],
            [
                'physical_quantity' => $request->physical_quantity,
                'checked_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'user_id' => Auth::id(),
                'pharmacy_id' => session('current_pharmacy_id'),
            ]
        );

        return response()->json(['success' => true]);
        // return redirect()->back()->with('success', 'Stock check saved successfully.');

    }
}
