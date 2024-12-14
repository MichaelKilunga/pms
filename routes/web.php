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
    Route::resource('items', ItemsController::class);
    Route::resource('categories', CategoryController::class);
});

Route::middleware(['auth'])->group(function () {
    // Route::get('/select', [SelectPharmacyController::class, 'show'])->name('pharmacies.selection');
    Route::post('/select', [SelectPharmacyController::class, 'set'])->name('pharmacies.set');
});