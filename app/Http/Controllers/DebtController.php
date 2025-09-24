<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Stock;
use App\Models\Item;
use App\Models\Installment;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index()
    {

        //return stock with medicine
        $stocks = Stock::with('item')->get();
        $debts = Debt::with('stock', 'installments')->get();
        // dd($debts);

        return view('debts.index', compact('debts', 'stocks'));
    }

    public function store(Request $request)
    {
        try {
            // Your code that may throw an exception
            $request->validate([
                'stock_id' => 'required|exists:stocks,id',
                'debtAmount' => 'required|integer|min:1',
            ]);

            $debt = Debt::create($request->only('stock_id', 'debtAmount'));
            // return response()->json(['success' => true, 'debt' => $debt]);
            return redirect()->route('debts.index')->with('success', 'Stock debt added successfully.');
        } catch (\Exception $e) {
            // Handle the exception (e.g., log it, display an error message, etc.)
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

        public function storeinst(Request $request)
    {
        try {
            // dd($request->all());
            $debt = Debt::findOrFail($request->debt_id);
            $request->validate(['amount' => 'required|numeric|min:1']);

            $installment = Installment::create([
                'debt_id' => $debt->id,
                'amount' => $request->amount,
            ]);

            // Update debt status
            $totalPaid = $debt->totalPaid();
            if ($totalPaid >= $debt->debtAmount) {
                $debt->status = 'Full paid';
            } elseif ($totalPaid > 0) {
                $debt->status = 'Partial paid';
            }
            $debt->save();

           //redirect to installments page
            return redirect()->route('debts.index')->with('success', 'Stock Debt Installment added successfully.');
        } catch (\Exception $e) {
            // Handle the exception (e.g., log it, display an error message, etc.)
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    // destrouy debt
    public function destroy($id)
    {
        try {
            $debt = Debt::findOrFail($id);
            $debt->installments()->delete(); // Delete related installments first
            $debt->delete();
            return redirect()->route('debts.index')->with('success', 'Debt and its installments deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
