<?php

namespace App\Http\Controllers;

use App\Models\SalesReturn;
use App\Models\Sale;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesReturnController extends Controller
{
    /**
     * Display a listing of sales returns.
     */
    public function salesReturns()
    {
        $returns = SalesReturn::with(['sale', 'staff', 'approvedBy'])->orderBy('date', 'desc')
        ->get();
        return view('sales_returns.index', compact('returns'));
    }

    /**
     * Show the form for creating a new sales return.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created sales return in storage.
     */
    public function storeSalesReturns(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);
       

        $sale = Sales::findOrFail($request->sale_id);

        $salesReturn = SalesReturn::create([
            'sale_id' => $request->sale_id,
            'pharmacy_id' => $sale->pharmacy_id,
            'item_id' => $sale->item_id,
            'staff_id' => Auth::user()->id, // Assuming logged-in user
            'quantity' => $request->quantity,
             // Pro-rated refund
            'refund_amount' => $sale->total_price * ($request->quantity / $sale->quantity), // Pro-rated refund
            'reason' => $request->reason,
            'return_status' => 'pending',
            'date' => now(),
        ]);

         //redirect back to sales page
        return redirect()->back()->with('success', 'Sales return created successfully.');

    }

    /**
     * Display the specified sales return.
     */
    public function show(SalesReturn $salesReturn)
    {
        return response()->json($salesReturn->load(['sale', 'staff', 'approvedBy']));
    }

    /**
     * Approve or reject a sales return.
     */
    public function update(Request $request, SalesReturn $salesReturn)
    {
        $request->validate([
            'return_status' => 'required|in:approved,rejected',
        ]);

        $salesReturn->update([
            'return_status' => $request->return_status,
            'approved_by' => auth()->id(), // Assuming logged-in admin
        ]);

        return response()->json(['message' => 'Sales return updated successfully.']);
    }

    /**
     * Remove the specified sales return from storage.
     */
    public function destroy(SalesReturn $salesReturn)
    {
        $salesReturn->delete();
        return response()->json(['message' => 'Sales return deleted successfully.']);
    }
}
