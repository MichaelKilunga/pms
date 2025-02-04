<?php

namespace App\Http\Controllers;

use App\Models\SaleNote;
use Illuminate\Http\Request;

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
        $salesNotes = SaleNote::where('pharmacy_id', session('current_pharmacy_id'))->with('staff')->get();
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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, SaleNote $saleNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaleNote $saleNote)
    {
        //
    }
}
