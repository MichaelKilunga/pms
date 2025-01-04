<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Pharmacy;
use App\Models\Items;
use App\Models\Medicine;
use App\Models\MedicineStore;
use App\Models\Staff;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StockController extends Controller
{
    /**
     * Display a listing of the stock.
     */
    public function index()
    {
        // Get stocks for pharmacies owned by the authenticated user
        // $pharmacies = Pharmacy::where('owner_id', auth::id())->pluck('id');
        $stocks = Stock::with('staff', 'item')->where('pharmacy_id', session('current_pharmacy_id'))->get();
        $medicines = Items::where('pharmacy_id', session('current_pharmacy_id'))->get();

        // $medicines = Medicine::where('name', '!=', 'name')->get();
        // dd($medicines);

        return view('stock.index', compact('stocks', 'medicines'));
    }

    /**
     * Show the form for creating a new stock entry.
     */
    public function create()
    {
        $pharmacies = Pharmacy::where('owner_id', auth::id())->get();
        $items = Items::all();
        $staff = Staff::whereIn('pharmacy_id', $pharmacies->pluck('id'))->get();

        return view('stock.create', compact('pharmacies', 'items', 'staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|array',
            'item_id.*' => 'required|exists:items,id',

            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',

            // 'remain_Quantity' => 'required|array',
            // 'remain_Quantity.*' => 'required|integer|min:1',

            'low_stock_percentage' => 'required|array',
            'low_stock_percentage.*' => 'required|integer|min:1',

            'buying_price' => 'required|array',
            'buying_price.*' => 'required|numeric',

            'selling_price' => 'required|array',
            'selling_price.*' => 'required|numeric',

            'in_date' => 'required|array',
            'in_date.*' => 'required|date',

            'expire_date' => 'required|array',
            'expire_date.*' => 'required|date',

            'batch_number' => 'required|integer',

            'supplier' => 'required|string|max:255',
        ]);

        // dd($request);

        $pharmacy_id = session('current_pharmacy_id');
        $staff_id = Auth::user()->id;

        foreach ($request->item_id as $index => $item_id) {
            Stock::create([
                'pharmacy_id' => $pharmacy_id,
                'staff_id' => $staff_id,
                'item_id' => $item_id,
                'quantity' => $request->quantity[$index],
                'buying_price' => $request->buying_price[$index],
                'selling_price' => $request->selling_price[$index],
                'remain_Quantity' =>  $request->quantity[$index],
                'low_stock_percentage' => $request->low_stock_percentage[$index],
                'in_date' => $request->in_date[$index],
                'batch_number' => $request->batch_number,
                'supplier' => $request->supplier,
                'expire_date' => $request->expire_date[$index],
            ]);
        }

        return redirect()->route('stock')->with('success', 'Stock added successfully!');
    }

    public function MS_store(Request $request)
    {
        try {
            $request->validate([
                'item_name' => 'required|array',
                'item_name.*' => 'required|string|unique:items,name',

                'quantity' => 'required|array',
                'quantity.*' => 'required|integer|min:1',

                // 'remain_Quantity' => 'required|array',
                // 'remain_Quantity.*' => 'required|integer|min:1',

                'low_stock_percentage' => 'required|array',
                'low_stock_percentage.*' => 'required|integer|min:1',

                'buying_price' => 'required|array',
                'buying_price.*' => 'required|numeric',

                'selling_price' => 'required|array',
                'selling_price.*' => 'required|numeric',

                'in_date' => 'required|array',
                'in_date.*' => 'required|date',

                'expire_date' => 'required|array',
                'expire_date.*' => 'required|date',

                'batch_number' => 'required|integer',

                'supplier' => 'required|string|max:255',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', "Data  not added because: " . $e->getMessage());
        }

        
        $pharmacy_id = session('current_pharmacy_id');
        $staff_id = Auth::user()->id;
        
        //a loop to add the medicine name to the items table and stock to the stock table
        foreach ($request->item_name as $index => $item_name) {
            $item = Items::create([
                'pharmacy_id' => $pharmacy_id,
                'category_id' => 1,
                'name' => $item_name,
            ]);

            Stock::create([
                'pharmacy_id' => $pharmacy_id,
                'staff_id' => $staff_id,
                'item_id' => $item->id,
                'quantity' => $request->quantity[$index],
                'buying_price' => $request->buying_price[$index],
                'selling_price' => $request->selling_price[$index],
                'remain_Quantity' =>  $request->quantity[$index],
                'low_stock_percentage' => $request->low_stock_percentage[$index],
                'in_date' => $request->in_date[$index],
                'batch_number' => $request->batch_number,
                'supplier' => $request->supplier,
                'expire_date' => $request->expire_date[$index],
            ]);
        }

        return redirect()->route('stock')->with('success', 'Stock & Medicine were added successfully!');
    }


    /**
     * Display the specified stock entry.
     */
    public function show(Stock $stock)
    {
        return view('stock.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified stock entry.
     */
    public function edit(Stock $stock)
    {
        return view('stock.edit', compact('stock'));
    }

    /**
     * Update the specified stock entry in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:stocks,id',
                'quantity' => 'required|integer|min:1',
                'remain_Quantity' => 'required|integer',
                'low_stock_percentage' => 'required|integer|min:1',
                'buying_price' => 'required|numeric',
                'selling_price' => 'required|numeric',
                'supplier' => 'required|string|max:255',
                'in_date' => 'required|date',
                'expire_date' => 'required|date',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', "Data  not added because: " . $e->getMessage());
        }

        $request['remain_Quantity'] = $request['quantity'];
        
        $stock = Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('id', $request->id)->first();
        $stock->update($request->only('quantity', 'low_stock_percentage', 'remain_Quantity', 'buying_price', 'selling_price', 'supplier', 'in_date', 'expire_date'));

        return redirect()->route('stock')->with('success', 'Stock updated successfully.');
    }

    /**
     * Remove the specified stock entry from storage.
     */
    public function destroy(Request $request)
    {
        $stock = Stock::destroy($request->id);

        return redirect()->route('stock')->with('success', 'Stock deleted successfully.');
    }
}
