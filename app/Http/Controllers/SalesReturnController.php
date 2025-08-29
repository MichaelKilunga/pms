<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\SalesReturn;
use App\Models\Sale;
use App\Models\Sales;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\InAppNotification;
use Illuminate\Support\Facades\Notification;

class SalesReturnController extends Controller
{
    /**
     * Display a listing of sales returns.
     */
    public function salesReturns()
    {

        if (Auth::user()->role == 'owner') {
            $returns = SalesReturn::with(['sale', 'staff', 'approvedBy'])
                ->where('pharmacy_id', session('current_pharmacy_id'))
                ->orderBy('date', 'desc')->orderBy('date', 'desc')
                ->get();
        } else {
            $returns = SalesReturn::with(['sale', 'staff', 'approvedBy'])
                ->where('pharmacy_id', session('current_pharmacy_id'))
                ->where('staff_id', Auth::user()->id) // Add this condition to filter by the logged-in user's ID
                ->orderBy('date', 'desc')->orderBy('date', 'desc')
                ->get();
        }

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
        try {
            $request->validate([
                'sale_id' => 'required|exists:sales,id',
                'quantity' => 'required|integer|min:1',
                'reason' => 'nullable|string',
            ]);


            $sale = Sales::findOrFail($request->sale_id);

            // if the sale return is already rejected, then update the status to pending
            if ($sale->salesReturn && $sale->salesReturn->return_status == 'rejected') {
                $sale->salesReturn->return_status = 'pending';
                $sale->salesReturn->quantity = $request->quantity;
                $sale->salesReturn->refund_amount = $sale->total_price * ($request->quantity / $sale->quantity); // Pro-rated refund
                $sale->salesReturn->reason = $request->reason;
                $sale->salesReturn->save();
            } else {
                $salesReturn = SalesReturn::create([
                    'sale_id' => $request->sale_id,
                    'quantity' => $request->quantity,
                    'refund_amount' => $sale->total_price * ($request->quantity / $sale->quantity), // Pro-rated refund
                    'reason' => $request->reason,
                    'return_status' => 'pending',
                    'pharmacy_id' => session('current_pharmacy_id'),
                    'staff_id' => Auth::user()->id,
                    'date' => now(),
                ]);
            }

            // debug
            // dd($salesReturn);

            //redirect back to sales page
            return redirect()->back()->with('success', 'Sales return requested successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
    public function updateSalesReturns(Request $request)
    {

        try {
            $request->validate([
                'return_status' => 'required|in:approved,rejected',
                'return_id' => 'required|exists:sales_returns,id',
                // 'stock_id' => 'required|exists:stocks,id',
            ]);

            //debug
            // dd($request->all());

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        // dd($request->all());

        //update sales return status set return status to approved or rejected and approved by to the logged in user
        $salesReturn = SalesReturn::findOrFail($request->return_id);
        $sale = Sales::findOrFail($salesReturn->sale_id);

        // dd($sale);

        $salesReturn->update([
            'return_status' => $request->return_status,
            'approved_by' => Auth::user()->id, // Assuming logged-in admin
        ]);



        // delete from slaes table if return is approved
        if ($request->return_status == 'approved') {
            $sale = Sales::findOrFail($salesReturn->sale_id);

            // update item quantity in the stock 
            $stock = Stock::findOrFail($sale->stock_id);
            $stock->update([
                'remain_Quantity' => $stock->remain_Quantity + $salesReturn->quantity,
            ]);
            // fetch sales returns with staff, sale and approvedBy
            $salesReturn = SalesReturn::with(['sale' => function ($query) {
                $query->with('item');
            }, 'staff', 'approvedBy'])
                ->where('id', $request->return_id)
                ->first();

            // dd($salesReturn);

            //delete sale
            try {
                // send notification to the user who made the sale and the user who approved the return
                $this->notify($salesReturn->approvedBy, 'You have approved a sales return of ' . $salesReturn->sale->item->name . ', Quantity:' . $salesReturn->sale->quantity . ' requested by ' . $salesReturn->staff->name, 'success');
                $this->notify($salesReturn->staff, 'Returns for sales of ' . $salesReturn->sale->item->name . ', Quantity:' . $salesReturn->sale->quantity . ' has been approved by ' . $salesReturn->approvedBy->name, 'success');
                // delete sale and returns
                SalesReturn::destroy($salesReturn->id);
                Sales::destroy($sale->id);
                return redirect()->back()->with('success', 'Sales return approved successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            try {
                // send notification to the user who made the sale and the user who approved the return
                $salesReturn = SalesReturn::with(['sale' => function ($query) {
                    $query->with('item');
                }, 'staff', 'approvedBy'])
                    ->where('id', $request->return_id)
                    ->first();
                $this->notify($salesReturn->approvedBy, 'You have rejected a sales return of ' . $salesReturn->sale->item->name . ', Quantity:' . $salesReturn->sale->quantity . ' requested by ' . $salesReturn->staff->name, 'danger');
                $this->notify($salesReturn->staff, 'Returns for sales of ' . $salesReturn->sale->item->name . ', Quantity:' . $salesReturn->sale->quantity . ' has been rejected by ' . $salesReturn->approvedBy->name, 'danger');
                return redirect()->back()->with('success', 'Sales return rejected successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
    }

    /**
     * Remove the specified sales return from storage.
     */
    public function destroy(SalesReturn $salesReturn)
    {
        $salesReturn->delete();
        return response()->json(['message' => 'Sales return deleted successfully.']);
    }

    private function notify(User $user, string $message, string $type): void
    {
        $notification = [
            'message' => $message,
            'type' => $type,
        ];
        $user->notify(new InAppNotification($notification));
    }
}
