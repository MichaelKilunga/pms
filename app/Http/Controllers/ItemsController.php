<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Pharmacy;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index()
    {
        // Get items for pharmacies owned by the authenticated user
        $pharmacies = Pharmacy::where('owner_id', auth::id())->pluck('id');
        $items = Items::whereIn('pharmacy_id', $pharmacies)
            ->with('category', 'pharmacy')
            ->get();

        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $pharmacies = Pharmacy::where('owner_id', auth::id())->get();
        $categories = Category::all();

        return view('items.create', compact('pharmacies', 'categories'));
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

        return redirect()->route('items.index')->with('success', 'Item added successfully.');
    }

    /**
     * Display the specified item.
     */
    public function show(Items $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Items $item)
    {
        $pharmacies = Pharmacy::where('owner_id', auth::id())->get();
        $categories = Category::all();

        return view('items.edit', compact('item', 'pharmacies', 'categories'));
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

        $item->update($request->only('pharmacy_id', 'category_id', 'name'));

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(Items $item)
    {
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
