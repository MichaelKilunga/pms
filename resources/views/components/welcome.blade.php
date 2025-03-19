<div class="container mt-2">
    @if (Auth::user()->role == 'owner' || Auth::user()->role == 'admin')
        {{-- Quick Actions Section --}}
        <div class="row mb-4 g-4 justify-content-center text-center">
            <div class="col-6 col-md-4 col-lg-2">
                <button data-bs-toggle="modal" data-bs-target="#createSalesNoteModal"
                    class="card bg-primary text-white shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-plus-circle fs-1#"></i> Sales Notebook</h6>
                    </div>
                </button>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('staff') }}" class="card bg-success text-white shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-people-fill fs-1#"></i>Manage Pharmacist</h6>

                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('pharmacies') }}" class="card bg-warning text-dark shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-hospital fs-1#"></i> View Pharmacies</h6>

                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="" data-bs-toggle="modal" data-bs-target="#createSalesModal"
                    class="card bg-danger text-white shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-cart-plus fs-1#"></i> Create new Sales</h6>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('reports.all') }}" class="card bg-info text-white shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-file-earmark-bar-graph fs-1#"></i> Generate Report</h6>

                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('stock') }}" class="card bg-secondary text-white shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-box-seam fs-1#"></i> Check Stocks</h6>

                    </div>
                </a>
            </div>
        </div>

        {{-- Summary Section --}}
        <div class="row mb-4 g-4 justify-content-center text-center">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-capsule fs-3# me-2"></i>Medicines
                        </h6>
                        <p class="fs-5 fw-bold">{{ $totalMedicines }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-people-fill fs-3# me-2"></i>Pharmacists
                        </h6>
                        <p class="fs-5 fw-bold">{{ $totalStaff }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-warning text-dark shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-hospital fs-2# me-2"></i>Pharmacies
                        </h6>
                        <p class="fs-5 fw-bold">{{ $totalPharmacies }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-exclamation-triangle fs-3# me-2"></i>Expired
                        </h6>
                        <p class="fs-5 fw-bold">{{ $stockExpired }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-info text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-currency-exchange fs-3# me-2"></i>Today Sales
                        </h6>
                        <p class="fs-5 fw-bold">{{ $totalSales }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-secondary text-light shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-box-seam fs-3# me-2"></i>Low Stock
                        </h6>
                        <p class="fs-5 fw-bold">{{ $lowStockCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (Auth::user()->role == 'staff')
        {{-- Quick Actions Section --}}
        <div class="row mb-4 g-4 justify-content-center text-center">
            <div class="col-6 col-md-4 col-lg-2">
                <a href="" data-bs-toggle="modal" data-bs-target="#createSalesModal"
                    class="card bg-danger text-white shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-cart-plus fs-1#"></i> Create new Sales</h6>

                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <button data-bs-toggle="modal" data-bs-target="#createSalesNoteModal"
                    class="card bg-primary text-white shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-plus-circle fs-1#"></i> Sales Notebook</h6>
                    </div>
                </button>
            </div>
            {{-- Summary Section --}}
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-secondary text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-capsule fs-3# me-2"></i>Medicines
                        </h6>
                        <p class="fs-5 fw-bold">{{ $totalMedicines }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-info text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-exclamation-triangle fs-3# me-2"></i>Expired
                        </h6>
                        <p class="fs-5 fw-bold">{{ $stockExpired }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-currency-exchange fs-3# me-2"></i>Today Sales
                        </h6>
                        <p class="fs-5 fw-bold">{{ $totalSales }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-warning text-dark shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-box-seam fs-3# me-2"></i>Low Stock
                        </h6>
                        <p class="fs-5 fw-bold">{{ $lowStockCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif


    {{-- Profit Made --}}
    {{-- <div class="row m-2">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="text-center mb-3">Total Profit Made:
                        <span id="totalProfitMade" type="text" name="totalProfitMade"
                            class="badge badge-succee totalProfitMade text-success"></span>
                    </h4>
                    <hr>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- Sales Filter Section --}}
    <div class="row mb-4">
        {{-- Sales Vs medicine graph --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body salesGraph">
                    <h4 class="text-center">Sales vs Medicines Graph</h4>
                    <canvas id="salesGraph"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            {{-- Search   Medicine --}}
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <h4 class="text-center mb-3">Search Medicine</h4>
                            <form id="search_form" class="row gy-2 gx-3 align-items-center justify-content-center">
                                <hr>
                                <div class="col-auto">
                                    <input id="medicine" type="text" name="searchValue" class="search">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="bi bi-search"></i></button>
                                </div>
                            </form>
                            <div class="mt-3 ml-5   ">
                                <h5 class="fw-bold ml-7">
                                    Status: <span id="avaiable_medicine" class="text-success"></span>
                                    <br>Similar Medicines Available: <span id="similar_medicine"
                                        class="text-warning "></span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Sales filter --}}
            <div class="row  mt-2">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <h4 class="text-center mb-3">Total Sales</h4>
                            <form id="filter-Form" class="row gy-2 gx-3 align-items-center justify-content-center">
                                <hr>
                                <div class="col-auto">
                                    <select name="filter" class="form-select" required>
                                        <option value="day" {{ $filter == 'day' ? 'selected' : '' }}>Today</option>
                                        <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>This Week
                                        </option>
                                        <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>This Month
                                        </option>
                                        <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>This Year
                                        </option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                                </div>
                            </form>

                            {{-- Total Sales in Selected Range --}}
                            <div class="mt-3 text-center">
                                <h5 class="fw-bold">
                                    Total Sales in Selected Range: <span
                                        class="text-success total-sales">{{ $filteredTotalSales }} TZS</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stock Section --}}
    <div class="row mb-4">
        {{-- Stock Summary Table --}}
        <div class="col-md-6 mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="text-center">Stock Summary</h4>
                        <div class="table-responsive">
                            <table id="Table" class="table table-striped table-hover text-center">
                                <thead>
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($medicines as $medicine)
                                        <tr>
                                            <td>{{ $medicine->medicine_name }}</td>
                                            <td>{{ $medicine->total_stock }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Stock Vs medicine graph --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h4 class="text-center">Stock vs Medicines Graph</h4>
                    <canvas id="stockGraph"></canvas>
                </div>
            </div>
        </div>
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
                                <label class="form-label">Medicine</label>
                                <select name="item_id[]" class="form-select chosen" required>
                                    <option selected disabled value="">Select Item</option>
                                    @foreach ($sellMedicines as $sellMedicine)
                                        {{-- <option value="{{ $sellMedicine->item->id }}">{{ $sellMedicine->item->name }}
                                        </option> --}}
                                        <option value="{{ $sellMedicine->id }}">
                                            {{ $sellMedicine->item->name }} <br><strong
                                                class="text-danger">Exp:({{ \Carbon\Carbon::parse($sellMedicine->expire_date)->format('m/Y') }})</strong>
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

{{-- Create Sales Note Modal --}}
<div class="modal fade" id="createSalesNoteModal" role="dialog" aria-labelledby="createSalesNoteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('salesNotes.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createSalesNoteModalLabel">Create Sales Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="floatingName" name="name"
                            placeholder="Amoxicillin" required>
                        <label class="form-label" for="floatingName">Medicine Name<span
                                class="text-danger">*</span></label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 form-floating">
                            <input type="number" min="1" class="form-control" id="floatingQuantity"
                                name="quantity" placeholder="50" required>
                            <label class="form-label" for="floatingQuantity">Quantity<span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="col-md-6 form-floating">
                            <input type="number" min="1" class="form-control" id="floatingUnitPrice"
                                name="unit_price" placeholder="200" required>
                            <label class="form-label" for="floatingUnitPrice">Unit Price<span
                                    class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" id="floatingDescription" name="description"
                            placeholder="Sold for headache">
                        <label class="form-label" for="floatingDescription">Description <span
                                class="text-success">(optional)</span></label>
                    </div>

                    <input readonly hidden type="text" name="pharmacy_id" placeholder="Pharmacy ID"
                        value="{{ session('current_pharmacy_id') }}" required>
                    <input readonly hidden type="text" name="staff_id" placeholder="Staff ID"
                        value="{{ auth()->id() }}" required>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script for Two Separate Graphs --}}
<script>
    $(document).ready(function() {
        // Sales Graph Initialization
        function initializeGraph(context, labels, data, label, backgroundColor, borderColor) {
            return new Chart(context, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: backgroundColor,
                        borderColor: borderColor,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Initialize Sales Graph
        const salesGraphCtx = $('#salesGraph')[0].getContext('2d');
        const salesChart = initializeGraph(
            salesGraphCtx,
            {!! json_encode($medicineNames) !!},
            {!! json_encode($medicineSales) !!},
            'Sales',
            'rgba(54, 162, 235, 0.6)',
            'rgba(54, 162, 235, 1)'
        );

        // Initialize Stock Graph
        const stockGraphCtx = $('#stockGraph')[0].getContext('2d');
        initializeGraph(
            stockGraphCtx,
            {!! json_encode($medicineNames) !!},
            {!! json_encode($medicineStock) !!},
            'Stock',
            'rgba(255, 99, 132, 0.6)',
            'rgba(255, 99, 132, 1)'
        );

        // Filter Form Submission
        $('#filter-Form').on('submit', function(event) {
            event.preventDefault();

            var filterValue = $('select[name="filter"]').val();

            // Update loading state
            $('.total-sales')
                .removeClass('text-success')
                .addClass('text-muted')
                .html(
                    '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>'
                );

            $('#salesGraph').attr('hidden', true);
            $('.salesGraph')
                .addClass('text-muted')
                .append(
                    '<div class="spinner-border remove-spinner spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>'
                );

            // Perform AJAX request
            $.ajax({
                url: '{{ route('sales.filter') }}',
                method: 'GET',
                data: {
                    filter: filterValue
                },
                dataType: 'json',
                success: function(response) {
                    // Update total sales text
                    $('.total-sales')
                        .removeClass('text-muted')
                        .addClass('text-success')
                        .text(new Intl.NumberFormat('en-TZ', {
                            style: 'currency',
                            currency: 'TZS'
                        }).format(response.filteredTotalSales));

                    $('.salesGraph').removeClass('text-muted').removeClass('text-danger')
                        .addClass('text-success');
                    $('#salesGraph').removeAttr('hidden');
                    $('.remove-spinner ').remove();

                    // Update the graph
                    salesChart.data.labels = response.medicineNames;
                    salesChart.data.datasets[0].data = response.medicineSales;
                    salesChart.update();
                },
                error: function(error) {
                    console.error('Error fetching sales data:', error);
                    $('.total-sales')
                        .text('There is an Error!')
                        .removeClass('text-success')
                        .addClass('text-danger');
                }
            });
        });

        // Search Form Submission
        $('#medicine').on('input', function(event) {
            event.preventDefault();

            var searchValue = $('#medicine').val();
            var avaiable_medicine = $('#avaiable_medicine');
            var similar_medicine = $('#similar_medicine');
            var spinner =
                '<div class="spinner-border spinner-border-sm" id="spinner" role="status"><span class="visually-hidden">Loading...</span></div>';
            var spinnerId = $('#spinner');

            // Update loading state
            avaiable_medicine.removeClass('text-success').addClass('text-muted').html(spinner);
            similar_medicine.removeClass('text-success').addClass('text-muted').html(spinner);

            // Perform AJAX request
            $.ajax({
                url: '{{ route('medicines.search') }}',
                method: 'GET',
                data: {
                    search: searchValue
                },
                dataType: 'json',
                success: function(response) {
                    spinnerId.remove();
                    avaiable_medicine.addClass('text-success').removeClass('text-muted')
                        .text(response.availableMedicine);
                    similar_medicine.addClass('text-success').removeClass('text-muted')
                        .text(response.similarMedicines.join(', '));
                },
                error: function(error) {
                    console.error('Error fetching sales data:', error);
                    spinnerId.remove();
                    avaiable_medicine.text('There is an Error!').removeClass('text-success')
                        .addClass('text-danger').html();
                    similar_medicine.text('There is an Error!').removeClass('text-success')
                        .addClass('text-danger').html();
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Chosen for dynamically added rows
        function initializeChosen() {
            $(document).ready(function() {
                $(".chosen").each(function() {
                    let $select = $(this);
                    let $modal = $select.closest(".modal"); // Check if inside a modal

                    $select.select2({
                        width: "100%",
                        placeholder: "Select an option", // Placeholder for better UX
                        focus: true,
                        language: {
                            noResults: function() {
                                return "No matches found!";
                            }
                        },
                        dropdownParent: $modal.length ? $modal : $(
                            "body") // Use modal if inside one
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
            let medicines = @json($sellMedicines); // Convert medicines to a JS array
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
                                    @foreach ($sellMedicines as $sellMedicine)
                                        <option value="{{ $sellMedicine->id }}">
                                            {{ $sellMedicine->item->name }} <br><strong class="text-danger">Exp:({{ \Carbon\Carbon::parse($sellMedicine->expire_date)->format('m/Y') }})</strong>
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
