@extends('stockTransfers.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3 mt-2">
            <h4 class="text-primary fs-2 fw-bold">Stock Transfer</h4>
            <div>
                <!-- Trigger Button -->
                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStockTransfersModal">
                    Transfer Stock Now!
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table id="stockTransfer" class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>SN</th>
                        <th>Medicine</th>
                        <th>Quantity</th>
                        <th>To Pharmacy</th>
                        <th>TIN Number</th>
                        <th>Notes</th>
                        <th>Transfer Date</th>
                        <th>Transferred By</th>
                        <th>Posted On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transfers as $transfer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transfer->stock->item->name }}</td>
                            <td>{{ $transfer->quantity }}</td>
                            <td>
                                {{ $transfer->to_pharmacy_id ? $transfer->toPharmacy->name : $transfer->to_pharmacy_name }}
                            </td>
                            <td>{{ $transfer->to_pharmacy_tin }}</td>
                            <td>{{ $transfer->notes }}</td>
                            <td>{{ \Carbon\Carbon::parse($transfer->transfer_date)->format('Y-m-d') }}</td>
                            <td>{{ $transfer->transferredBy->name }}</td>
                            <td>{{ $transfer->created_at }}</td>
                            <td>
                                @if ($transfer->status == 'completed')
                                    <span class="text-success">Completed</span>
                                @else
                                    <span class="text-danger">Pending</span>
                                @endif
                            <td>
                                @if ($transfer->status == 'completed')
                                    <div class="d-flex">
                                        <form action="{{ route('stockTransfers.destroy', $transfer->id) }}" method="POST"
                                            class="me-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to restore this stock transfer, you will not be able to see your transfer and transfered stock will return to original stock ?')"><i
                                                    class="bi bi-arrow-counterclockwise"></i></button>
                                        </form>
                                        <form action="{{ route('stockTransfers.confirm', $transfer->id) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm"
                                                onclick="return confirm('Are you sure you want to confirm this stock transfer ?')"
                                                disabled><i class="bi bi-check-circle"></i></button>
                                        </form>
                                        <form action="{{ route('stockTransfers.print', $transfer->id) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn btn-outline-dark btn-sm mx-2"
                                                onclick="return confirm('Are you sure you want to print this stock transfer ?')"><i
                                                    class="bi bi-printer"></i></button>
                                        </form>
                                    </div>
                                @else
                                    <div class="d-flex">
                                        <form action="{{ route('stockTransfers.destroy', $transfer->id) }}" method="POST"
                                            class="me-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to restore this stock transfer, you will not be able to see your transfer and transfered stock will return to original stock ?')"><i
                                                    class="bi bi-arrow-counterclockwise"></i></button>
                                        </form>
                                        <form action="{{ route('stockTransfers.confirm', $transfer->id) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm"
                                                onclick="return confirm('Are you sure you want to confirm this stock transfer ?')"><i
                                                    class="bi bi-check-circle"></i></button>
                                        </form>
                                        <form action="{{ route('stockTransfers.print', $transfer->id) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn btn-outline-dark btn-sm mx-2"
                                                onclick="return confirm('Confirm first to be able to print.') && false"><i
                                                    class="bi bi-printer"></i></button>
                                        </form>
                                    </div>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $transfers->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addStockTransfersModal" tabindex="-1" aria-labelledby="addStockTransfersModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-12 bg-white ">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title fw-bold text-white" id="addStockTransfersModalLabel">Transfer Stock</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('stockTransfers.store') }}" method="POST">
                                @csrf
                                <!-- To Pharmacy -->
                                <div class="mb-3">
                                    <label class="form-label">To Pharmacy (in system)</label>
                                    <select name="to_pharmacy_id" class="form-select" required>
                                        <option value="" selected>-- Select an Option --</option>
                                        <option value="0">Pharmacy is Not in the system </option>
                                        @foreach ($pharmacies as $pharmacy)
                                            <option value="{{ $pharmacy->id }}">{{ $pharmacy->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- External Pharmacy Name field to be shown if option selected has value="0" -->
                                <div class="mb-3 hidden external-pharmacy-name">
                                    <label for="to_pharmacy_name" class="form-label">External Pharmacy Name</label>
                                    <input type="text" class="form-control" name="to_pharmacy_name"
                                        placeholder="Only if not in system">
                                </div>
                                <!-- TIN Number -->
                                <div class="mb-3">
                                    <label for="tin_number" class="form-label">TIN Number</label>
                                    <input type="text" class="form-control" name="tin_number"
                                        placeholder="Only if external">
                                </div>

                                <!-- Medicine Selection -->
                                <div id="medicine-group-container">
                                    <div class="medicine-group mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="stock_id" class="form-label">Select Medicine</label>
                                                <select name="stock_id[]" class="form-select select2" required>
                                                    <option value="">-- Select --</option>
                                                    @foreach ($stocks as $stock)
                                                        <option data-remain-quantity="{{ $stock->remain_Quantity }}"
                                                            value="{{ $stock->id }}">
                                                            {{ $stock->item->name }} (Remaining:
                                                            {{ $stock->remain_Quantity }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="quantity" class="form-label">Quantity to Transfer</label>
                                                <input type="number" class="form-control" name="quantity[]" required
                                                    min="1">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button"
                                                    class="btn btn-danger remove-medicine-group">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-medicine-group" class="btn btn-success mb-3">Add Another
                                    Medicine</button>

                                <!-- Transfer Date -->
                                <div class="mb-3 hidden">
                                    <label for="transfer_date" class="form-label">Transfer Date</label>
                                    <input type="date" class="form-control" name="transfer_date"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required readonly>

                                </div>

                                <!-- Notes -->
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                </div>
                                <!-- Submit Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Transfer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#stockTransfer').DataTable({
                    paging: true,
                    language: {
                        lengthMenu: "Show _MENU_ entries",
                        zeroRecords: "No records found",
                        info: "Showing page _PAGE_ of _PAGES_",
                        infoEmpty: "No records available",
                        infoFiltered: "(filtered from _MAX_ total records)",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        },
                        search: "Search:",
                    }
                });

                // External Pharmacy Name field to be shown if option selected has value="0" 
                $('select[name="to_pharmacy_id"]').change(function() {
                    if ($(this).val() == '0') {
                        $('.external-pharmacy-name').removeClass('hidden');
                        // set as required
                    } else {
                        $('.external-pharmacy-name').addClass('hidden');
                    }
                });

                // Function to initialize Select2
                initializeSelect2();

                function initializeSelect2() {
                    // Initialize Select2 for the medicine selection
                    $(".select2").each(function() {
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
                        // catch remaining quantity and selected value
                        let selectedOption = $(this).find("option:selected");
                        let remainingQuantity = selectedOption.data("remain-quantity");
                        // set the remain quantity to the input field of quantity
                        $(this).closest(".medicine-group").find("input[name='quantity[]']").attr(
                            "max", remainingQuantity);
                    });
                }

                // Initialize Select2 for the existing select elements
                var $addMedicineBtn = $("#add-medicine-group");
                var $container = $("#medicine-group-container");

                $addMedicineBtn.on("click", function() {
                    var $newGroup = `<div class="medicine-group mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="stock_id" class="form-label">Select Medicine</label>
                                                <select name="stock_id[]" class="form-select select2" required>
                                                    <option value="">-- Select --</option>
                                                    @foreach ($stocks as $stock)
                                                        <option data-remain-quantity="{{ $stock->remain_Quantity }}"
                                                            value="{{ $stock->id }}">
                                                            {{ $stock->item->name }} (Remaining:
                                                            {{ $stock->remain_Quantity }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="quantity" class="form-label">Quantity to Transfer</label>
                                                <input type="number" class="form-control" name="quantity[]" required
                                                    min="1">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button"
                                                    class="btn btn-danger remove-medicine-group">Remove</button>
                                            </div>
                                        </div>
                                    </div>`;

                    $container.append($newGroup);
                    initializeSelect2(); // Reinitialize Select2 for the new group
                });

                $container.on("click", ".remove-medicine-group", function() {
                    var $groups = $container.find(".medicine-group");
                    if ($groups.length > 1) {
                        $(this).closest(".medicine-group").remove();
                    } else {
                        alert('At least one medicine entry is required.');
                    }
                });
            });
        </script>
    @endsection
