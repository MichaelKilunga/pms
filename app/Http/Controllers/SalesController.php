<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Pharmacy;
use App\Models\Items;
use App\Models\Staff;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Display a listing of sales.
     */
    public function index()
    {
        // Get sales data for the pharmacies owned by the authenticated user
        
        $sales = Sales::with('item')->where('pharmacy_id', session('current_pharmacy_id'))
            ->get();
            
        $medicines = Stock::where('pharmacy_id', session('current_pharmacy_id'))->with('item')->get();
        // dd($medicines);
        return view('sales.index', compact('sales', 'medicines'));
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

        // Validate the incoming request data for all rows of sales
        $request->validate([
            //NIMEIGNORE HIVI DATA NDIO IKAFANYA KAZI
            // 'pharmacy_id' => 'required|exists:pharmacies,id',
            // 'staff_id' => 'required|exists:users,id',

            'item_id' => 'required|array',         // Ensure it's an array of item IDs
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


        // Retrieve the pharmacy_id and staff_id for the sale record
        $pharmacyId = session('current_pharmacy_id'); // Ensure this is set correctly in your session
        $staffId = Auth::user()->id;



        // Loop through the arrays of item data and create individual sale records
        foreach ($request->item_id as $key => $item_id) {
            
            // dd($request);
            Sales::create([
                'pharmacy_id' => $pharmacyId,         // Use the pharmacy_id from session
                'staff_id' => $staffId,                // Use the staff_id from the authenticated user
                'item_id' => $item_id,
                'quantity' => $request->quantity[$key],
                // 'total_price' => $request->total_price[$key],
                'total_price' => $request->amount[$key],
                'date' => $request->date[$key],
            ]);
        }

        // Redirect with a success message
        return redirect()->route('sales')->with('success', 'Sales recorded successfully.');
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
}
