<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Medicine;
use App\Imports\MedicinesImport;

class MedicineImportController extends Controller
{
    public function showImportForm()
    {
        return view('superAdmin.medicines.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new MedicinesImport, $request->file('file'));
            return redirect()->back()->with('success', 'Medicines imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }
    public function all()
    {

        // $medicines = Medicine::where('brand_name', '!=', 'brand_name')->get();
        $medicines = Medicine::all();
        return view('superAdmin.medicines.index', compact('medicines'));
    }
    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('superAdmin.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:approved,pending,rejected',
        ]);

        $medicine = Medicine::findOrFail($id);
        $medicine->update($request->all());

        return redirect()->route('allMedicines.all')->with('success', 'Medicine updated successfully.');
    }

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return redirect()->route('allMedicines.all')->with('success', 'Medicine deleted successfully.');
    }
}
