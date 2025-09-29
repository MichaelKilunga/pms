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
        //get all installments with debt and stock
        $installments = Installment::with('debt.stock')
         ->where('pharmacy_id', session('current_pharmacy_id'))
        ->get();

        $debts = Debt::all()
         ->where('pharmacy_id', session('current_pharmacy_id'))
        ; 

        return view('debts.installment', compact('installments', 'debts'));
    }

    // public function store(Request $request)
    // {
    //     try {

    //         $debt = Debt::findOrFail($request->debt_id);
    //         $request->validate(['amount' => 'required|numeric|min:1']);
    //         $request->validate(['pharmacy_id' => 'required|exists:pharmacies,id']);
    //         // dd($request->all());

    //         $installment = Installment::create([
    //             'debt_id' => $debt->id,
    //             // 'pharmacy_id'=>$request->pharmacy_id,
    //             'amount' => $request->amount,
    //         ]);

    //         // Update debt status
    //         $totalPaid = $debt->totalPaid();
    //         if ($totalPaid >= $debt->debtAmount) {
    //             $debt->status = 'Full paid';
    //         } elseif ($totalPaid > 0) {
    //             $debt->status = 'Partial paid';
    //         }
    //         $debt->save();

    //        //redirect to installments page
    //         return redirect()->route('installments.installment')->with('success', 'Stock debt installment added successfully.');
    //     } catch (\Exception $e) {
    //         // Handle the exception (e.g., log it, display an error message, etc.)
    //         return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    //     }
    // }

    public function destroyinst($id){

           try {
            $installment = Installment::findOrFail($id);

            //if remining amount is greater than 0 change debt status to unpaid
            $debt = Debt::findOrFail($installment->debt_id);
            $totalPaid = $debt->totalPaid() - $installment->amount;
            if ($totalPaid <= 0) {
                $debt->status = 'Not paid';
            } elseif ($totalPaid > 0 && $totalPaid < $debt->debtAmount) {
                $debt->status = 'Partial paid';
            }
            $debt->save();

            //delete installment
            $installment->delete();
            return redirect()->route('debts.index')->with('success', 'Stock debt installments deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
