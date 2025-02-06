<?php

namespace App\Http\Controllers;

use App\Models\SaleNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if ($request->filter == true) {
            $notes = SaleNote::where('pharmacy_id', session('current_pharmacy_id'))->whereDate('created_at', Carbon::today())->with('staff');
        }

        $saleNotes = null;
        if (Auth::user()->role == 'staff') {
            $saleNotes = $notes->where('staff_id', Auth::user()->id)->get();
        } else {
            $saleNotes = $notes->get();
        }

        $salesNotes = $saleNotes;
        return view('sales_notes.index', compact('salesNotes'));
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
    public function promoteSalesNotes(Request $request){
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

            dd($request->all());

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
