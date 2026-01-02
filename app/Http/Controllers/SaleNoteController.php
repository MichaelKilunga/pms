<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\SaleNote;
use App\Models\Sales;
use App\Models\Stock;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ValidatedInput;
use Illuminate\Validation\ValidationData;
use Livewire\Attributes\Validate;
use Vonage\Client\Exception\Validation;

class SaleNoteController extends Controller
{

    /* Schema::create('sale_notes', function (Blueprint $table) {
        $table->id();
        //name
        $table->string('name');
        $table->string('quantity');
        $table->string('unit_price');
        //status
        $table->enum('status', ['promoted', 'Unpromoted','rejected'])->default('Unpromoted');
        //description
        $table->string('description')->nullable();
        //foreign key for pharmacy id
        $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
        //foreign key for staff id
        $table->foreignId('staff_id')->constrained('users')->onDelete('cascade'); // User making the sale
        $table->timestamps();
    });
    */

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //return a  collection of sale notes
        $notes = SaleNote::where('pharmacy_id', session('current_pharmacy_id'))->with('staff');

        // sum of the product of quantity and unit_price for sales made today
        $totalSalesMadeToday = $notes;

        if ($request->filter == true) {
            $notes = SaleNote::where('pharmacy_id', session('current_pharmacy_id'))->whereDate('created_at', Carbon::today())->with('staff');
            $totalSalesMadeToday = $notes->whereDate('created_at', Carbon::today());
        }

        $saleNotes = null;
        if (Auth::user()->hasRole('Staff')) {
            $saleNotes = $notes->where('staff_id', Auth::user()->id)->where('status', 'Unpromoted')->get();
            $totalSalesMadeToday = $notes->where('staff_id', Auth::user()->id)->where('status', 'Unpromoted')->sum(DB::raw('quantity * unit_price'));
        } else {
            $saleNotes = $notes->where('status', 'Unpromoted')->get();
            $totalSalesMadeToday = $notes->where('status', 'Unpromoted')->sum(DB::raw('quantity * unit_price'));
        }

