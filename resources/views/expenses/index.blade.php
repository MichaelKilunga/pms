@extends('expenses.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><i class="fas fa-wallet me-2"></i> Expenses</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerExpenseModal">
                <i class="fas fa-plus-circle me-1"></i> New Expense
            </button>
        </div>

        <!-- Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('expenses.index') }}" class="row gy-2 gx-3 align-items-center">
                    <div class="col-md-2">
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control"
                            placeholder="From Date">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control"
                            placeholder="To Date">
                    </div>
                    <div class="col-md-2">
                        <select name="pharmacy_id" class="form-select" required readonly>
                            <option value="">All Pharmacies</option>
                            @foreach ($pharmacies as $pharmacy)
                                <option selected value="{{ $pharmacy->id }}" @selected(request('pharmacy_id') == $pharmacy->id)>
                                    {{ $pharmacy->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                            <option value="approved" @selected(request('status') == 'approved')>Approved</option>
                            <option value="rejected" @selected(request('status') == 'rejected')>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-search"></i>
                            Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Expenses Table -->
        <div class="row">
            <div class="card shadow-sm">
                <div class="card-body table-responsive">
                    <table id="Table" class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Pharmacy</th>
                                <th>Category</th>
                                <th>Vendor</th>
                                <th>Payment</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Approved By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $expense->expense_date->format('d M Y') }}</td>
                                    <td>{{ $expense->pharmacy->name ?? '-' }}</td>
                                    <td>{{ $expense->category->name ?? '-' }}</td>
                                    <td>{{ $expense->vendor->name ?? '-' }}</td>
                                    <td><span
                                            class="badge bg-info text-dark">{{ ucfirst($expense->payment_method) }}</span>
                                    </td>
                                    <td><strong>{{ number_format($expense->amount, 2) }}</strong> {{ $expense->currency }}
                                    </td>
                                    <td>
                                        @if ($expense->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($expense->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $expense->creator->name ?? 'N/A' }}</td>
                                    <td>{{ $expense->approver?->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="group-item">
                                            @if ($expense->status == 'pending' && ($users->role == 'owner' || $users->role == 'admin'))
                                                <button type="button" class="btn btn-sm btn-info view-expense"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewExpenseModal{{ $expense->id }}"
                                                    data-expense='@json($expense)'><i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning edit-expense"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editExpenseModal{{ $expense->id }}"
                                                    data-expense='@json($expense)'><i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                                                    class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                                <form action="{{ route('expenses.approve', $expense->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to accept?')">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-sm btn-success view-expense"><i
                                                            class="bi bi-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('expenses.reject', $expense->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to reject?')">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-sm btn-danger view-expense"><i
                                                            class="bi bi-x"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($expense->status == 'pending' && $users->role == 'staff')
                                                <button type="button" class="btn btn-sm btn-info view-expense"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewExpenseModal{{ $expense->id }}"
                                                    data-expense='@json($expense)'><i class="bi bi-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning edit-expense"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editExpenseModal{{ $expense->id }}"
                                                    data-expense='@json($expense)'><i
                                                        class="bi bi-pencil"></i>
                                                </button>
                                                <form action="{{ route('expenses.destroy', $expense->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            @endif

                                            @if ($expense->status == 'approved' && ($users->role == 'staff' || $users->role == 'admin' || $users->role == 'owner'))
                                                <button type="button" class="btn btn-sm btn-info view-expense"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewExpenseModal{{ $expense->id }}"
                                                    data-expense='@json($expense)'><i class="bi bi-eye"></i>
                                                </button>
                                            @endif

                                            @if ($expense->status == 'rejected' && ($users->role == 'staff' || $users->role == 'admin' || $users->role == 'owner'))
                                                <button type="button" class="btn btn-sm btn-info view-expense"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewExpenseModal{{ $expense->id }}"
                                                    data-expense='@json($expense)'><i class="bi bi-eye"></i>
                                                </button>
                                                <form action="{{ route('expenses.destroy', $expense->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            @endif
                                        </div>

                                    </td>
                                </tr>
                                <!-- View Expense Modal -->
                                <div class="modal fade" id="viewExpenseModal{{ $expense->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-cash me-2"></i> Expense Details
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><strong>Pharmacy pharmacy:</strong> <span
                                                            id="modal-pharmacy"></span>{{ $expense->pharmacy->name }}</li>
                                                    <li class="list-group-item"><strong>Category:</strong> <span
                                                            id="modal-category"></span>{{ $expense->category->name }}</li>
                                                    <li class="list-group-item"><strong>Vendor:</strong> <span
                                                            id="modal-vendor"></span>{{ $expense->vendor->name }}</li>
                                                    <li class="list-group-item"><strong>Payment Method:</strong> <span
                                                            id="modal-payment"
                                                            class="badge bg-info text-dark"></span>{{ $expense->payment_method }}
                                                    </li>
                                                    <li class="list-group-item"><strong>Amount:</strong> <span
                                                            id="modal-amount"></span> <span id="modal-currency"></span>
                                                        {{ $expense->currency }} {{ $expense->amount }}
                                                    </li>
                                                    <li class="list-group-item"><strong>Date:</strong> <span
                                                            id="modal-expense-date"></span>{{ $expense->expense_date->format('d M Y') }}
                                                    </li>
                                                    <li class="list-group-item"><strong>Status:</strong> <span
                                                            id="modal-status"></span>
                                                        @if ($expense->status == 'approved')
                                                            <span class="badge bg-success">Approved</span>
                                                        @elseif($expense->status == 'rejected')
                                                            <span class="badge bg-danger">Rejected</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        @endif
                                                    </li>
                                                    <li class="list-group-item"><strong>Description:</strong> <span
                                                            id="modal-description"></span>
                                                        {{ $expense->description ?? '-' }}
                                                    </li>
                                                    <li class="list-group-item"><strong>Created At:</strong> <span
                                                            id="modal-created-at"></span> {{ $expense->created_at }}
                                                    </li>

                                                </ul>
                                            </div>
                                            <div class="modal-footer"><button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Expense Modal -->
                                <div class="modal fade" id="editExpenseModal{{ $expense->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form id="editExpenseForm" method="POST"
                                                action="{{ route('expenses.update', $expense->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Expense</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">

                                                        <div class="col-md-6">
                                                            <label class="form-label">pharmacy</label>
                                                            <select name="pharmacy_id" id="edit-pharmacy"
                                                                value="{{ $expense->pharmacy_id }}" class="form-select"
                                                                required>
                                                                <option value="">Select pharmacy</option>
                                                                @foreach ($pharmacies as $pharmacy)
                                                                    <option value="{{ $pharmacy->id }}"
                                                                        {{ $pharmacy->id == $expense->pharmacy_id ? 'selected' : '' }}>
                                                                        {{ $pharmacy->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Category</label>
                                                            <select name="category_id" id="edit-category"
                                                                class="form-select" required>
                                                                <option value="">Select Category</option>
                                                                @foreach ($categories as $cat)
                                                                    <option value="{{ $cat->id }}"
                                                                        {{ $cat->id == $expense->category_id ? 'selected' : '' }}>
                                                                        {{ $cat->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Expense Date</label>
                                                            <input type="date" name="expense_date"
                                                                id="edit-expense-date" class="form-control"
                                                                value="{{ $expense->expense_date->format('Y-m-d') }}"
                                                                required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Vendor</label>
                                                            <select name="vendor_id" id="edit-vendor"
                                                                class="form-select">
                                                                <option value="">Select Vendor</option>
                                                                @foreach ($vendors as $vendor)
                                                                    <option value="{{ $vendor->id }}"
                                                                        {{ $vendor->id == $expense->vendor_id ? 'selected' : '' }}>
                                                                        {{ $vendor->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Payment Method</label>
                                                            <select name="payment_method" id="edit-payment-method"
                                                                class="form-select" required>
                                                                <option value="cash"
                                                                    {{ $expense->payment_method == 'cash' ? 'selected' : '' }}>
                                                                    Cash</option>
                                                                <option value="mobile_money"
                                                                    {{ $expense->payment_method == 'mobile_money' ? 'selected' : '' }}>
                                                                    Mobile Money</option>
                                                                <option value="bank_transfer"
                                                                    {{ $expense->payment_method == 'bank_transfer' ? 'selected' : '' }}>
                                                                    Bank Transfer</option>
                                                                <option value="cheque"
                                                                    {{ $expense->payment_method == 'cheque' ? 'selected' : '' }}>
                                                                    Cheque</option>
                                                                <option value="other"
                                                                    {{ $expense->payment_method == 'other' ? 'selected' : '' }}>
                                                                    Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Amount</label>
                                                            <input type="number" step="0.01" name="amount"
                                                                id="edit-amount" class="form-control"
                                                                value="{{ $expense->amount }}" required>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="form-label">Description</label>
                                                            <textarea name="description" id="edit-description" class="form-control" rows="3">{{ $expense->description }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="fas fa-save me-1"></i> Update
                                                        Expense</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                           
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $expenses->withQueryString()->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Expense Modal -->
    <div class="modal fade" id="registerExpenseModal" tabindex="-1" aria-labelledby="registerExpenseLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerExpenseLabel"><i class="fas fa-wallet me-2"></i> New Expense
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Expense Date</label>
                                <input type="date" class="form-control" name="expense_date"
                                    value="{{ old('expense_date', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pharmacy</label>
                                <select name="pharmacy_id" class="form-select" required>
                                    <option value="">Select Pharmacy</option>
                                    @foreach ($pharmacies as $pharmacy)
                                        <option selected value="{{ $pharmacy->id }}">{{ $pharmacy->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vendor</label>
                                <select name="vendor_id" class="form-select">
                                    <option value="">Select Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="">Select Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control" name="amount"
                                    value="{{ old('amount') }}" required>
                            </div>
                            <div class="col-md-12 hidden">
                                <label for="attachment" class="form-label">Attachment (Any file) <span
                                        class="text-danger fw-italic">* Optional</span></label>
                                <input type="file" class="form-control" name="attachment"
                                    accept=".png,.jpg,.jpeg,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" rows="2" class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary"><i
                                class="fas fa-save me-1"></i> Save Expense</button></div>
                </form>
            </div>
        </div>
    </div>


@endsection


@push('scripts')
    <script>
        $(document).ready(function() {

            // Initialize DataTable
            $('#Table').DataTable({
                paging: false,
                searching: false,
                ordering: true,
                responsive: true
            });

            // View expense modal
            $('.view-expense').on('click', function() {
                let expense = $(this).data('expense');
                $('#modal-expense-date').text(new Date(expense.expense_date).toLocaleDateString());
                $('#modal-pharmacy').text(expense.pharmacy ? expense.pharmacy.name : '-');
                $('#modal-category').text(expense.category ? expense.category.name : '-');
                $('#modal-vendor').text(expense.vendor ? expense.vendor.name : '-');
                $('#modal-payment').text(expense.payment_method.charAt(0).toUpperCase() + expense
                    .payment_method.slice(1));
                $('#modal-amount').text(Number(expense.amount).toLocaleString());
                $('#modal-currency').text(expense.currency);
                let statusBadge = expense.status === 'approved' ?
                    '<span class="badge bg-success">Approved</span>' :
                    expense.status === 'rejected' ? '<span class="badge bg-danger">Rejected</span>' :
                    '<span class="badge bg-warning text-dark">Pending</span>';
                $('#modal-status').html(statusBadge);
                $('#modal-description').text(expense.description || '-');
            });

            // Edit expense modal
            $('.edit-expense').on('click', function() {
                let expense = $(this).data('expense');
                $('#editExpenseForm').attr('action', '/expenses/' + expense.id);
                $('#edit-expense-date').val(expense.expense_date);
                $('#edit-pharmacy').val(expense.branch_id);
                $('#edit-category').val(expense.category_id);
                $('#edit-vendor').val(expense.vendor_id);
                $('#edit-payment-method').val(expense.payment_method);
                $('#edit-amount').val(expense.amount);
                $('#edit-description').val(expense.description);
            });

        });
    </script>
@endpush
