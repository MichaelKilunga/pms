<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Pharmacy;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{
    /**
     * Display a listing of the medicines
     */
    public function index()
    {
        $medicines = Items::with(['category', 'pharmacy'])->where('pharmacy_id', session('current_pharmacy_id'))->get();
        $categories = Category::where('pharmacy_id', session('current_pharmacy_id'))->get();
        $pharmacy = Pharmacy::where('pharmacy_id', session('current_pharmacy_id'))->first();
            // dd($medicines);
        return view('medicines.index', compact('medicines','categories','pharmacy'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $pharmacies = Pharmacy::where('owner_id', auth::id())->get();
        $categories = Category::all();

        return view('medicines.create', compact('pharmacies','medicines', 'categories'));
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pharmacy_id' => 'required|exists:pharmacies,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        Items::create($request->only('pharmacy_id', 'category_id', 'name'));

        return redirect()->route('medicines')->with('success', 'Medicine added successfully.');
    }

    /**
     * Display the specified item.
     */
    public function show($id)
    {
// dd($id);
        $medicine = Items::with(['category','pharmacy'])->where('id',$id)->first();
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
