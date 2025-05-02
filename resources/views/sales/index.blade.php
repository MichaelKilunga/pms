@extends('sales.app')

@section('content')
    @php 
    // $medicines = App\Models\Stock::where('pharmacy_id', session('current_pharmacy_id'))->where('expire_date', '>', now())->where('remain_Quantity', '>', 0)->with('item')->get(); 
    $medicines = App\Models\Stock::select(
            // generate a new id for each row by concatenating item_id and selling_price
                            \DB::raw("item_id || selling_price as id"),
                            'item_id',
                            \DB::raw("SUM(remain_Quantity) as remain_Quantity"),
                            'selling_price'
                        )
                        ->where('pharmacy_id', session('current_pharmacy_id'))
                        ->where('expire_date', '>', now())
                        ->groupBy('item_id', 'selling_price')
                        ->with('item')
                        ->get();
    @endphp
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
            {{-- checkbox to enable use of printer --}}
            <div>
                <div class="form-control form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" {{ session('use_printer') ? 'checked' : '' }}
                        id="printerCheckbox">
                    <label class="form-check-label" for="printerCheckbox">Printer Enabled</label>
                </div>
            </div>
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
            <table class="table table-striped table-hover table-bordered align-middle" id="salesTable">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Sales Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
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
                                <input type="text" name="stock_id[]" hidden required>
                                <div class="col-md-4">
                                    <label for="medicines" class="form-label">Medicine</label>
                                    <select name="item_id[]" class="form-select salesChosen" required>
                                        <option selected value="">Select medicine</option>
                                        @foreach ($medicines as $medicine)
                                            <option value="{{ $medicine->id }}">
                                                {{ $medicine->item->name }} 
                                                <br><strong
                                                class="text-danger">({{ number_format($medicine->selling_price) }}Tsh)</strong>
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
                                    <input type="number" class="form-control amount" name="amount[]" placeholder="Amount"
                                        readonly>
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
    @if (false)
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
                            <div class="mb-3">
                                <label for="computer_name" class="form-label">Computer's Name</label>
                                <input type="text" class="form-control" id="computer_name" name="computer_name"
                                    placeholder="DESKTOP-32" required>
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
                $(document).ready(function() {
                    $(".salesChosen").each(function() {
                        let $select = $(this);
                        let $modal = $select.closest(".modal"); // Check if inside a modal

                        $select.select2({
                            width: "100%",
                            dropdownParent: $modal.length ? $modal : $(
                                "body") // Use modal if inside one
                        });

                        // Auto-focus the search input when dropdown opens
                        $select.on("select2:open", function() {
                            document.querySelector(
                                    ".select2-container--open .select2-search__field")
                                .focus();
                        });

                    }).on("select2:select select2:unselect", function() {
                        const row = $(this).closest(".sale-entry")[0];
                        tellPrice(row);
                        calculateAmount(row);
                    });
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
                                <select name="item_id[]" data-row-id="item_id[]" class="form-select salesChosen" required>
                                    <option selected disabled value="">Select medicine</option>
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

            // function to enable or disable use of printer
            var printerCheckbox = $('#printerCheckbox');
            printerCheckbox.on('change', function() {
                if (printerCheckbox.is(':checked')) {
                    new_status = 1;
                    current_status = 0;
                } else {
                    new_status = 0;
                    current_status = 1;
                }

                // Update printer enable status in the db using jquery ajax
                $.ajax({
                    type: "POST",
                    url: "{{ route('printer.updateStatus') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        current_status: current_status,
                        new_status: new_status,
                    },
                    success: function(response) {
                        // Handle the response from the server
                        console.log(response);
                        if (response.status == "success") {
                            if (response.message == "no configurations") {

                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        error(error);

                    }
                });

            });

        });

        // Datatables  for sales table
        $(document).ready(function() {
            $('#salesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('sales') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'sales_name',
                        name: 'item.name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'total_price',
                        name: 'total_price'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
