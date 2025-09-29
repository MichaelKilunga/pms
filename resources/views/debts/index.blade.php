@extends('debts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Stock Debts</h1>
        <!-- Add Debt Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDebtModal">Add Debt</button>
    </div>

    <table class="table table-bordered table-triped mt-3" id="Table">
        <thead>
            <tr>
                <th>SN</th>
                <th>Medicine Stock</th>
                <th>Debt Amout</th>
                <th>Remaining Amount</th>
                <th>Total Paid</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($debts as $debt)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $debt->stock->item->name }}({{ $debt->stock->batch_number }}-{{ $debt->stock->supplier }})</td>
                    <td>{{ number_format($debt->debtAmount) }}</td>
                    <td>{{ number_format($debt->debtAmount - $debt->installments->sum('amount')) }}</td>
                    <td>{{ number_format($debt->totalPaid()) }}</td>
                    <td>{{ $debt->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $debt->updated_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $debt->status }}</td>
                    <td>
                     
                        <div class="d-flex justify-content-between align-items-center gap-2">

                            {{-- Case 1: Debt fully paid --}}
                            @if ($debt->debtAmount - $debt->installments->sum('amount') == 0)
                                {{-- No installments allowed, Edit disabled, Delete disabled if anything paid --}}
                                <button class="btn btn-primary btn-sm" disabled>
                                    <span class="bi bi-cash"></span>
                                </button>
                                <button class="btn btn-success btn-sm" disabled>
                                    <span class="bi bi-pencil"></span>
                                </button>
                                <button type="submit" class="btn btn-danger btn-sm" disabled>
                                    <span class="bi bi-trash"></span>
                                </button>

                                {{-- Case 2: Debt partially paid --}}
                            @elseif ($debt->debtAmount - $debt->installments->sum('amount') > 0 && $debt->totalPaid() > 0)
                                {{-- Installments allowed, Edit disabled, Delete disabled --}}
                                <button class="btn btn-primary btn-sm add-installment-btn"
                                    data-debt-id="{{ $debt->id }}" data-debt-quantity="{{ $debt->debtAmount }}"
                                    data-total-paid="{{ $debt->totalPaid() }}" data-bs-toggle="modal"
                                    data-bs-target="#addInstallmentModal{{ $debt->id }}">
                                    <span class="bi bi-cash"></span>
                                </button>
                                <button class="btn btn-success btn-sm" disabled>
                                    <span class="bi bi-pencil"></span>
                                </button>
                                <button type="submit" class="btn btn-danger btn-sm" disabled>
                                    <span class="bi bi-trash"></span>
                                </button>

                                {{-- Case 3: New debt (nothing paid yet) --}}
                            @elseif ($debt->totalPaid() == 0)
                                {{-- Installments allowed, Edit enabled, Delete enabled --}}
                                <button class="btn btn-primary btn-sm add-installment-btn"
                                    data-debt-id="{{ $debt->id }}" data-debt-quantity="{{ $debt->debtAmount }}"
                                    data-total-paid="{{ $debt->totalPaid() }}" data-bs-toggle="modal"
                                    data-bs-target="#addInstallmentModal{{ $debt->id }}">
                                    <span class="bi bi-cash"></span>
                                </button>
                                <button class="btn btn-success btn-sm" data-debt-id="{{ $debt->id }}"
                                    data-debt-quantity="{{ $debt->debtAmount }}"
                                    data-total-paid="{{ $debt->totalPaid() }}" data-bs-toggle="modal"
                                    data-bs-target="#EditDebt{{ $debt->id }}">
                                    <span class="bi bi-pencil"></span>
                                </button>
                                <form action="{{ route('debts.destroy', $debt->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this debt?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <span class="bi bi-trash"></span>
                                    </button>
                                </form>
                            @endif

                        </div>

                        <!-- Add Installment Modal -->
                        <div class="modal fade" id="addInstallmentModal{{ $debt->id }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form id="addInstallmentForm" method="POST" action="{{ route('debts.inststore') }}">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="debt_id" id="installmentDebtId">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Add Installment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mt-2">Stock Details:
                                                <span>{{ $debt->stock->item->name }}({{ $debt->stock->batch_number }}-{{ $debt->stock->supplier }})</span>
                                            </p>
                                            <hr />
                                            <p class="mt-2">Debt Amount:
                                                <span>{{ number_format($debt->debtAmount) }}</span>
                                            </p>
                                            <hr />
                                            <p class="mt-2">Total Paid:
                                                <span>{{ number_format($debt->totalPaid()) }}</span>
                                            </p>
                                            <hr />
                                            <div class="form-group mt-2">
                                                <label>Remainig Amount: </label>
                                                <input type="number" name="debt_id" class="form-control"
                                                    value="{{ $debt->id }}" hidden>
                                                <span
                                                    class="text-danger fw-bold">{{ number_format($debt->debtAmount - $debt->installments->sum('amount')) }}</span>
                                                </p>
                                                <hr />
                                            </div>
                                            <div class="form-group mt-2">
                                                <label>Description</label>
                                                <textarea class="form-control" name="description" placeholder="Enter installation description..."></textarea>
                                            </div>
                                            <div class="form-group mt-2">
                                                <label>Amount</label>
                                                <input type="number" name="amount" class="form-control" min="1"
                                                    max="{{ $debt->debtAmount - $debt->installments->sum('amount') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="modal-footer d-flex justify-content-between">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary"> Save </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Add Edit Debt Modal -->
                        <div class="modal fade" id="EditDebt{{ $debt->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('debts.update', $debt->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="debt_id" id="installmentDebtId">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Edit Debts</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-group mt-2 mb-3">
                                                <label>Select Stock</label>
                                                <select name="stock_id" class="form-control" required>
                                                    {{-- <option value="">-- Select Stock --</option> --}}
                                                    <option value="{{ $debt->stock->id }}" selected>
                                                        {{ $debt->stock->item->name }}({{ $debt->stock->batch_number }}-{{ $debt->stock->supplier }})
                                                    </option>
                                                    @foreach ($stocks as $stock)
                                                        <option value="{{ $stock->id }}"
                                                            data-max="{{ $stock->buying_price * $stock->quantity }}"
                                                            {{ $stock->id == $debt->stock_id ? 'selected' : '' }}>
                                                            {{ $stock->item->name }} ({{ $stock->batch_number }} -
                                                            {{ $stock->supplier }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group mt-2">
                                                <label>Amount</label>
                                                <input type="number" name="debt_id" class="form-control"
                                                    value="{{ $debt->id }}" hidden>
                                                <input type="number" name="amount" class="form-control" min="1"
                                                    value="{{ $debt->debtAmount }}" required id="debtAmount2">
                                                <small class="text-muted maxHint"></small>
                                                <div class="text-danger mt-1 amountError" style="display:none;"></div>
                                            </div>

                                        </div>

                                        <div class="modal-footer d-flex justify-content-between">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary"> Save </button>
                                        </div>
                                    </div>
                            </div>
                            </form>
                        </div>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Add Debt Modal -->
    <div class="modal fade" id="addDebtModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="debtForm" method="POST" action="{{ route('debts.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Debt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Stock</label>
                            <select id="Select2" name="stock_id" class="form-control Select2 chosen select" required>
                                <option value="" disabled selected>-- Select Stock --</option>
                                @foreach ($stocks as $stock)
                                    <option value="{{ $stock->id }}"
                                        data-max="{{ $stock->buying_price * $stock->quantity }}">
                                        {{ $stock->supplier }} - Batch {{ $stock->batch_number }}
                                        ({{ $stock->item->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label>Debt Amount</label>
                            <input type="number" id="debtAmount" name="debtAmount" class="form-control" step="0.001"
                                min="1" required>
                            <small id="maxHint" class="text-muted"></small>
                            <div id="amountError" class="text-danger mt-1" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"> Save </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // initialize select2
            $('.Select2').select2({
                dropdownParent: $('#addDebtModal'),
                width: "100%",
                minimumResultsForSearch: 5,
            }, ).on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });

            // function to set maxmum debt amount based on selected stock
            $('#Select2').change(function() {
                let maxAmount = $(this).find(':selected').data('max');
                $('#debtAmount').attr('max', maxAmount);
                $('#maxHint').text('Maximum debt amount for selected stock is ' + maxAmount
                    .toLocaleString());
                $('#amountError').hide();
            });

            // ========================
            // Edit Debt Modals
            // ========================
            $('select[name="stock_id"]').on('change', function() {
                let maxAmount = $(this).find(':selected').data('max');
                let modal = $(this).closest('.modal'); // find current modal
                modal.find('input[name="amount"]').attr('max', maxAmount);
                modal.find('.maxHint').text('Maximum debt amount for selected stock is ' + maxAmount
                    .toLocaleString());
                modal.find('.amountError').hide();
            });

            // Prefill installment modal
            $('.add-installment-btn').click(function() {
                let debtId = $(this).data('debt-id');
                let debtQty = $(this).data('debt-quantity');
                let totalPaid = $(this).data('total-paid');

                $('#installmentDebtId').val(debtId);
                $('#installmentDebtQuantity').text(debtQty);
                $('#installmentTotalPaid').text(totalPaid);
            });
        });
    </script>
@endsection
