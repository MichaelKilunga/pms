<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SelectPharmacyController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard', compact('pharmacies'));
//     })->name('dashboard');
// });

Route::middleware(['auth'])->group(function () {
    // Route::resource('dashboard', DashboardController::class);
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('pharmacy', PharmacyController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('sales', SalesController::class);
    Route::resource('stock', StockController::class);

    // Route::resource('medicines', ItemsController::class);
    Route::get('medicines', [ItemsController::class, 'index'])->name('medicines');
    Route::get('medicines/create', [ItemsController::class, 'create'])->name('medicines.create');
    Route::post('medicines', [ItemsController::class, 'store'])->name('medicines.store');
    Route::get('medicines/{id}', [ItemsController::class, 'show'])->name('medicines.show');
    Route::put('medicines/update/{id}', [ItemsController::class, 'destroy'])->name('medicines.edit');
    Route::delete('medicines/delete/{id}', [ItemsController::class, 'destroy'])->name('medicines.destroy');


    Route::get('pharmacies', [PharmacyController::class, 'index'])->name('pharmacies');
    Route::get('pharmacies/create', [PharmacyController::class, 'create'])->name('pharmacies.create');
    Route::post('pharmacies', [PharmacyController::class, 'store'])->name('pharmacies.store');
    Route::get('pharmacies/{id}', [PharmacyController::class, 'show'])->name('pharmacies.show');
    Route::put('pharmacies/update/{id}', [PharmacyController::class, 'destroy'])->name('pharmacies.edit');
    Route::delete('pharmacies/delete/{id}', [PharmacyController::class, 'destroy'])->name('pharmacies.destroy');

    
    Route::get('staff', [StaffController::class, 'index'])->name('staff');
    Route::get('staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('staff/{id}', [StaffController::class, 'show'])->name('staff.show');
    Route::put('staff/update/{id}', [StaffController::class, 'destroy'])->name('staff.edit');
    Route::delete('staff/delete/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');

     
    Route::get('stock', [StockController::class, 'index'])->name('stock');
    Route::get('stock/create', [StockController::class, 'create'])->name('stock.create');
    Route::post('stock', [StockController::class, 'store'])->name('stock.store');
    Route::get('stock/{id}', [StockController::class, 'show'])->name('stock.show');
    Route::put('stock/update/{id}', [StockController::class, 'destroy'])->name('stock.edit');
    Route::delete('stock/delete/{id}', [StockController::class, 'destroy'])->name('stock.destroy');

    Route::get('sales', [SalesController::class, 'index'])->name('sales');
    Route::get('sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('sales', [SalesController::class, 'store'])->name('sales.store');
    Route::get('sales/{id}', [SalesController::class, 'show'])->name('sales.show');
    Route::put('sales/update/{id}', [SalesController::class, 'destroy'])->name('sales.edit');
    Route::delete('sales/delete/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');

    Route::get('category', [CategoryController::class, 'index'])->name('category');
    Route::get('category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('category/{id}', [CategoryController::class, 'show'])->name('category.show');
    Route::put('category/update/{id}', [CategoryController::class, 'destroy'])->name('category.edit');
    Route::delete('category/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::resource('categories', CategoryController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/select', [SelectPharmacyController::class, 'show'])->name('pharmacies.selection');
    Route::get('/switch', [SelectPharmacyController::class, 'switch'])->name('pharmacies.switch');
    Route::post('/select', [SelectPharmacyController::class, 'set'])->name('pharmacies.set');
});