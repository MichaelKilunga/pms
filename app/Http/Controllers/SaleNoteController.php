<?php

namespace App\Http\Controllers;

use App\Models\SaleNote;
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
    public function index()
    {
        //return a  collection of sale notes
        $notes = SaleNote::where('pharmacy_id', session('current_pharmacy_id'))->with('staff');
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

            $saleNote = SaleNote::where('id',$request->id)->first();
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
}
