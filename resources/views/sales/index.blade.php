@extends('sales.app')

@section('content')
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary fs-2 fw-bold">Sales Management</h2>
            <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createSalesModal">
                <i class="bi bi-plus-lg"></i> New Sales
            </a>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <div></div>
            <div>
                <a href="print/lastReceipt" class="btn btn-success m-2">
                    <i class="bi bi-printer"></i> Last
                </a>
                <a href="allReceipts" class="btn btn-success m-2">
                    <i class="bi bi-receipt"></i> Receipts
                </a>
            </div>
        </div>
        <hr class="mb-2">

        <!-- Sales Table -->
        <div class="table-responsive shadow-sm rounded-3">
            <table class="table table-striped table-hover table-bordered align-middle" id="Table">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Sales Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        {{-- <th>Stock Id</th> --}}
                        <th>Total Price</th> <!-- New column for calculated amount -->
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sale->item->name }}</td>
                            <td>{{ $sale->total_price / $sale->quantity }}</td>
                            <td>{{ $sale->quantity }}</td>
                            {{-- <td>{{ $sale->stock_id }}</td> --}}
                            <td class="amount-cell">{{ $sale->total_price }}</td>
                            <!-- Display calculated amount -->
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
                                                <div><strong>Price:</strong> {{ $sale->total_price / $sale->quantity }}
                                                </div>
                                                <div><strong>Quantity:</strong> {{ $sale->quantity }}</div>
                                                <div><strong>Amount:</strong> {{ $sale->total_price }}
                                                </div> <!-- Display amount here -->
                                                <div><strong>Date:</strong> {{ $sale->date }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" hidden
                                    data-bs-target="#editSaleModal{{ $sale->id }}">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <!-- Edit Sale Modal -->
                                <div class="modal fade" hidden id="editSaleModal{{ $sale->id }}" tabindex="-1"
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
                                                        <label class="form-label">Total Price</label>
                                                        <input type="number" class="form-control" name="total_price"
                                                            value="{{ $sale->total_price }}" readonly required>
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
                                    <button type="submit" hidden disabled="true" class="btn btn-sm btn-danger"
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
    <div class="modal fade" id="createSalesModal" tabindex="-1" aria-labelledby="createSalesModalLabel"
        aria-hidden="true">
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
                                <input type="text" name="stock_id[]" hidden required>
                                <div class="col-md-4">
                                    <label for="medicines" class="form-label">Medicine</label>
                                    <select name="item_id[]" class="form-select chosen" required>
                                        <option selected value=""></option>
                                        @foreach ($medicines as $medicine)
                                            <option value="{{ $medicine->id }}">
                                                {{ $medicine->item->name }} <br><strong
                                                    class="text-danger">Exp:({{ \Carbon\Carbon::parse($medicine->expire_date)->format('m/Y') }})</strong>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Price(TZS)</label>
                                    <input type="text" class="form-control" placeholder="Price" name="total_price[]"
                                        value="0" readonly required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="label[]">Quantity</label>
                                    <input type="number" class="form-control" min="1"
                                        title="Only 10 has remained in stock!" placeholder="Quantity" name="quantity[]"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Amount</label>
                                    <input type="number" class="form-control amount" name="amount[]"
                                        placeholder="Amount" readonly>
                                </div>
                                <div class="col-md-0" hidden>
                                    <label class="form-label">Date</label>
                                    <input type="text" class="form-control date" name="date[]"
                                        value="{{ now() }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-8 text-end">
                                <strong>Total Amount:</strong>
                            </div>
                            <div class="col-md-3 text-end">
                                <input type="text" class="form-control text-danger" id="totalAmount" value="0"
                                    readonly>
                            </div>
                            <div class="col-md-1 text-end">
                                <!-- <input class="btn btn-outline-danger" id="totalAmount" value="0" disabled> -->
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="col-md-11 d-flex justify-content-between ">
                                <button type="button" id="addSaleRow" class="btn btn-outline-primary">
                                    <i class="bi bi-plus-lg"></i> Add Row
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Save Sales
                                </button>
                            </div>
                            <div class="col-md-1">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- A MODAL FOR SELECTING PRINTERS FROM THE LIST OF ACTIVE PRINTERS, MODAL SHOULD DETECT ACTIVE PRINTERS
         USING JS AND RETURN THEM AS A DROP-DOWN LIST, USER WILL SELECT THE PRINTER HE/SHE WANTS AND SUBMIT,
         USER SHOULD ONLY SELECT WHILE THE FORM SHOULD CATCH IP address and Printe's path using JS --}}
    {{-- Modal for Selecting Printers --}}
    @if (!session('printer'))
        <div class="modal fade" id="printerModal" tabindex="-1" aria-labelledby="printerModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="printerModalLabel">Select Printer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <form action="{{ route('printer.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="printer" class="form-label">Printer's Name</label>
                                <input type="text" class="form-control" id="printer" name="printer"
                                    placeholder="TM-20ll Receipt" required>
                            </div>
                            <div class="mb-3">
                                <label for="ip_address" class="form-label">IP Address</label>
                                <input type="text" class="form-control" id="ip_address" name="ip_address"
                                    placeholder="192.168.0.123" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Chosen for dynamically added rows
            function initializeChosen() {
                $(".chosen").chosen({
                    width: "100%",
                    no_results_text: "No matches found!",
                    allow_single_deselect: true,
                }).on("change", function() {
                    const row = $(this).closest(".sale-entry")[0];
                    tellPrice(row);
                    calculateAmount(row);
                });
            }

            // Reusable Functions
            function calculateAmount(row) {
                const price = parseFloat(row.querySelector('[name="total_price[]"]').value) || 0;
                const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 0;
                const amount = price * quantity;
                row.querySelector('.amount').value = amount;

                // Update total amount
                updateTotalAmount();
            }

            function tellPrice(row) {
                let medicines = @json($medicines); // Convert medicines to a JS array
                const selectedMedicineId = row.querySelector('[name="item_id[]"]').value;

                // Find the selected medicine
                const selectedMedicine = medicines.find(medicine => medicine.id == selectedMedicineId);

                row.querySelector('[name="stock_id[]"]').value = `${selectedMedicine.id}`;
                // console.log(selectedMedicine.id);

                if (selectedMedicine) {
                    // Set the total price to the medicine price (formatted with "TZS")
                    row.querySelector('[name="total_price[]"]').value = `${selectedMedicine.selling_price}`;
                    row.querySelector('[name="quantity[]"]').setAttribute('max',
                        `${selectedMedicine.remain_Quantity}`);

                    const labelElement = row.querySelector('[for="label[]"]');
                    // Clear any existing content in the labelElement
                    labelElement.innerHTML = '';
                    // Create a span element to hold the appended text
                    const appendedText = document.createElement('small');
                    // Set the text content and add the class
                    appendedText.innerHTML = 'In stock (&darr;' + selectedMedicine.remain_Quantity + ')';
                    if (selectedMedicine.remain_Quantity < selectedMedicine.low_stock_percentage) {
                        appendedText.classList.add('text-danger');
                    } else {
                        appendedText.classList.add('text-success');
                    }
                    // Append the span to the label element
                    labelElement.appendChild(appendedText);

                }
            }

            function updateTotalAmount() {
                let total = 0;
                document.querySelectorAll('.sale-entry').forEach(row => {
                    const amount = parseFloat(row.querySelector('.amount').value) || 0;
                    total += amount;
                });
                document.getElementById('totalAmount').value = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'TZS',
                }).format(total);
            }

            // Add Row Functionality
            document.getElementById('addSaleRow').addEventListener('click', function() {
                const salesFields = document.getElementById('salesFields');
                const newRow = document.createElement('div');
                newRow.classList.add('row', 'mb-3', 'sale-entry', 'align-items-center');

                newRow.innerHTML = `
                            <input type="text" name="stock_id[]" hidden required>
                            <div class="col-md-4">
                                <select name="item_id[]" data-row-id="item_id[]" class="form-select chosen" required>
                                    <option selected disabled value="">Select Item</option>
                                    @foreach ($medicines as $medicine)
                                        <option value="{{ $medicine->id }}">
                                                {{ $medicine->item->name }} <br><strong class="text-danger">Exp:({{ \Carbon\Carbon::parse($medicine->expire_date)->format('m/Y') }})</strong>
                                            </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" VALUE="0" name="total_price[]" readonly required>
                            </div>
                            <div class="col-md-2">
                                    <label class="form-label" for="label[]"></label>
                                <input type="number" class="form-control" name="quantity[]" min="1" placeholder="Quantity" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control amount" name="amount[]" placeholder="Amount" readonly>
                            </div>
                            <div class="col-md-0" hidden>
                            <input type="text" class="form-control date" name="date[]" value="{{ now() }}" required>
                            </div>
                            <div class="col-md-1 d-flex justify-content-center">
                                <button type="button" class="btn btn-danger btn-sm remove-sale-row">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                `;


                salesFields.appendChild(newRow);

                // Initialize Chosen for the new row
                initializeChosen();

                // Add Event Listeners for the new row
                newRow.querySelector('.remove-sale-row').addEventListener('click', function() {
                    newRow.remove();
                    updateTotalAmount();
                });

                newRow.querySelector('[name="quantity[]"]').addEventListener('input', function() {
                    calculateAmount(newRow);
                });
            });

            // Initial Setup for Existing Rows
            document.querySelectorAll('.sale-entry').forEach(row => {
                row.querySelector('[name="item_id[]"]').addEventListener('change', function() {
                    tellPrice(row);
                    calculateAmount(row);
                });

                row.querySelector('[name="quantity[]"]').addEventListener('input', function() {
                    calculateAmount(row);
                });
            });

            // Initialize Chosen on Page Load
            initializeChosen();
        });
    </script>
@endsection
