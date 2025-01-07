<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Pharmacy;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ItemsController extends Controller
{
    /**
     * Display a listing of the medicines
     */
    public function index()
    {
        $medicines = Items::with(['category', 'pharmacy'])->where('pharmacy_id', session('current_pharmacy_id'))->get();
        $categories = Category::get();
        // $pharmacy = Pharmacy::where('pharmacy_id', session('current_pharmacy_id'))->first();

        // dd($medicines);
        return view('medicines.index', compact('medicines', 'categories'));
    }

    public function import(Request $request)
    {
        $onlineMedicines = Medicine::where('brand_name', '!=', 'brand_name')->get();
        return view('medicines.import', compact('onlineMedicines'));
    }


    public function importStore(Request $request)
    {
        try{
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
        ]);
    }catch(Exception $e){
            return response()->json(['message' => 'Failed to import medicine because: '.$e->getMessage()], 500);
        // return redirect()->back()->withInput()->with('error', 'Failed to import beacuse: '.$e->getMessage());
    }

        try {
            $medicine = Medicine::findOrFail($request->medicine_id);
            $request['name'] = $medicine->generic_name.' ('.$medicine->brand_name.')';
            $request->validate([
                'name' => 'unique:items,name',
            ]);
            // Get the current pharmacy ID (assuming it's stored in the session or user context)
            $pharmacyId = session('current_pharmacy_id');

            if (!$pharmacyId) {
                return response()->json(['message' => 'Pharmacy not selected.'], 400);
            }

            // Insert the medicine into the items table
            Items::create([
                'name' => $request->name,
                'pharmacy_id' => $pharmacyId,
                'category_id' => 1,
            ]);

            return response()->json(['message' => 'Medicine imported successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to import this medicine because: '.$e->getMessage()], 500);
        }
    }


    public function search(Request $request)
    {
        // Get the search term from the request
        $search = $request->input('search');
        $currentPharmacyId = session('current_pharmacy_id'); // Fetch the current pharmacy ID from the session

        // Query to find if exact medicine is available in the current pharmacy
        $availableMedicine = DB::table('items')
            ->join('stocks', 'items.id', '=', 'stocks.item_id')
            ->where('items.name', $search)
            ->where('stocks.remain_Quantity', '>', 0)
            ->where('stocks.pharmacy_id', '=', $currentPharmacyId) // Filter by pharmacy ID
            ->exists();

        // Query to find similar medicines in the current pharmacy
        $similarMedicines = DB::table('items')
            ->join('stocks', 'items.id', '=', 'stocks.item_id')
            ->where('items.name', 'LIKE', '%' . $search . '%')
            ->where('items.name', '!=', $search)
            ->where('stocks.remain_Quantity', '>', 0)
            ->where('stocks.pharmacy_id', '=', $currentPharmacyId) // Filter by pharmacy ID
            ->pluck('items.name');

        // Prepare the response
        return response()->json([
            'availableMedicine' => $availableMedicine ? 'Available' : 'Not Available',
            'similarMedicines' => $similarMedicines,
        ]);
    }


    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $pharmacies = Pharmacy::where('owner_id', auth::id())->get();
        $categories = Category::all();

        return view('medicines.create', compact('pharmacies', 'medicines', 'categories'));
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {
        $request['pharmacy_id'] = session('current_pharmacy_id');
        // $request['pharmacy_id'] =20;
        try {
            $request->validate([
                'pharmacy_id' => 'required|exists:pharmacies,id',
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
            ]);
        } catch (Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->withInput()->with('error',"Data  not added because: ".$e->getMessage());
        }
        // dd($request['pharmacy_id']);

        Items::create($request->only('pharmacy_id', 'category_id', 'name'));

        return redirect()->route('medicines')->with('success', 'Medicine added successfully.');
    }

    /**
     * Display the specified item.
     */
    public function show($id)
    {
        // dd($id);
        $medicine = Items::with(['category', 'pharmacy'])->where('id', $id)->first();
        return view('medicines.show', compact('medicine'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Items $item)
    {
        $pharmacies = Pharmacy::where('owner_id', auth::id())->get();
        $categories = Category::all();

        return view('medicines.edit', compact('item', 'pharmacies', 'categories'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, Items $item)
    {
        $request->validate([
            'pharmacy_id' => 'required|exists:pharmacies,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        $item = Items::where('pharmacy_id', $request->pharmacy_id)->where('id', $request->id);
        $item->update($request->only('pharmacy_id', 'category_id', 'name'));

        return redirect()->route('medicines')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(Request $request, Items $item)
    {
        // dd($request->id);
        // $item->delete();
        Items::destroy($request->id);

        return redirect()->route('medicines')->with('success', 'Medicine deleted successfully!');
    }
}
