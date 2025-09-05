<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\AuditController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SelectPharmacyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\MedicineImportController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ReportPrintController;
use App\Http\Controllers\SmsPush;
use App\Models\Package;
use App\Notifications\SmsNotification;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractUpdateController;
use App\Http\Controllers\SaleNoteController;
use App\Http\Controllers\SalesReturnController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ExpenseController;


Route::get('/', function () {
    //return welcome view with packages
    return view('welcome', ['packages' => Package::where('id', '!=', 1)->get(), 'agents' => User::where('role', 'agent')->get()]);
});

Route::middleware(['auth', 'eligible:hasContract'])->group(function () {
    Route::get('schedule', function () {
        // Run the Artisan schedule:run command to manually trigger the scheduled tasks
        Artisan::call('schedule:run');

        return 'Scheduler task triggered and executed!';
    })->name('schedule');

    //USERS
    Route::get('/superadmin/users', [SuperAdminController::class, 'manageUsers'])->name('superadmin.users');
    Route::get('/superadmin/users/{id}/edit', [SuperAdminController::class, 'editUser'])->name('superadmin.users.edit');
    Route::put('/superadmin/users/{id}', [SuperAdminController::class, 'updateUser'])->name('superadmin.users.update');
    Route::get('/superadmin/users/{id}', [SuperAdminController::class, 'showUser'])->name('superadmin.users.show');
    Route::delete('/superadmin/users/{id}', [SuperAdminController::class, 'deleteUser'])->name('superadmin.users.delete');

    //PHARMACIES
    Route::get('/superadmin/pharmacies', [SuperAdminController::class, 'managePharmacies'])->name('superadmin.pharmacies');
    Route::get('/superadmin/pharmacies/{id}/edit', [SuperAdminController::class, 'editPharmacy'])->name('superadmin.pharmacies.edit');
    Route::put('/superadmin/pharmacies/{id}', [SuperAdminController::class, 'updatePharmacy'])->name('superadmin.pharmacies.update');
    Route::get('/superadmin/pharmacies/{id}', [SuperAdminController::class, 'showPharmacy'])->name('superadmin.pharmacies.show');
    Route::delete('/superadmin/pharmacies/{id}', [SuperAdminController::class, 'deletePharmacy'])->name('superadmin.pharmacies.delete');

    //PACKAGES
    Route::get('packages', [PackageController::class, 'index'])->name('packages');
    Route::get('packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::post('packages', [PackageController::class, 'store'])->name('packages.store');
    Route::get('packages/edit/{id}', [PackageController::class, 'edit'])->name('packages.edit');
    Route::get('packages/{package}', [PackageController::class, 'show'])->name('packages.show');
    Route::put('packages/update/{id}', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('packages/delete/{id}', [PackageController::class, 'destroy'])->name('packages.destroy');


    Route::get('allMedicines', [MedicineImportController::class, 'all'])->name('allMedicines.all');
    Route::get('allMedicines/{id}/edit', [MedicineImportController::class, 'edit'])->name('allMedicines.edit');
    Route::put('allMedicines/{id}', [MedicineImportController::class, 'update'])->name('allMedicines.update');
    Route::delete('allMedicines/{id}', [MedicineImportController::class, 'destroy'])->name('allMedicines.destroy');
    Route::get('/medicines/import', [MedicineImportController::class, 'showImportForm'])->name('medicines.import-form');
    Route::post('/medicines/import', [MedicineImportController::class, 'import'])->name('medicines.import');

    // Route::resource('medicines', ItemsController::class);
    Route::get('medicines', [ItemsController::class, 'index'])->name('medicines');

    Route::middleware(['eligible:add medicine'])->group(function () {
        Route::get('medicines/create', [ItemsController::class, 'create'])->name('medicines.create');
        Route::post('medicines', [ItemsController::class, 'store'])->name('medicines.store');
        Route::post('medicineStock', [StockController::class, 'MS_store'])->name('medicineStock.store');
        Route::post('/import', [ItemsController::class, 'importStore'])->name('importStore');
    });

    Route::get('medicines/search', [ItemsController::class, 'search'])->name('medicines.search');
    Route::get('medicines/{id}', [ItemsController::class, 'show'])->name('medicines.show');
    Route::put('medicines/update/{id}', [ItemsController::class, 'update'])->name('medicines.update');
    Route::delete('medicines/delete/{id}', [ItemsController::class, 'destroy'])->name('medicines.destroy');
    Route::get('/import', [ItemsController::class, 'import'])->name('import');

    Route::get('pharmacies', [PharmacyController::class, 'index'])->name('pharmacies');
    Route::get('pharmacies/create', [PharmacyController::class, 'create'])->name('pharmacies.create');
    Route::get('pharmacies/{id}', [PharmacyController::class, 'show'])->name('pharmacies.show');
    Route::put('pharmacies', [PharmacyController::class, 'update'])->name('pharmacies.update');
    Route::delete('pharmacies/delete/{id}', [PharmacyController::class, 'destroy'])->name('pharmacies.destroy');

    Route::get('staff', [StaffController::class, 'index'])->name('staff');

    Route::middleware(['eligible:add staff'])->group(function () {
        Route::get('staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
    });

    Route::get('staff/{id}', [StaffController::class, 'show'])->name('staff.show');
    Route::put('staff', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('staff/delete/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');

    Route::get('stock', [StockController::class, 'index'])->name('stock');
    Route::get('stock/create', [StockController::class, 'create'])->name('stock.create');
    Route::post('stock', [StockController::class, 'store'])->name('stock.store');
    Route::get('stock/{id}', [StockController::class, 'show'])->name('stock.show');
    Route::put('stock', [StockController::class, 'update'])->name('stock.update');
    Route::delete('stock/delete/{id}', [StockController::class, 'destroy'])->name('stock.destroy');
    Route::post('/stock/import', [StockController::class, 'import'])->name('importMedicineStock');
    Route::get('/stock-balances', [StockController::class, 'viewStockBalances'])->name('stocks.balance');
    //adde these for viewing stock balance
    Route::get('/stock/balances', [StockController::class, 'viewStockBalances'])->name('stock.balances');
    Route::get('/stock/details/{id}', [StockController::class, 'getStockDetails'])->name('stock.details');


    //stock stransfer
    Route::get('stockTransfers', [StockTransferController::class, 'index'])->name('stockTransfers');
    Route::get('/stock-transfers', [StockTransferController::class, 'index'])->name('stockTransfers.index');
    Route::post('stockTransfers', [StockTransferController::class, 'store'])->name('stockTransfers.store');
    Route::delete('stockTransfers/{id}', [StockTransferController::class, 'destroy'])->name('stockTransfers.destroy');
    Route::get('stockTransfers/{id}/confirm', [StockTransferController::class, 'confirm'])->name('stockTransfers.confirm');
    Route::get('stockTransfers/{id}/print', [StockTransferController::class, 'print'])->name('stockTransfers.print');

    Route::get('sales', [SalesController::class, 'index'])->name('sales');
    Route::get('sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('sales', [SalesController::class, 'store'])->name('sales.store');
    Route::get('sales/{id}', [SalesController::class, 'show'])->name('sales.show');
    Route::put('sales', [SalesController::class, 'update'])->name('sales.update');
    Route::delete('sales/delete/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');

    //create sales return
    Route::get('salesReturns', [SalesReturnController::class, 'salesReturns'])->name('salesReturns');
    Route::get('salesReturns/create', [SalesReturnController::class, 'createSalesReturns'])->name('salesReturns.create');
    Route::post('salesReturns/store', [SalesReturnController::class, 'storeSalesReturns'])->name('salesReturns.store');
    Route::get('salesReturns/{id}', [SalesReturnController::class, 'showSalesReturns'])->name('salesReturns.show');
    Route::post('salesReturns/update', [SalesReturnController::class, 'updateSalesReturns'])->name('salesReturns.update');
    Route::delete('salesReturns/delete/{id}', [SalesReturnController::class, 'destroySalesReturns'])->name('salesReturns.destroy');
    Route::get('salesReturns/{id}/edit', [SalesReturnController::class, 'editSalesReturns'])->name('salesReturns.edit');



    Route::get('allReceipts', [SalesController::class, 'allReceipts'])->name('allReceipts');
    Route::get('print/lastReceipt', [SalesController::class, 'printLastReceipt'])->name('print.lastReceipt');
    Route::get('printReceipt', [SalesController::class, 'printReceipt'])->name('printReceipt');

    Route::get('category', [CategoryController::class, 'index'])->name('category');
    Route::get('category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('category/{id}', [CategoryController::class, 'show'])->name('category.show');
    Route::put('category', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('category/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');


    //for report printing
    Route::middleware(['eligible:view reports'])->group(function () {
        Route::get('/allReports', [ReportPrintController::class, 'all'])->name('reports.all');
    });
    Route::get('/reports', [ReportPrintController::class, 'index'])->name('reports.index');
    Route::get('/filterReports', [ReportPrintController::class, 'filterReports'])->name('filterReports');
    Route::post('/reports', [ReportPrintController::class, 'generateReport'])->name('reports.generate');

    // Route::middleware(['eligible:get sms'])->group(function () {
    //     Route::post('/send-sms', [SmsPush::class, 'sendSmsNotification'])->name('send-sms');
    //     Route::get('/send-sms', [SmsPush::class, 'sendSmsNotification'])->name('send-sms');
    // });

    // expenses controller
    // Homepage should redirect to expenses index
    Route::get('/', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    //vendors
    Route::get('expenses/vendors', [ExpenseController::class, 'vendors'])->name('expenses.vendors');
    Route::post('expenses/vendors', [ExpenseController::class, 'storeVendor'])->name('vendors.store');
    Route::put('expenses/vendors/{id}', [ExpenseController::class, 'updateVendor'])->name('vendors.update');
    Route::delete('expenses/vendors/{id}', [ExpenseController::class, 'destroyVendor'])->name('vendors.destroy');

    //categories
    Route::get('expenses/category', [ExpenseController::class, 'category'])->name('expenses.category');
    Route::post('expenses/category', [ExpenseController::class, 'storeCategory'])->name('category.store');
    Route::put('expenses/category/{id}', [ExpenseController::class, 'updateCategory'])->name('category.update');
    Route::delete('expenses/category/{id}', [ExpenseController::class, 'destroyCategory'])->name('category.destroy');
    Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
});

Route::middleware(['auth'])->group(function () {
    // Route::resource('dashboard', DashboardController::class);
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales/filter/{duration}', [DashboardController::class, 'filterSales'])->name('sales.filter');


    Route::get('/contracts', [ContractController::class, 'indexSuperAdmin'])->name('contracts');
    Route::get('/contracts/create', [ContractController::class, 'createSuperAdmin'])->name('contracts.admin.create');
    Route::post('/contracts', [ContractController::class, 'storeSuperAdmin'])->name('contracts.admin.store');
    Route::get('/contracts/{id}', [ContractController::class, 'showSuperAdmin'])->name('contracts.admin.show');
    Route::get('/contracts/{id}/edit', [ContractController::class, 'editSuperAdmin'])->name('contracts.admin.edit');
    Route::put('/contracts/{id}', [ContractController::class, 'updateSuperAdmin'])->name('contracts.admin.update');
    Route::get('/contracts/{id}/confirm', [ContractController::class, 'confirm'])->name('contracts.admin.confirm');
    Route::get('/contracts/{id}/grace', [ContractController::class, 'grace'])->name('contracts.admin.grace');

    Route::get('myContracts', [ContractController::class, 'indexUser'])->name('myContracts');
    Route::get('myContracts/{id}', [ContractController::class, 'showUser'])->name('contracts.users.show');
    Route::get('contracts/users/upgrade', [ContractController::class, 'upgrade'])->name('contracts.users.upgrade');
    Route::get('contracts/users/subscribe', [ContractController::class, 'subscribe'])->name('contracts.users.subscribe');
    Route::get('/contracts/users/activate', [ContractController::class, 'activate'])->name('contracts.users.activate');
    Route::get('/contracts/users/renew', [ContractController::class, 'renew'])->name('contracts.users.renew');

    Route::get('/update-contracts', [ContractUpdateController::class, 'updateContracts'])->name('update.contracts');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/readAll', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/notifications/unread_count', function () {
        return response()->json([
            'unreadCount' => Auth::user()->unreadNotifications->count()
        ]);
    });


    Route::get('/select', [SelectPharmacyController::class, 'show'])->name('pharmacies.selection');
    Route::get('/switch', [SelectPharmacyController::class, 'switch'])->name('pharmacies.switch');
    Route::post('/select', [SelectPharmacyController::class, 'set'])->name('pharmacies.set');


    // store printer settings
    Route::post('/printer', [DashboardController::class, 'storePrinterSettings'])->name('printer.store');
    // Update printer use status
    Route::post('/printer/update-status', [DashboardController::class, 'updatePrinterStatus'])->name('printer.updateStatus');


    //routes for sales notes
    Route::get('salesNotes', [SaleNoteController::class, 'index'])->name('salesNotes');
    Route::get('salesNotes/create', [SaleNoteController::class, 'createSalesNotes'])->name('salesNotes.create');
    Route::post('salesNotes', [SaleNoteController::class, 'storeSalesNotes'])->name('salesNotes.store');
    Route::get('salesNotes/{id}', [SaleNoteController::class, 'showSalesNotes'])->name('salesNotes.show');
    Route::put('salesNotes', [SaleNoteController::class, 'update'])->name('salesNotes.update');
    Route::delete('salesNotes/delete/{id}', [SaleNoteController::class, 'destroySalesNotes'])->name('salesNotes.destroy');
    Route::get('salesNotes/{id}/edit', [SaleNoteController::class, 'editSalesNotes'])->name('salesNotes.edit');
    //Promote sales Notes to sales
    Route::post('salesNotes/promote', [SaleNoteController::class, 'promoteSalesNotes'])->name('salesNotes.promote');
    Route::post('salesNotes/promoteAsOne', [SaleNoteController::class, 'promoteSalesNotesAsOne'])->name('salesNotes.promoteAsOne');


    // Agent routes
    Route::get('agent/complete-registration', [AgentController::class, 'completeRegistration'])->name('agent.completeRegistration');
    Route::post('agent/complete-registration', [AgentController::class, 'completeRegistration'])->name('agent.completeRegistration');

    Route::middleware(['eligible:registered'])->group(function () {

        Route::get('agent/messages', [AgentController::class, 'messages'])->name('agent.messages');
        Route::post('agent/messages', [AgentController::class, 'messages'])->name('agent.messages');

        Route::get('agent/cases', [AgentController::class, 'cases'])->name('agent.cases');
        Route::post('agent/cases', [AgentController::class, 'cases'])->name('agent.cases');

        Route::get('agent/pharmacies', [AgentController::class, 'pharmacies'])->name('agent.pharmacies');
        Route::post('agent/pharmacies/create', [AgentController::class, 'pharmacies'])->name('agent.pharmacies.store');
        Route::put('agent/pharmacies/{id}', [AgentController::class, 'pharmacies'])->name('agent.pharmacies.update');
        Route::delete('agent/pharmacies/{id}', [AgentController::class, 'pharmacies'])->name('agent.pharmacies.destroy');

        Route::get('agent/packages', [AgentController::class, 'packages'])->name('agent.packages');
        Route::post('agent/packages', [AgentController::class, 'packages'])->name('agent.packages.manage');

        Route::get('agent/contracts', [AgentController::class, 'contracts'])->name('agent.contracts');
        Route::post('agent/contracts', [AgentController::class, 'contracts'])->name('agent.contracts');
    });

    // AUDITS
    Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
    // get base64 image
    Route::get('/get_logo', [AuditController::class, 'getImage'])->name('audits.getImage');
});

Route::middleware(['auth', 'eligible:create pharmacy'])->group(function () {
    Route::post('pharmacies', [PharmacyController::class, 'store'])->name('pharmacies.store');
});

// expenses controller
Route::middleware(['auth'])->group(function () {
    // Homepage should redirect to expenses index
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    //vendors
    Route::get('expenses/vendors', [ExpenseController::class, 'vendors'])->name('expenses.vendors');
    Route::post('expenses/vendors', [ExpenseController::class, 'storeVendor'])->name('vendors.store');
    Route::put('expenses/vendors/{id}', [ExpenseController::class, 'updateVendor'])->name('vendors.update');
    Route::delete('expenses/vendors/{id}', [ExpenseController::class, 'destroyVendor'])->name('vendors.destroy');

    //categories
    Route::get('expenses/category', [ExpenseController::class, 'category'])->name('expenses.category');
    Route::post('expenses/category', [ExpenseController::class, 'storeCategory'])->name('category.store');
    Route::put('expenses/category/{id}', [ExpenseController::class, 'updateCategory'])->name('category.update');
    Route::delete('expenses/category/{id}', [ExpenseController::class, 'destroyCategory'])->name('category.destroy');

    // Full CRUD routes for expenses
    // Route::resource('expenses', ExpenseController::class);

    // Approve & Reject actions
    Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])
        ->name('expenses.approve');

    Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject'])
        ->name('expenses.reject');
});

Route::post('/contact-us', [ContactController::class, 'send'])->name('contact.send');
