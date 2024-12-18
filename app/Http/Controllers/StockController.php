<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Pharmacy;
use App\Models\Items;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Display a listing of the stock.
     */
    public function index()
    {
        // Get stocks for pharmacies owned by the authenticated user
        // $pharmacies = Pharmacy::where('owner_id', auth::id())->pluck('id');
        $stocks = Stock::with('staff', 'item')->where('pharmacy_id', session('current_pharmacy_id'))->get();
        $medicines = Items::where('pharmacy_id', session('current_pharmacy_id'))->get();

        return view('stock.index', compact('stocks', 'medicines'));
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

    /**
     * Store a newly created stock entry in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|array',
            'item_id.*' => 'required|exists:items,id',

            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',

            'buying_price' => 'required|array',
            'buying_price.*' => 'required|numeric',

            'selling_price' => 'required|array',
            'selling_price.*' => 'required|numeric',

            'in_date' => 'required|array',
            'in_date.*' => 'required|date',
            
            'expire_date' => 'required|array',
            'expire_date.*' => 'required|date',
        ]);

        // Assuming 'pharmacy_id' and 'staff_id' are constants or come from the request
        $pharmacy_id = session('current_pharmacy_id');
        $staff_id = Auth::user()->id;
        

        // Loop through the rows of input and create the stock records
        foreach ($request->item_id as $index => $item_id) {

            Stock::create([
                'pharmacy_id' => $pharmacy_id,
                'staff_id' => $staff_id,
                'item_id' => $item_id,
                'quantity' => $request->quantity[$index],
                'buying_price' => $request->buying_price[$index],
                'selling_price' => $request->selling_price[$index],
                'in_date' => $request->in_date[$index],
                'expire_date' => $request->expire_date[$index],
            ]);
        }

        return redirect()->route('stock')->with('success', 'Stock added successfully.');
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
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'buying_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'in_date' => 'required|date',
            'expire_date' => 'required|date',
        ]);
        $stock = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('id',$request->id)->first();
        $stock->update($request->only('quantity', 'buying_price', 'selling_price', 'in_date', 'expire_date'));

        return redirect()->route('stock')->with('success', 'Stock updated successfully.');
    }

    /**
     * Remove the specified stock entry from storage.
     */
    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stock')->with('success', 'Stock deleted successfully.');
    }
}