        $salesNotes = $saleNotes;
        return view('sales_notes.index', compact('salesNotes', 'totalSalesMadeToday'));
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
    public function storeSalesNotes(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'quantity' => 'required|numeric|min:1',
                'unit_price' => 'required|numeric|min:1',
                'description' => 'nullable|string',
                'pharmacy_id' => 'required|exists:pharmacies,id',
                'staff_id' => 'required|exists:users,id',
            ]);

            $saleNote = new SaleNote();
            $saleNote->name = $request->name;
            $saleNote->quantity = $request->quantity;
            $saleNote->unit_price = $request->unit_price;
            $saleNote->description = $request->description;
            $saleNote->pharmacy_id = $request->pharmacy_id;
            $saleNote->staff_id = $request->staff_id;
            $saleNote->save();

            return redirect()->route('salesNotes')
                ->with('success', 'Sale Note created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SaleNote $saleNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaleNote $saleNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'quantity' => 'required|numeric|min:1',
                'unit_price' => 'required|numeric|min:1',
                'description' => 'nullable|string',
            ]);

            $saleNote = SaleNote::where('id', $request->id)->first();
            // dd($saleNote->all());
            // dd($request->all());

            $saleNote->update($request->only('name', 'quantity', 'unit_price', 'description'));

            return redirect()->route('salesNotes')->with('success', 'Sale Note updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroySalesNotes($saleNoteId)
    {
        try {
            // $saleNote->delete();
            // dd($saleNoteId);
            SaleNote::destroy($saleNoteId);
            return redirect()->back()->with('success', 'Sale Note deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // function to promote a sale note
    /* Data comes from this form here
        <div class="form-group mb-3 row">
            <div class="col-md-4 form-floating">
                <input class="form-control batch_number" type="text" id="batch_number" name="batch_number"
                    placeholder="Batch Number" required readonly>
                <label class="form-label" for="batch_number">Batch Number</label>
            </div>
            <div class="col-md-4 form-floating">
                <input class="form-control" type="text" name="supplier_name"
                    placeholder="Supplier Name" required>
                <label class="form-label" for="supplier_name">Supplier Name</label>
            </div>
            <div class="col-md-4 form-floating">
                <input class="form-control" type="date" value="{{ date('Y-m-d') }}"
                    name="date" placeholder="Entry Date" required>
                <label class="form-label" for="date">Entry Date</label>
            </div>
            <div hidden class="hidden form-floating">
                <input readonly type="text" required name="sale_note_id[]" value="${saleNoteId}">
            </div>
            <div class="col-md-2 form-floating">
                <input class="form-control" type="text" required name="name[]"
                    placeholder="Name" value="${rowName}">
                <label class="form-label" for="name">Name</label>
            </div>
            <div class="col-md-2 form-floating">
                <input class="form-control" type="number" required name="buying_price[]"
                    placeholder="Buying Price">
                <label class="form-label" for="buying_price">Buying Price</label>
            </div>
            <div class="col-md-1 form-floating">
                <input class="form-control" type="number" required name="selling_price[]"
                    placeholder="Selling Price" readonly value="${rowUnitPrice}">
                <label class="form-label" for="selling_price">Unit Price</label>
            </div>
            <div class="col-md-2 form-floating">
                <input class="form-control" type="number" min="1" required name="stocked_quantity[]"
                    placeholder="Stocked Quantity">
                <label class="form-label" for="stocked_quantity">Quantity</label>
            </div>
            <div class="col-md-2 form-floating">
                <input class="form-control" type="number" required name="low_stock_quantity[]" placeholder="Low Stock Quantity">
                <label class="form-label" for="low_stock_quantity">Low Stock</label>
            </div>
            <div class="col-md-2 form-floating">
                <input class="form-control" type="date"
                    required name="expiry_date[]" placeholder="Expiry Date">
                <label class="form-label" for="expiry_date">Expire Date</label>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm removeRow"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
                        */
    public function promoteSalesNotes(Request $request)
    {
        try {
            $request->validate([
                'batch_number' => 'required|numeric',
                'supplier_name' => 'required|string',
                'date' => 'required|date',

                'sale_note_id' => 'required|array',
                'sale_note_id.*' => 'required|numeric',

                'name' => 'required|array',
                'name.*' => 'required|string',

                'buying_price' => 'required|array',
                'buying_price.*' => 'required|numeric',

                'selling_price' => 'required|array',
                'selling_price.*' => 'required|numeric',

                'stocked_quantity' => 'required|array',
                'stocked_quantity.*' => 'required|numeric',

                'low_stock_quantity' => 'required|array',
                'low_stock_quantity.*' => 'required|numeric',

                'expiry_date' => 'required|array',
                'expiry_date.*' => 'required|date',

            ]);

            // for each sales note
            foreach ($request->sale_note_id as $key => $saleNoteId) {
                $saleNote = SaleNote::find($saleNoteId);

                // create a new stock for this sale note
                $stockValues = new Request();
                $stockValues['batch_number'] = $request->batch_number;
                $stockValues['supplier_name'] = $request->supplier_name;
                $stockValues['date'] = $request->date;
                $stockValues['name'] = $request->name[$key];
                $stockValues['buying_price'] = $request->buying_price[$key];
                $stockValues['selling_price'] = $request->selling_price[$key];
                $stockValues['stocked_quantity'] = $request->stocked_quantity[$key];
                $stockValues['low_stock_quantity'] = $request->low_stock_quantity[$key];
                $stockValues['expiry_date'] = $request->expiry_date[$key];

                $stock = $this->createStock($stockValues);

                // create a new sale for this sale note
                try {
                    $saleValues = new Request();
                    $saleValues['staff_id'] = $saleNote->staff_id;
                    $saleValues['pharmacy_id'] = $saleNote->pharmacy_id;
                    $saleValues['item_id'] = $stock->item_id;
                    $saleValues['quantity'] = $saleNote->quantity;
                    $saleValues['total_price'] = $saleNote->quantity * $saleNote->unit_price;
                    $saleValues['date'] = $saleNote->created_at;
                    $saleValues['stock_id'] = $stock->id;

                    $sale = $this->storeSales($saleValues);
                } catch (\Exception $e) {
                    // if there is an error, delete the stocks and items created
                    Stock::where('id', $stock->id)->delete();
                    Items::where('id', $stock->item_id)->delete();
                    throw new \Exception($e->getMessage());
                }
                $saleNote->status = 'promoted';
                $saleNote->save();
            }

            return redirect()->back()->with('success', 'Sale notes promoted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // promoting multiple sale notes as one
    public function promoteSalesNotesAsOne(Request $request)
    {
        try {
            // dd($request->all());
            // check if is promoting to existing
            if (isset($request->promoteToExisting)) {
                $request->validate([
                    'batch_number' => 'required|numeric',
                    'supplier_name' => 'required|string',
                    'date' => 'required|date',

                    'sale_note_ids' => 'required|string',

                    'item_id' => 'required|integer|exists:items,id',

                    'buying_price' => 'required|numeric',
                    'selling_price' => 'required|numeric',
                    'stocked_quantity' => 'required|numeric',
                    'low_stock_quantity' => 'required|numeric',
                    'expiry_date' => 'required|date',
                ]);

                // dd($request->all());

                $stockValues = $request;

                // // pass item name
                $stockValues['name'] = Items::where('id', $request->item_id)->first()->name;

                // dd($stockValues->all());

                // create a stock record
                $stock = $this->createStock($stockValues);

                // get the sale note ids
                $saleNoteIds = explode(',', $request->sale_note_ids);
                // create a sales record for each sale note
                $salesValues = new Request();
                $salesValues['item_id'] = $stock->item_id;
                $salesValues['stock_id'] = $stock->id;

                foreach ($saleNoteIds as $saleNoteId) {
                    $saleNote = SaleNote::where('id', $saleNoteId)->first();

                    $salesValues['staff_id'] = $saleNote->staff_id;
                    $salesValues['quantity'] = $saleNote->quantity;
                    $salesValues['total_price'] = $saleNote->unit_price * $saleNote->quantity;
                    $salesValues['date'] = $saleNote->created_at;

                    $sale = $this->storeSales($salesValues);

                    $saleNote->status = 'promoted';
                    $saleNote->save();
                }
                return redirect()->route('salesNotes')->with('success', 'Sale Notes promoted successfully.');
            } else {
                $request->validate([
                    'batch_number' => 'required|numeric',
                    'supplier_name' => 'required|string',
                    'date' => 'required|date',

                    'sale_note_ids' => 'required|string',

                    'name' => 'required|string',
                    'buying_price' => 'required|numeric',
                    'selling_price' => 'required|numeric',
                    'stocked_quantity' => 'required|numeric',
                    'low_stock_quantity' => 'required|numeric',
                    'expiry_date' => 'required|date',
                ]);

                // dd($request->all());

                $stockValues = $request;
                // create a stock record
                $stock = $this->createStock($stockValues);

                // get the sale note ids
                $saleNoteIds = explode(',', $request->sale_note_ids);
                // create a sales record for each sale note
                $salesValues = new Request();
                $salesValues['item_id'] = $stock->item_id;
                $salesValues['stock_id'] = $stock->id;

                foreach ($saleNoteIds as $saleNoteId) {
                    $saleNote = SaleNote::where('id', $saleNoteId)->first();

                    $salesValues['staff_id'] = $saleNote->staff_id;
                    $salesValues['quantity'] = $saleNote->quantity;
                    $salesValues['total_price'] = $saleNote->unit_price * $saleNote->quantity;
                    $salesValues['date'] = $saleNote->created_at;

                    $sale = $this->storeSales($salesValues);

                    $saleNote->status = 'promoted';
                    $saleNote->save();
                }
                return redirect()->route('salesNotes')->with('success', 'Sale Notes promoted successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // function that receives an array   of stock values and to create a new stock
    public function createStock(Request $stockValues)
    {
        try {
            $stockValues->validate([
                'batch_number' => 'required|numeric',
                'supplier_name' => 'required|string',
                'date' => 'required|date',
                'name' => 'required|string',
                'buying_price' => 'required|numeric',
                'selling_price' => 'required|numeric',
                'stocked_quantity' => 'required|numeric',
                'low_stock_quantity' => 'required|numeric',
                'expiry_date' => 'required|date',
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        // dd($stockValues->all());

        $pharmacy_id = session('current_pharmacy_id');
        $staff_id = Auth::user()->id;

        //a loop to add the medicine name to the items table and stock to the stock table
        try {
            // dd($stockValues->all());
            // initialize $item
            $item = null;
            // check if is promoting to existing
            if (isset($stockValues->promoteToExisting)) {
                $item = Items::where('id', $stockValues->item_id)->first();
            } else {
                $item = Items::create([
                    'pharmacy_id' => $pharmacy_id,
                    'category_id' => 1,
                    'name' => $stockValues->name,
                ]);
            }

            // dd($item);

            $stock = Stock::create([
                'pharmacy_id' => $pharmacy_id,
                'staff_id' => $staff_id,
                'item_id' => $item->id,

                'quantity' => $stockValues->stocked_quantity,
                'buying_price' => $stockValues->buying_price,
                'selling_price' => $stockValues->selling_price,
                'remain_Quantity' =>  $stockValues->stocked_quantity,
                'low_stock_percentage' => $stockValues->low_stock_quantity,
                'in_date' => $stockValues->date,
                'expire_date' => $stockValues->expiry_date,

                'batch_number' => $stockValues->batch_number,
                'supplier' => $stockValues->supplier_name,
            ]);

            return  $stock;
        } catch (\Exception $e) {
            // delete the item record if validation fails
            $item->delete();
            throw new \Exception($e->getMessage());
        }
    }

    // function that receives Request $request of sales values then store them in the database
    public function storeSales(Request $request)
    {
        try {
            $request->validate([
                'item_id' => 'required|integer|exists:items,id',
                'staff_id' => 'required|integer|exists:users,id',
                'quantity' => 'required|integer|min:1',
                'total_price' => 'required|numeric',
                'date' => 'required|date',
                'stock_id' => 'required|integer|exists:stocks,id',
            ]);
        } catch (\Exception $e) {
            // Delete the stock record if validation fails
            Stock::where('id', $request->stock_id)->delete();
            // delete the item record if validation fails
            Items::where('id', $request->item_id)->delete();
            throw new \Exception($e->getMessage());
        }

        // Retrieve the pharmacy_id and staff_id for the sale record
        $pharmacyId = session('current_pharmacy_id');

        // Loop through the arrays of item data and create individual sale records
        try {
            //update remaning stock
            $stock = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('id', $request->stock_id)->first();
            $remainQuantity = $stock->remain_Quantity - $request->quantity;
            $stock->update(['remain_Quantity' => $remainQuantity]);

            // dd($request);
            $sale = Sales::create([
                'pharmacy_id' => $pharmacyId,
                'staff_id' => $request->staff_id,
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'stock_id' => $request->stock_id,
                // 'total_price' => $request->total_price,
                'total_price' => $stock->selling_price,
                'date' => $request->date,
            ]);

            return $sale;
        } catch (\Exception $e) {
            // Delete the stock record if validation fails
            Stock::where('id', $request->stock_id)->delete();
            // delete the item record if validation fails
            Items::where('id', $request->item_id)->delete();
            throw new \Exception($e->getMessage());
        }
    }
}
