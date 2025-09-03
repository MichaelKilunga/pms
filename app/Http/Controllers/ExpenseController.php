<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Vendor;
use App\Models\Pharmacy; // assuming your branches model is Pharmacy
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    //**************** EXPENSES ****************** */
    /**
     * Display a listing of expenses.
     */

    public function index(Request $request)
    {
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
    }

    /**
     * Show form to create a new expense.
     */
    public function create()
    {
        $branches = Pharmacy::all();
        $categories = ExpenseCategory::where('is_active', true)->get();
        $vendors = Vendor::where('is_active', true)->get();


        return view('expenses.create', compact('branches', 'categories', 'vendors'));
    }

    /**
     * Store a new expense.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        //validate form
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
    }

    /**
     * Update an expense.
     */
    public function update(Request $request, Expense $expense)
    {
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
    }

    /**
     * Delete an expense.
     */
    public function destroy(Expense $expense)
    {
        if ($expense->status === 'approved') {
            return redirect()->route('expenses.index')->with('error', 'Approved expenses cannot be deleted.');
        }

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    /**
     * Approve an expense.
     */
    public function approve(Expense $expense)
    {
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
    }

    /**
     * Reject an expense.
     */
    public function reject(Expense $expense)
    {
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
    }



    //**************** VENDOR ****************** */
    //vendors
    public function vendors()
    {
        $vendors = Vendor::where('pharmacy_id', session('current_pharmacy_id'))->get();
        return view('expenses.vendors', compact('vendors'));
    }

    // Store a new vendor
    public function storeVendor(Request $request)
    {

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
    }

    // Update a vendor
    public function updateVendor(Request $request, $id)
    {
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
    }
    // Delete a vendor
    public function destroyVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('expenses.vendors')
            ->with('success', 'Vendor deleted successfully.');
    }

    /**************** CATEGORY ****************** */
    //expenses category
    public function category()
    {
        $categories = ExpenseCategory::where('pharmacy_id', session('current_pharmacy_id'))->get();
        return view('expenses.category', compact('categories'));
    }

    // Store a new category
    public function storeCategory(Request $request)
    {
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
    }

    // Update a category
    public function updateCategory(Request $request, $id)
    {
        $category = ExpenseCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $category->update($validated);

        return redirect()->route('expenses.category')
            ->with('success', 'Expense category updated successfully.');
    }

    // Delete a category
    public function destroyCategory($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('expenses.category')
            ->with('success', 'Expense category deleted successfully.');
    }
}
