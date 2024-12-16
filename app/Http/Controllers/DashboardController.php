<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\Items;
use App\Models\Sales;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalMedicines = Items::count();
        $totalSales = Sales::sum('amount'); // Adjust as needed
        $lowStockCount = Items::where('stock', '<', 10)->count(); // Low stock threshold
// dd($totalMedicines);
        $medicines = Items::all(['name', 'stock']);
        $medicineNames = $medicines->pluck('name');
        $medicineStock = $medicines->pluck('stock');
        $medicineSales = $medicines->map(function ($medicine) {
            return $medicine->sales->sum('quantity'); // Assuming a `sales` relationship
        });

        if (Auth::user()->role == 'owner') {
            $pharmacies = Pharmacy::where('owner_id', Auth::user()->id)->get();
            return view('dashboard', compact(
                'pharmacies',
                'totalMedicines',
                'totalSales',
                'lowStockCount',
                'medicines',
                'medicineNames',
                'medicineStock',
                'medicineSales'
            ));
        } else {
            $staff = Staff::where('user_id', Auth::user()->id)->first();
            // dd($staff->pharmacy_id);
            $pharmacy = Pharmacy::where('id', $staff->pharmacy_id)->first();
            // dd(Staff::all());
            return view('dashboard', compact(
                'pharmacy',
                'totalMedicines',
                'totalSales',
                'lowStockCount',
                'medicines',
                'medicineNames',
                'medicineStock',
                'medicineSales'
            ));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
