@extends("debts.app")

@section("content")
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Stock Debts</h1>
        <!-- Add Debt Button -->
        <button class="btn btn-primary mb-3" data-bs-target="#addDebtModal" data-bs-toggle="modal">Add Debt</button>
    </div>

    <table class="table-bordered table-triped small mt-3 table" id="Table">
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
                    <td>{{ number_format($debt->debtAmount - $debt->installments->sum("amount")) }}</td>
                    <td>{{ number_format($debt->totalPaid()) }}</td>
                    <td>{{ $debt->created_at->format("Y-m-d H:i") }}</td>
                    <td>{{ $debt->updated_at->format("Y-m-d H:i") }}</td>
                    <td>{{ $debt->status }}</td>
                    <td>

                        <div class="d-flex justify-content-between align-items-center gap-2">

                            {{-- Case 1: Debt fully paid --}}
                            @if ($debt->debtAmount - $debt->installments->sum("amount") == 0)
                                {{-- No installments allowed, Edit disabled, Delete disabled if anything paid --}}
                                <button class="btn btn-primary btn-sm" disabled>
                                    <span class="bi bi-cash"></span>
                                </button>
                                <button class="btn btn-success btn-sm" disabled>
                                    <span class="bi bi-pencil"></span>
                                </button>
                                <button class="btn btn-danger btn-sm" disabled type="submit">
                                    <span class="bi bi-trash"></span>
                                </button>

                                {{-- Case 2: Debt partially paid --}}
                            @elseif ($debt->debtAmount - $debt->installments->sum("amount") > 0 && $debt->totalPaid() > 0)
                                {{-- Installments allowed, Edit disabled, Delete disabled --}}
                                <button class="btn btn-primary btn-sm add-installment-btn"
                                    data-bs-target="#addInstallmentModal{{ $debt->id }}" data-bs-toggle="modal"
                                    data-debt-id="{{ $debt->id }}" data-debt-quantity="{{ $debt->debtAmount }}"
                                    data-total-paid="{{ $debt->totalPaid() }}">
                                    <span class="bi bi-cash"></span>
                                </button>
                                <button class="btn btn-success btn-sm" disabled>
                                    <span class="bi bi-pencil"></span>
                                </button>
                                <button class="btn btn-danger btn-sm" disabled type="submit">
                                    <span class="bi bi-trash"></span>
                                </button>

                                {{-- Case 3: New debt (nothing paid yet) --}}
                            @elseif ($debt->totalPaid() == 0)
                                {{-- Installments allowed, Edit enabled, Delete enabled --}}
                                <button class="btn btn-primary btn-sm add-installment-btn"
                                    data-bs-target="#addInstallmentModal{{ $debt->id }}" data-bs-toggle="modal"
                                    data-debt-id="{{ $debt->id }}" data-debt-quantity="{{ $debt->debtAmount }}"
                                    data-total-paid="{{ $debt->totalPaid() }}">
                                    <span class="bi bi-cash"></span>
                                </button>
                                <button class="btn btn-success btn-sm" data-bs-target="#EditDebt{{ $debt->id }}"
                                    data-bs-toggle="modal" data-debt-id="{{ $debt->id }}"
                                    data-debt-quantity="{{ $debt->debtAmount }}"
                                    data-total-paid="{{ $debt->totalPaid() }}">
                                    <span class="bi bi-pencil"></span>
                                </button>
                                <form action="{{ route("debts.destroy", $debt->id) }}" class="d-inline" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this debt?');">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-danger btn-sm" type="submit">
                                        <span class="bi bi-trash"></span>
                                    </button>
                                </form>
                            @endif

                        </div>

                        <!-- Add Installment Modal -->
                        <div aria-hidden="true" class="modal fade" id="addInstallmentModal{{ $debt->id }}"
                            tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route("debts.inststore") }}" id="addInstallmentForm" method="POST">
                                    @csrf
                                    @method("POST")
                                    <input id="installmentDebtId" name="debt_id" type="hidden">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Add Installment</h5>
                                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
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
                                                <input class="form-control" hidden name="debt_id" type="number"
                                                    value="{{ $debt->id }}">
                                                <span
                                                    class="text-danger fw-bold">{{ number_format($debt->debtAmount - $debt->installments->sum("amount")) }}</span>
                                                </p>
                                                <hr />
                                            </div>
                                            <div class="form-group mt-2">
                                                <label>Description</label>
                                                <textarea class="form-control" name="description" placeholder="Enter installation description..."></textarea>
                                            </div>
                                            <div class="form-group mt-2">
                                                <label>Amount</label>
                                                <input class="form-control"
                                                    max="{{ $debt->debtAmount - $debt->installments->sum("amount") }}"
                                                    min="1" name="amount" required type="number">
                                            </div>
                                        </div>
                                        <div class="modal-footer d-flex justify-content-between">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal"
                                                type="button">Cancel</button>
                                            <button class="btn btn-primary" type="submit"> Save </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Add Edit Debt Modal -->
                        <div aria-hidden="true" class="modal fade" id="EditDebt{{ $debt->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route("debts.update", $debt->id) }}" method="POST">
                                    @csrf
                                    @method("PUT")

                                    <input id="installmentDebtId" name="debt_id" type="hidden">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Edit Debts</h5>
                                            <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-group mb-3 mt-2">
                                                <label>Select Stock</label>
                                                <select class="form-control" name="stock_id" required>
                                                    {{-- <option value="">-- Select Stock --</option> --}}
                                                    <option selected value="{{ $debt->stock->id }}">
                                                        {{ $debt->stock->item->name }}({{ $debt->stock->batch_number }}-{{ $debt->stock->supplier }})
                                                    </option>
                                                    @foreach ($stocks as $stock)
                                                        <option {{ $stock->id == $debt->stock_id ? "selected" : "" }}
                                                            data-max="{{ $stock->buying_price * $stock->quantity }}"
                                                            value="{{ $stock->id }}">
                                                            {{ $stock->item->name }} ({{ $stock->batch_number }} -
                                                            {{ $stock->supplier }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group mt-2">
                                                <label>Amount</label>
                                                <input class="form-control" hidden name="debt_id" type="number"
                                                    value="{{ $debt->id }}">
                                                <input class="form-control" id="debtAmount2" min="1"
                                                    name="amount" required type="number"
                                                    value="{{ $debt->debtAmount }}">
                                                <small class="text-muted maxHint"></small>
                                                <div class="text-danger amountError mt-1" style="display:none;"></div>
                                            </div>

                                        </div>

                                        <div class="modal-footer d-flex justify-content-between">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal"
                                                type="button">Cancel</button>
                                            <button class="btn btn-primary" type="submit"> Save </button>
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
    <div aria-hidden="true" class="modal fade" id="addDebtModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route("debts.store") }}" id="debtForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Debt</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Stock</label>
                            <select class="form-control Select2 chosen select" id="Select2" name="stock_id" required>
                                <option disabled selected value="">-- Select Stock --</option>
                                @foreach ($stocks as $stock)
                                    <option data-max="{{ $stock->buying_price * $stock->quantity }}"
                                        value="{{ $stock->id }}">
                                        {{ $stock->supplier }} - Batch {{ $stock->batch_number }}
                                        ({{ $stock->item->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label>Debt Amount</label>
                            <input class="form-control" id="debtAmount" min="1" name="debtAmount" required
                                step="0.001" type="number">
                            <small class="text-muted" id="maxHint"></small>
                            <div class="text-danger mt-1" id="amountError" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                        <button class="btn btn-primary" type="submit"> Save </button>
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
