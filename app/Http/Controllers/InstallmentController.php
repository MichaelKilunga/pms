<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Installment;
use App\Models\Stock;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{

    public function index()
    {
        $installments = Installment::with('debt.stock')->get();
        $debts = Debt::all(); // for modal dropdown

        return view('debts.installment', compact('installments', 'debts'));
    }

    public function store(Request $request)
    {
        try {

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
            return redirect()->route('installments.installment')->with('success', 'Stock Debt Installment added successfully.');
        } catch (\Exception $e) {
            // Handle the exception (e.g., log it, display an error message, etc.)
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroyinst($id){

        // dd($request->all());
           try {
            $installment = Installment::findOrFail($id);
            // $debt->installments()->delete(); // Delete related installments first
            $installment->delete();
            return redirect()->route('debts.index')->with('success', 'Installments deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
