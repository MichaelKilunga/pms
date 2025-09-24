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
                    <td>{{ $debt->status }}</td>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            @if ($debt->debtAmount - $debt->installments->sum('amount') == 0)
                                <button class="btn btn-primary btn-sm add-installment-btn" data-debt-id="{{ $debt->id }}"
                                    data-debt-quantity="{{ $debt->debtAmount }}"
                                    data-total-paid="{{ $debt->totalPaid() }}" data-bs-toggle="modal"
                                    data-bs-target="#addInstallmentModal{{ $debt->id }}" disabled>
                                    <span class="bi bi-cash"></span>
                                </button>
                            @endif

                            @if ($debt->debtAmount - $debt->installments->sum('amount') > 0)
                                <button class="btn btn-primary btn-sm add-installment-btn"
                                    data-debt-id="{{ $debt->id }}" data-debt-quantity="{{ $debt->debtAmount }}"
                                    data-total-paid="{{ $debt->totalPaid() }}" data-bs-toggle="modal"
                                    data-bs-target="#addInstallmentModal{{ $debt->id }}">
                                    <span class="bi bi-cash"></span>
                                </button>
                            @endif
                            @if($debt->totalPaid() > 0)
                            <form action="{{ route('debts.destroy', $debt->id) }}" method   ="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this debt?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" disabled><span
                                        class="bi bi-trash"></span></button>
                            </form>
                            @endif
                            @if($debt->totalPaid() == 0)
                                  <form action="{{ route('debts.destroy', $debt->id) }}" method   ="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this debt?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><span
                                        class="bi bi-trash"></span></button>
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
                                            <p class="mt-2">Stock Details: <span>{{ $debt->stock->item->name }}({{ $debt->stock->batch_number }}-{{ $debt->stock->supplier }})</span></p>
                                            <hr/>
                                            <p class="mt-2">Debt Amount: <span>{{ number_format($debt->debtAmount) }}</span></p>
                                            <hr/>
                                            <p class="mt-2">Total Paid: <span>{{ number_format($debt->totalPaid()) }}</span></p>
                                            <hr />
                                            <div class="form-group mt-2">
                                                <label>Remainig Amount</label>
                                                <input type="number" name="debt_id" class="form-control"
                                                    value="{{ $debt->id }}" hidden>
                                                <input type="number" name="amountRemain" class="form-control"
                                                    min="0"
                                                    value="{{ $debt->debtAmount - $debt->installments->sum('amount') }}"
                                                    readonly>
                                            </div>
                                            <div class="form-group mt-2">
                                                <label>Amount</label>
                                                <input type="number" name="amount" class="form-control" min="1"
                                                    max="{{ $debt->debtAmount - $debt->installments->sum('amount') }}"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="modal-footer d-flex justify-content-between">
                                            <button type="submit" class="btn btn-primary">Add Installment</button>
                                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
                        {{-- <div class="form-group mt-2">
                            <label>Stock Amount</label>
                            <input type="number" id="stockAmount" name="stockAmount" class="form-control" step="0.001" min="1" value="" @readonly(true)>
                            <div id="amountError" class="text-danger mt-1" style="display: none;"></div>
                        </div> --}}
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
                        <button type="submit" class="btn btn-primary">Save Debt</button>
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
            },
            ).on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });

            // function to set maxmum debt amount based on selected stock
            $('#Select2').change(function() {
                let maxAmount = $(this).find(':selected').data('max');
                $('#debtAmount').attr('max', maxAmount);
                $('#maxHint').text('Maximum debt amount for selected stock is ' + maxAmount.toLocaleString());
                $('#amountError').hide();
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
