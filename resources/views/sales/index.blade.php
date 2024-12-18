@extends('sales.app')

@section('content')
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary fw-bold">Sales Management</h2>
            <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createSalesModal">
                <i class="bi bi-plus-lg"></i> Add New Sales
            </a>
        </div>

        <!-- Sales Table -->
        <div class="table-responsive shadow-sm rounded-3">
            <table class="table table-striped table-hover table-bordered align-middle" id="Table">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Sales Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Amount</th> <!-- New column for calculated amount -->
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->item->name }}</td>
                            <td>{{ $sale->total_price }}</td>
                            <td>{{ $sale->quantity }}</td>
                            <td class="amount-cell">{{ $sale->total_price * $sale->quantity }}</td> <!-- Display calculated amount -->
                            <td>{{ $sale->date }}</td>
                            <td>
                                <!-- View Modal -->
                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#viewSaleModal{{ $sale->id }}">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <!-- View Sale Modal -->
                                <div class="modal fade" id="viewSaleModal{{ $sale->id }}" tabindex="-1"
                                    aria-labelledby="viewSaleModalLabel{{ $sale->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewSaleModalLabel{{ $sale->id }}">Sale
                                                    Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div><strong>Sales Name:</strong> {{ $sale->item->name }}</div>
                                                <div><strong>Price:</strong> {{ $sale->total_price }}</div>
                                                <div><strong>Quantity:</strong> {{ $sale->quantity }}</div>
                                                <div><strong>Amount:</strong> {{ $sale->total_price * $sale->quantity }}</div> <!-- Display amount here -->
                                                <div><strong>Date:</strong> {{ $sale->date }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editSaleModal{{ $sale->id }}">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <!-- Edit Sale Modal -->
                                <div class="modal fade" id="editSaleModal{{ $sale->id }}" tabindex="-1"
                                    aria-labelledby="editSaleModalLabel{{ $sale->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editSaleModalLabel{{ $sale->id }}">Edit
                                                    Sale</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="id" class="form-control"
                                                        value="{{ $sale->id }}" hidden readonly>
                                                    <div class="mb-3">
                                                        <label class="form-label">Sales Name</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $sale->item->name }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Price</label>
                                                        <input type="number" class="form-control" name="total_price"
                                                            value="{{ $sale->total_price }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Quantity</label>
                                                        <input type="number" class="form-control" name="quantity"
                                                            value="{{ $sale->quantity }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Date</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $sale->date }}" readonly>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Form -->
                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure to delete this sale?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Sales Modal -->
    <div class="modal fade" id="createSalesModal" tabindex="-1" aria-labelledby="createSalesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createSalesModalLabel">Add New Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sales.store') }}" method="POST" id="salesForm">
                        @csrf
                        <div id="salesFields">
                            <div class="row mb-3 sale-entry align-items-center">
                                <div class="col-md-3">
                                    <label class="form-label">Item</label>
                                    <select name="item_id[]" class="form-select" required>
                                        <option selected disabled value="">Select Item</option>
                                        @foreach ($medicines as $medicine)
                                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Price</label>
                                    <input type="number" class="form-control" placeholder="Price" name="total_price[]"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" class="form-control" placeholder="Quantity" name="quantity[]"
                                        required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Amount</label>
                                    <input type="number" class="form-control amount" placeholder="Amount" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" class="form-control date" name="date[]"  required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-8 text-end">
                                <strong>Total Amount:</strong>
                            </div>
                            <div class="col-md-4 text-end">
                                <input class="btn btn-outline-danger" id="totalAmount" value="0" disabled>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" id="addSaleRow" class="btn btn-outline-primary">
                                <i class="bi bi-plus-lg"></i> Add Row
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Save Sales
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to calculate Amount
            function calculateAmount(row) {
                const price = parseFloat(row.querySelector('[name="total_price[]"]').value) || 0;
                const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 0;
                const amount = price * quantity;
                row.querySelector('.amount').value = amount;

                // Update total amount
                updateTotalAmount();
            }

            // Update the total amount across all rows
            function updateTotalAmount() {
                let total = 0;
                document.querySelectorAll('.sale-entry').forEach(row => {
                    const amount = parseFloat(row.querySelector('.amount').value) || 0;
                    total += amount;
                });
                document.getElementById('totalAmount').value = total;
            }

            // Add Row Button
            document.getElementById('addSaleRow').addEventListener('click', function() {
                const salesFields = document.getElementById('salesFields');
                const newRow = document.createElement('div');
                newRow.classList.add('row', 'mb-3', 'sale-entry', 'align-items-center');

                newRow.innerHTML = `
                    <div class="col-md-3">
                        <select name="item_id[]" class="form-select" required>
                            <option selected disabled value="">Select Item</option>
                            @foreach ($medicines as $medicine)
                                <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="total_price[]" placeholder="Price" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="quantity[]" placeholder="Quantity" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control amount" placeholder="Amount" readonly>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="date form-control" name="date[]" required>
                    </div>
                    <div class="col-md-2 d-flex justify-content-center">
                        <button type="button" class="btn btn-danger btn-sm remove-sale-row">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                salesFields.appendChild(newRow);

                // Attach Remove Row Event to the New Button
                newRow.querySelector('.remove-sale-row').addEventListener('click', function() {
                    newRow.remove();
                    updateTotalAmount();
                });

                // Attach Calculation for the new row
                newRow.querySelector('[name="total_price[]"]').addEventListener('input', function() {
                    calculateAmount(newRow);
                });

                newRow.querySelector('[name="quantity[]"]').addEventListener('input', function() {
                    calculateAmount(newRow);
                });
            });

            // Initial Remove Row Buttons
            document.querySelectorAll('.remove-sale-row').forEach(button => {
                button.addEventListener('click', function() {
                    button.closest('.sale-entry').remove();
                    updateTotalAmount();
                });
            });

            // Initial Calculation for existing rows
            document.querySelectorAll('.sale-entry').forEach(row => {
                row.querySelector('[name="total_price[]"]').addEventListener('input', function() {
                    calculateAmount(row);
                });

                row.querySelector('[name="quantity[]"]').addEventListener('input', function() {
                    calculateAmount(row);
                });
            });
            document.getElementsByClassName('.date').value = new Date();
        });
    </script>

@endsection
