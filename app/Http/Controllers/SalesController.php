<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Pharmacy;
use App\Models\Items;
use App\Models\Staff;
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
        $pharmacies = Pharmacy::where('owner_id', auth::id())->pluck('id');
        $sales = Sales::whereIn('pharmacy_id', $pharmacies)
            ->with('staff', 'pharmacy', 'item')
            ->get();

        return view('sales.index', compact('sales'));
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
        $request->validate([
            'pharmacy_id' => 'required|exists:pharmacies,id',
            'item_id' => 'required|exists:items,id',
            'staff_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric',
            'date' => 'required|date',
        ]);

        Sales::create($request->only('pharmacy_id', 'item_id', 'staff_id', 'quantity', 'total_price', 'date'));

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully.');
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

        $sale->update($request->only('quantity', 'total_price'));

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(Sales $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}
