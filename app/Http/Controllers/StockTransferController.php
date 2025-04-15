<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockTransfer;
use App\Models\Pharmacy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StockTransferController extends Controller
{

    public function index()
    {
        //call stockstransfer of the current pharmacy
        $transfers = StockTransfer::with(['stock.item', 'fromPharmacy', 'toPharmacy', 'transferredBy'])
            ->where('from_pharmacy_id', session('current_pharmacy_id'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        //calling medicines that are in the pharmacy
        //all medicine that are in the pharmacy but not expired and remaining stock >0
        $pharmacyId = session('current_pharmacy_id');
        $stocks = Stock::where('pharmacy_id', $pharmacyId)
            ->whereHas('item', function ($query) {
                $query->where('expire_date', '>', now());
                $query->where('remain_Quantity', '>', 0);
            })
            ->with('item')
            ->get();

        // sijafanikiwa kuvuta pharmacy ambazo nizammliliki wa sasa lakini sio ya sasa
        // all pharmacy belong to the current login user
        $pharmacies = Pharmacy::where('id', '!=', $pharmacyId)
            ->where('owner_id', Auth::id())
            // ->whereHas('users', function ($query) {
            //     $query->where('user_id', Auth::id());
            // })
            ->get();

        return view('stockTransfers.index', compact('transfers', 'stocks', 'pharmacies'));
    }


    public function create()
    {
        $pharmacyId = session('current_pharmacy_id');
        $stocks = Stock::where('pharmacy_id', $pharmacyId)->with('item')->get();
        $pharmacies = Pharmacy::where('id', '!=', $pharmacyId)->get();

        return view('stockTransfers.create', compact('stocks', 'pharmacies'));
    }

    //store stock transfer
    public function store(Request $request)
    {
        try {

            $request->validate([
                'stock_id.*' => 'required|exists:stocks,id',
                'quantity.*' => 'required|integer|min:1',
                // check if exists in pharmacies id or is equal to 0
                'to_pharmacy_id' => [
                    'required',
                    Rule::in(array_merge(
                        [0],
                        Pharmacy::pluck('id')->toArray()
                    )),
                ],
                'to_pharmacy_name' => 'nullable|string|max:255',
                'tin_number' => 'nullable|string|max:255',
                'transfer_date' => 'required|date',
                'notes' => 'nullable|string',
            ]);
            // dd($request->all());
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }


        // call pharmacy id of the current user
        $fromPharmacyId = session('current_pharmacy_id');

        foreach ($request->stock_id as $index => $stockId) {
            StockTransfer::create([
                'stock_id' => $stockId,
                'quantity' => $request->quantity[$index],
                'to_pharmacy_id' => $request->to_pharmacy_id == 0 ? null :  $request->to_pharmacy_id,
                'to_pharmacy_name' => $request->to_pharmacy_name,
                'to_pharmacy_tin' => $request->tin_number,
                'transfer_date' => $request->transfer_date,
                'notes' => $request->notes,
                'transferred_by' => Auth::id(),
                'from_pharmacy_id' => $fromPharmacyId,
            ]);
        }
        foreach ($request->stock_id as $index => $stockId) {
            // Update the stock quantity
            $stock = Stock::findOrFail($stockId);
            $stock->remain_Quantity -= $request->quantity[$index];
            $stock->save();
        }

        return redirect()->route('stockTransfers.index')->with('success', 'Stock transferred successfully.');
    }


    //return transfer stock and delete the transfer record
    public function destroy($id)
    {
        $transfer = StockTransfer::findOrFail($id);
        $stock = Stock::findOrFail($transfer->stock_id);

        // Update remaining quantity
        $stock->remain_Quantity += $transfer->quantity;
        $stock->save();

        // Delete transfer record
        $transfer->delete();

        return redirect()->route('stockTransfers.index')->with('success', 'Stock transfer restored and stock returned successfully.');
    }

    //confirm by updating the transfer record status completed from pending
    public function confirm($id)
    {
        $transfer = StockTransfer::findOrFail($id);
        // $transfer->status = 'completed';
        // $transfer->save();

        // update the other transfers made
        $otherTransfers = StockTransfer::where('from_pharmacy_id', $transfer->from_pharmacy_id)
            // ->where('to_pharmacy_id', $transfer->to_pharmacy_id)
            ->where('created_at', $transfer->created_at)
            ->update(['status' => 'completed']);

        return redirect()->route('stockTransfers.index')->with('success', 'Stock transfer confirmed and completed successfully.');
    }

    //print transfer invoice
    public function print($id)
    {
        $transfer = StockTransfer::with(['stock.item', 'fromPharmacy', 'toPharmacy', 'transferredBy'])
            ->where('from_pharmacy_id', session('current_pharmacy_id'))
            ->where('created_at', StockTransfer::find($id)->created_at);

        return view('stockTransfers.print', compact('transfer'));
    }
}
