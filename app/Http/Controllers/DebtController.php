<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Stock;
use App\Models\Item;
use App\Models\Installment;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index()
    {
        //return stock with medicine that has not listed on stock debts
        $stocks = Stock::with('item')
            ->where('pharmacy_id', session('current_pharmacy_id'))
            ->whereDoesntHave('debts')
            ->get();

        $debts = Debt::with('stock', 'installments')
            ->where('pharmacy_id', session('current_pharmacy_id'))
            ->get();


        return view('debts.index', compact('debts', 'stocks'));
    }

    public function store(Request $request)
    {
        try {
            // Your code that may throw an exception
            $request['pharmacy_id'] = session('current_pharmacy_id');

            $request->validate([
                'stock_id' => 'required|exists:stocks,id',
                'pharmacy_id' => 'required|exists:pharmacies,id',
                'debtAmount' => 'required|integer|min:1',
            ]);

            $debt = Debt::create($request->only('pharmacy_id', 'stock_id', 'debtAmount'));

            return redirect()->route('debts.index')->with('success', 'Stock debt added successfully.');
        } catch (\Exception $e) {
            // Handle the exception (e.g., log it, display an error message, etc.)
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function storeinst(Request $request)
    {
        try {
            $request['pharmacy_id'] = session('current_pharmacy_id');
            $debt = Debt::findOrFail($request->debt_id);
            $request->validate(['amount' => 'required|numeric|min:1']);
            $request->validate(['pharmacy_id' => 'required|exists:pharmacies,id']);
            $request->validate(['description' => 'nullable|string']);

            $installment = Installment::create([
                'debt_id' => $debt->id,
                'pharmacy_id' => $request->pharmacy_id,
                'amount' => $request->amount,
                'description' => $request->description,
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
            return redirect()->route('debts.index')->with('success', 'Stock debt installment added successfully.');
        } catch (\Exception $e) {
            // Handle the exception (e.g., log it, display an error message, etc.)
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    //uodate debt
    public function update(Request $request, $id)
    {
        try {
            $debt = Debt::findOrFail($id);
            $request->validate([
                'stock_id' => 'required|exists:stocks,id',
                'amount' => 'required|integer|min:1',
            ]);

            $debt->stock_id = $request->stock_id;
            $debt->debtAmount = $request->amount;
            $debt->save();

            return redirect()->route('debts.index')->with('success', 'Stock debt updated successfully.');
        } catch (\Exception $e) {
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
            return redirect()->route('debts.index')->with('success', 'Stock debt deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
