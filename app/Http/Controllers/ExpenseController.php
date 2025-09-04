<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Vendor;
use App\Models\Pharmacy; // assuming your branches model is Pharmacy
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    //**************** EXPENSES ****************** */
    /**
     * Display a listing of expenses.
     */

    //add try catch block to all methods
    public function index(Request $request)
    {
        try {
            $pharmacies = Pharmacy::where('id', session('current_pharmacy_id'))->get();
            $categories = ExpenseCategory::where('is_active', true)
                ->where('pharmacy_id', session('current_pharmacy_id'))
                ->get();

            $vendors = Vendor::where('is_active', true)
                ->where('pharmacy_id', session('current_pharmacy_id'))
                ->get();

            $users = Auth::user();

            //check if owner and pharmacy to call data
            $query = Expense::where('pharmacy_id', session('current_pharmacy_id'))->with(['pharmacy', 'category', 'vendor', 'creator']);

            //    if role is staff
            if ($users->role == 'staff') {
                $query->where('created_by', $users->id);
            }

            // Filtering
            if ($request->filled('from_date')) {
                $query->whereDate('expense_date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('expense_date', '<=', $request->to_date);
            }
            if ($request->filled('pharmacy_id')) {
                $query->where('pharmacy_id', $request->pharmacy_id);
            }
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $expenses = $query->latest()->paginate(10);


            return view('expenses.index', compact('expenses', 'pharmacies', 'categories', 'vendors', 'users'));
        } catch (\Exception $e) {
            // Log error ili uone kwenye storage/logs/laravel.log
            Log::error('Expenses Index Error: ' . $e->getMessage());

            return redirect()->route('expenses.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Show form to create a new expense.
     */
    public function create()
    {
        try {
            $branches = Pharmacy::all();
            $categories = ExpenseCategory::where('is_active', true)->get();
            $vendors = Vendor::where('is_active', true)->get();

            return view('expenses.create', compact('branches', 'categories', 'vendors'));
        } catch (\Exception $e) {
            Log::error('Expenses Create Error: ' . $e->getMessage());
            return redirect()->route('expenses.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Store a new expense.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'expense_date' => ['required', 'date'],
                'pharmacy_id' => ['required', 'exists:pharmacies,id'],
                'category_id' => ['required', 'exists:expense_categories,id'],
                'vendor_id' => ['nullable', 'exists:vendors,id'],
                'amount' => ['required', 'numeric', 'min:0'],
                'payment_method' => ['required', 'in:cash,mobile_money,bank_transfer,cheque,other'],
                'payment_reference' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
            ]);

            $validated['created_by'] = Auth::id();
            $validated['status'] = 'pending';

            $expense = Expense::create($validated);

            return redirect()->route('expenses.index')
                ->with('success', 'Expense recorded successfully.');
        } catch (\Exception $e) {
            Log::error('Expenses Store Error: ' . $e->getMessage());
            return redirect()->route('expenses.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Update an expense.
     */
    public function update(Request $request, Expense $expense)
    {
        try {
            if ($expense->status !== 'pending') {
                return redirect()->route('expenses.index')->with('error', 'Only pending expenses can be updated.');
            }

            $validated = $request->validate([
                'expense_date' => ['required', 'date'],
                'pharmacy_id' => ['required', 'exists:pharmacies,id'],
                'category_id' => ['required', 'exists:expense_categories,id'],
                'vendor_id' => ['nullable', 'exists:vendors,id'],
                'amount' => ['nullable', 'numeric', 'min:0'],
                'tax_amount' => ['nullable', 'numeric', 'min:0'],
                'total_amount' => ['nullable', 'numeric', 'min:0', 'gte:amount'],
                'currency' => ['nullable', 'string', 'size:3'],
                'payment_method' => ['required', 'in:cash,mobile_money,bank_transfer,cheque,other'],
                'payment_reference' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
            ]);

            $expense->update($validated);

            return redirect()->route('expenses.index')
                ->with('success', 'Expense updated successfully.');
        } catch (\Exception $e) {
            Log::error('Expenses Update Error: ' . $e->getMessage());
            return redirect()->route('expenses.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Delete an expense.
     */
    public function destroy(Expense $expense)
    {
        try {
            if ($expense->status === 'approved') {
                return redirect()->route('expenses.index')->with('error', 'Approved expenses cannot be deleted.');
            }

            $expense->delete();

            return redirect()->route('expenses.index')
                ->with('success', 'Expense deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Expenses Destroy Error: ' . $e->getMessage());
            return redirect()->route('expenses.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Approve an expense.
     */
    public function approve(Expense $expense)
    {
        try {
            if ($expense->status !== 'pending') {
                return redirect()->route('expenses.index')->with('error', 'Only pending expenses can be approved.');
            }

            $expense->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            return redirect()->route('expenses.index')
                ->with('success', 'Expense approved successfully.');
        } catch (\Exception $e) {
            Log::error('Expenses Approve Error: ' . $e->getMessage());
            return redirect()->route('expenses.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Reject an expense.
     */
    public function reject(Expense $expense)
    {
        try {
            if ($expense->status !== 'pending') {
                return redirect()->route('expenses.index')->with('error', 'Only pending expenses can be rejected.');
            }

            $expense->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            return redirect()->route('expenses.index')
                ->with('success', 'Expense rejected successfully.');
        } catch (\Exception $e) {
            Log::error('Expenses Reject Error: ' . $e->getMessage());
            return redirect()->route('expenses.index')->with('error', $e->getMessage());
        }
    }

    //**************** VENDOR ****************** */
    //vendors
    public function vendors()
    {
        try {
            $vendors = Vendor::where('pharmacy_id', session('current_pharmacy_id'))->get();
            return view('expenses.vendors', compact('vendors'));
        } catch (\Exception $e) {
            Log::error('Vendors Index Error: ' . $e->getMessage());
            return redirect()->route('expenses.index')->with('error', $e->getMessage());
        }
    }

    // Store a new vendor
    public function storeVendor(Request $request)
    {
        try {

            //assign session to request to be used in validation
            $request->merge(['pharmacy_id' => session('current_pharmacy_id')]);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string'],
                'email' => ['nullable', 'string'],
                'address' => ['nullable', 'string'],
                'tin' => ['nullable', 'string'],
                'city' => ['nullable', 'string'],
                'country' => ['nullable', 'string'],
                'is_active' => ['required', 'boolean'],
                'pharmacy_id' => ['required', 'integer', 'exists:pharmacies,id'],
            ]);

            Vendor::create($validated);

            return redirect()->route('expenses.vendors')
                ->with('success', 'Vendor added successfully.');
        } catch (\Exception $e) {
            Log::error('Vendor Add Error: ' . $e->getMessage());
            return redirect()->route('expenses.vendors')->with('error', $e->getMessage());
        }
    }

    // Update a vendor
    public function updateVendor(Request $request, $id)
    {
        try {
            $vendor = Vendor::findOrFail($id);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['nullable', 'string'],
                'email' => ['nullable', 'string'],
                'address' => ['nullable', 'string'],
                'tin' => ['nullable', 'string'],
                'city' => ['nullable', 'string'],
                'country' => ['nullable', 'string'],
                'is_active' => ['required', 'boolean'],
            ]);

            $vendor->update($validated);

            return redirect()->route('expenses.vendors')
                ->with('success', 'Vendor updated successfully.');
        } catch (\Exception $e) {
            Log::error('Vendor Update Error: ' . $e->getMessage());
            return redirect()->route('expenses.vendors')->with('error', $e->getMessage());
        }
    }
    // Delete a vendor
    public function destroyVendor($id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            $vendor->delete();

            return redirect()->route('expenses.vendors')
                ->with('success', 'Vendor deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Vendor Delete Error: ' . $e->getMessage());
            return redirect()->route('expenses.vendors')->with('error', $e->getMessage());
        }
    }

    /**************** CATEGORY ****************** */
    //expenses category
    public function category()
    {
        try {
            $categories = ExpenseCategory::where('pharmacy_id', session('current_pharmacy_id'))->get();
            return view('expenses.category', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Expense Category Index Error: ' . $e->getMessage());
            return redirect()->route('expenses.index')->with('error', $e->getMessage());
        }
    }

    // Store a new category
    public function storeCategory(Request $request)
    {
        try {
            //assign session to request to be used in validation
            $request->merge(['pharmacy_id' => session('current_pharmacy_id')]);

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'is_active' => ['required', 'boolean'],
                'pharmacy_id' => ['required', 'integer', 'exists:pharmacies,id'],
            ]);

            ExpenseCategory::create($validator->validated());

            return redirect()->route('expenses.category')
                ->with('success', 'Expense category added successfully.');
        } catch (\Exception $e) {
            Log::error('Expense Category Store Error: ' . $e->getMessage());
            return redirect()->route('expenses.category')->with('error', $e->getMessage());
        }
    }

    // Update a category
    public function updateCategory(Request $request, $id)
    {
        try {
            $category = ExpenseCategory::findOrFail($id);

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'is_active' => ['required', 'boolean'],
            ]);

            $category->update($validated);

            return redirect()->route('expenses.category')
                ->with('success', 'Expense category updated successfully.');
        } catch (\Exception $e) {
            Log::error('Expense Category Update Error: ' . $e->getMessage());
            return redirect()->route('expenses.category')->with('error', $e->getMessage());
        }
    }

    // Delete a category
    public function destroyCategory($id)
    {
        try {
            $category = ExpenseCategory::findOrFail($id);
            $category->delete();

            return redirect()->route('expenses.category')
                ->with('success', 'Expense category deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Expense Category Delete Error: ' . $e->getMessage());
            return redirect()->route('expenses.category')->with('error', $e->getMessage());
        }
    }
}
