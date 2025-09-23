    @section('title', 'Dashboard')
    @section('meta_description', 'Pharmacy Management System Dashboard')
    @section('meta_keywords', 'Pharmacy Management System Dashborad')

    <div class="container mt-2">
        @if (Auth::user()->role == 'owner' || Auth::user()->role == 'admin')
            {{-- Quick Actions Section --}}
            <div class="row mb-4 g-4 justify-content-center text-center">
                <div class="col-6 col-md-4 col-lg-2">
                    <button data-bs-toggle="modal" data-bs-target="#createSalesNoteModal"
                        class="card bg-primary text-white shadow text-decoration-none">
                        <div class="card-body">
                            <h6><i class="bi bi-plus-circle fs-1#"></i> Create Sales Notebook</h6>
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
                            <p class="fs-5 fw-bold">{{ number_format($totalSales, 2) }}</p>
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
                            <h6><i class="bi bi-plus-circle fs-1#"></i> Create Sales Notebook</h6>
                            {{-- <p class="fs-5 fw-bold">{{ $totalMedicines }}</p> --}}
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
                 <!-- Locked sales card (hidden initially) -->
                <div class="col-6 col-md-4 col-lg-2" id="totalSalesCard" style="display: none;">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            <h6>
                                <i class="bi bi-currency-exchange fs-3# me-2"></i>Today Sales
                            </h6>
                            <p class="fs-5 fw-bold">{{ number_format($totalSales, 2) }} TZS</p>
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
                                <form id="search_form"
                                    class="row gy-2 gx-3 align-items-center justify-content-center">
                                    <hr>
                                    <div class="col-auto">
                                        <input id="medicine" type="text" name="searchValue"
                                            class="form-control search">
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
                @if (Auth::user()->role == 'owner' || Auth::user()->role == 'admin')
                    <div class="row  mt-2">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h4 class="text-center mb-3">Total Sales</h4>
                                    <form id="filter-Form"
                                        class="row gy-2 gx-3 align-items-center justify-content-center">
                                        <hr>
                                        <div class="col-auto">
                                            <select name="filter" class="form-select" required>
                                                <option value="day" {{ $filter == 'day' ? 'selected' : '' }}>Today
                                                </option>
                                                <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>This
                                                    Week
                                                </option>
                                                <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>This
                                                    Month
                                                </option>
                                                <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>This
                                                    Year
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button id="apply-filter-btn" type="submit"
                                                class="btn btn-primary">Apply
                                                Filter</button>
                                        </div>
                                    </form>

                                    <div class="mt-3 text-center">
                                        <h5 class="fw-bold">
                                            Total Sales in Selected Range: <span
                                                class="text-success total-sales">{{ number_format($filteredTotalSales) }}
                                                TZS</span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                @if (Auth::user()->role == 'staff')
                    <!-- Eye icon (initial display) -->
                    <div id="unlock-section" class="text-center mt-3">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h4 class="text-center mb-3">Total Sales</h4>
                                    <hr>
                                    <button id="eye-icon" class="btn btn-md btn-info text-white mt-2">
                                        <i class="fas fa-eye-slash  fa-1x" style="cursor: pointer;"></i>
                                    </button>
                                    <p>Click the eye icon to unlock the sales report.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Password prompt (hidden initially) -->
                    <div id="password-section" class="text-center mt-3" style="display: none;">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h4 class="text-center mb-3">Total Sales</h4>
                                    <hr class="mb-2">
                                    <input type="password" id="unlock-password"
                                        class="form-control d-inline-block w-auto" placeholder="Enter Password"
                                        required>
                                    <button id="check-password-btn" class="btn btn-primary">Unlock</button> <br>
                                    <span class="text-danger text-small" id="passwordCHechError"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Locked report (hidden initially) -->
                    <div class="row mt-2 myreportcheck" style="display: none;">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-body">
                                    <h4 class="text-center mb-3">Total Sales</h4>
                                    <form id="filter-Form"
                                        class="row gy-2 gx-3 align-items-center justify-content-center">
                                        <hr>
                                        <div class="col-auto">
                                            <select name="filter" class="form-select" required>
                                                <option value="day" {{ $filter == 'day' ? 'selected' : '' }}>Today
                                                </option>
                                                {{-- <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>This Week
                                        </option>
                                        <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>This
                                            Month
                                        </option>
                                        <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>This Year
                                        </option> --}}
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button id="apply-filter-btn" type="submit"
                                                class="btn btn-primary">Apply
                                                Filter</button>
                                        </div>
                                    </form>

                                    <div class="mt-3 text-center">
                                        <h5 class="fw-bold">
                                            Total Sales in Selected Range:
                                            <span
                                                class="text-success total-sales">{{ number_format($filteredTotalSales) }}
                                                TZS</span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif



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
                            <div class="table-responsive mt-2">
                                <table id="Table" class="table table-striped table-hover">
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
        <div class="modal-dialog modal-xl">
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
                                            {{-- <option value="{{ $sellMedicine->id }}">
                                            {{ $sellMedicine->item->name }}
                                            <br><strong
                                                class="text-danger">({{ number_format($sellMedicine->selling_price) }}Tsh)</strong>
                                        </option> --}}
                                            <option
                                                value="{{ $sellMedicine->item->id }}_{{ $sellMedicine->selling_price }}">
                                                {{ $sellMedicine->item->name }}
                                                ({{ number_format($sellMedicine->selling_price) }}Tsh)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Price(TZS)</label>
                                    <input type="text" class="form-control" placeholder="Price"
                                        name="total_price[]" value="0" readonly required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label  stock-label" for="label[]">Quantity</label>
                                    <input type="number" class="form-control" min="1"
                                        title="Only 10 has remained in stock!" placeholder="Quantity"
                                        name="quantity[]" required>
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
                                <input type="text" class="form-control text-danger" id="totalAmount"
                                    value="0" readonly>
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
    <div class="modal fade modal-lg" id="createSalesNoteModal" role="dialog"
        aria-labelledby="createSalesNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('salesNotes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header text-white bg-primary">
                        <h5 class="modal-title" id="createSalesNoteModalLabel">Create Sales Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex flex-column">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" id="floatingName" name="name"
                                placeholder="Amoxicillin" required>
                            <label class="form-label" for="floatingName">Medicine Name<span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3 form-floating">
                                <input type="number" min="1" class="form-control" id="floatingQuantity"
                                    name="quantity" placeholder="50" required>
                                <label class="form-label" for="floatingQuantity">Quantity<span
                                        class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-5 form-floating">
                                <input type="number" min="1" class="form-control" id="floatingUnitPrice"
                                    name="unit_price" placeholder="200" required>
                                <label class="form-label" for="floatingUnitPrice">Unit Price<span
                                        class="text-danger">*</span></label>
                            </div>

                            {{-- quantity* unit selling price --}}
                            <div class="col-md-4 form-floating">
                                <input type="text" class="form-control fw-bold text-success"
                                    id="floatingTotalPrice" name="total_price" placeholder="100000" readonly>
                                <label class="form-label fw-bold text-dark" for="TotalAmount">Total Price
                                    <span class="text-danger">*</span>
                                </label>
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
                        <button type="submit" class="btn btn-primary">Save</button>
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

                var duration = $('select[name="filter"]').val();

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
                    url: `/sales/filter/${duration}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            console.log(response);
                            // alert('Succeeded:  '+response.message);
                            $('.total-sales')
                                .removeClass('text-muted')
                                .addClass('text-success')
                                .text(new Intl.NumberFormat('en-TZ', {
                                    style: 'currency',
                                    currency: 'TZS'
                                }).format(response.filteredTotalSales));

                            $('.salesGraph').removeClass('text-muted').removeClass(
                                    'text-danger')
                                .addClass('text-success');
                            $('#salesGraph').removeAttr('hidden');
                            $('.remove-spinner').remove();

                            // Update the graph
                            salesChart.data.labels = response.medicineNames;
                            salesChart.data.datasets[0].data = response.medicineSales;
                            salesChart.update();
                        } else {
                            console.log(response);
                            $('.total-sales')
                                .removeClass('text-muted')
                                .addClass('text-danger')
                                .text(new Intl.NumberFormat('en-TZ', {
                                    style: 'currency',
                                    currency: 'TZS'
                                }).format(0));

                            $('.salesGraph').removeClass('text-muted').removeClass(
                                    'text-success')
                                .addClass('text-danger');
                            $('#salesGraph').removeAttr('hidden');
                            $('.remove-spinner').remove();

                            salesChart.data.labels = [];
                            salesChart.data.datasets[0].data = [];
                            salesChart.update();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        // alert('Errored: ' + xhr.statusText);
                        $('.total-sales')
                            .removeClass('text-success')
                            .addClass('text-danger')
                            .text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS'
                            }).format(0));


                        $('.salesGraph').removeClass('text-muted').removeClass('text-danger')
                            .addClass('text-success');
                        $('#salesGraph').removeAttr('hidden');
                        $('.remove-spinner').remove();

                        // Update the graph
                        salesChart.data.labels = [];
                        salesChart.data.datasets[0].data = [];
                        salesChart.update();
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
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // raw data from Laravel
            const rawMedicines = @json($sellMedicines); // ensure this includes item relation

            // normalize and add compositeKey = "<medicine_id>_<selling_price>"
            const medicines = (rawMedicines || []).map(m => {
                const itemId = (m.item && m.item.id) ? m.item.id : (m.item_id || '');
                return Object.assign({}, m, {
                    compositeKey: itemId + '_' + m.selling_price
                });
            });

            // -------------------------
            // Reusable Functions
            // -------------------------
            function calculateAmount(row) {
                const price = parseFloat(row.querySelector('[name="total_price[]"]').value) || 0;
                const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 0;
                const amount = price * quantity;
                row.querySelector('.amount').value = amount;

                updateTotalAmount();
            }

            function tellPrice(row) {
                const selectedKey = row.querySelector('[name="item_id[]"]').value;
                if (!selectedKey) return;

                const selectedMedicine = medicines.find(m => m.compositeKey === selectedKey);
                if (!selectedMedicine) return;

                // set hidden stock_id[] to actual stock id (so server can use it)
                const stockInput = row.querySelector('[name="stock_id[]"]');
                if (stockInput) stockInput.value = selectedMedicine.id;

                // set price, set max quantity, update label
                const priceInput = row.querySelector('[name="total_price[]"]');
                if (priceInput) priceInput.value = selectedMedicine.selling_price;

                const qtyInput = row.querySelector('[name="quantity[]"]');
                if (qtyInput) qtyInput.setAttribute('max', selectedMedicine.remain_Quantity || 0);

                const labelElement = row.querySelector('.stock-label');
                if (labelElement) {
                    labelElement.innerHTML = '';
                    const appendedText = document.createElement('small');
                    appendedText.innerHTML = 'In stock (&darr;' + (selectedMedicine.remain_Quantity || 0) + ')';
                    appendedText.classList.add(
                        (selectedMedicine.remain_Quantity || 0) < (selectedMedicine.low_stock_percentage || 0) ?
                        'text-danger' :
                        'text-success'
                    );
                    labelElement.appendChild(appendedText);
                }
            }

            function updateTotalAmount() {
                let total = 0;
                document.querySelectorAll('.sale-entry').forEach(row => {
                    total += parseFloat(row.querySelector('.amount').value) || 0;
                });
                const totalEl = document.getElementById('totalAmount');
                if (totalEl) {
                    totalEl.value = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'TZS'
                    }).format(total);
                }
            }

            // -------------------------
            // Disable/Enable Selected Options (based on compositeKey)
            // -------------------------
            function updateMedicineOptions() {
                let selectedKeys = [];
                document.querySelectorAll('[name="item_id[]"]').forEach(select => {
                    if (select.value) selectedKeys.push(select.value); // these are composite keys now
                });

                document.querySelectorAll('[name="item_id[]"]').forEach(select => {
                    const currentValue = select.value;
                    $(select).find('option').each(function() {
                        // this.value is also a compositeKey
                        if (this.value && selectedKeys.includes(this.value) && this.value !==
                            currentValue) {
                            $(this).prop("disabled", true);
                        } else {
                            $(this).prop("disabled", false);
                        }
                    });

                    // refresh select2 display
                    try {
                        $(select).trigger('change.select2');
                    } catch (e) {
                        // ignore if select2 not present
                    }
                });
            }

            // -------------------------
            // Initialize Select2 for a row
            // -------------------------
            function attachSelect2(row) {
                // init select2 on selects inside this row
                const $sel = $(row).find('select.chosen');
                if ($sel.length) {
                    $sel.select2({
                        width: '100%',
                        minimumResultsForSearch: 5,
                        dropdownParent: $('#createSalesModal')
                    }).on('select2:select select2:unselect change', function() {
                        // when user selects or unselects, update price & options
                        tellPrice(row);
                        calculateAmount(row);
                        updateMedicineOptions();
                    });
                } else {
                    // fallback: plain select change
                    const sel = row.querySelector('select.chosen');
                    if (sel) {
                        sel.addEventListener('change', function() {
                            tellPrice(row);
                            calculateAmount(row);
                            updateMedicineOptions();
                        });
                    }
                }
            }

            // -------------------------
            // Add Row Functionality
            // -------------------------
            const addSaleBtn = document.getElementById('addSaleRow');
            if (addSaleBtn) {
                addSaleBtn.addEventListener('click', function() {
                    const salesFields = document.getElementById('salesFields');
                    const newRow = document.createElement('div');
                    newRow.classList.add('row', 'mb-3', 'sale-entry', 'align-items-center');

                    newRow.innerHTML = `
                <input type="text" name="stock_id[]" hidden required>
                <div class="col-md-4">
                    <select name="item_id[]" class="form-select chosen" required>
                        <option selected disabled value="">Select Item</option>
                        @foreach ($sellMedicines as $sellMedicine)
                            <option value="{{ $sellMedicine->item->id }}_{{ $sellMedicine->selling_price }}">
                                {{ $sellMedicine->item->name }} ({{ number_format($sellMedicine->selling_price) }}Tsh)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" value="0" name="total_price[]" readonly required>
                </div>
                <div class="col-md-2">
                    <label class="form-label stock-label">Quantity</label>
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

                    attachSelect2(newRow);
                    updateMedicineOptions();

                    const qty = newRow.querySelector('[name="quantity[]"]');
                    if (qty) qty.addEventListener('input', function() {
                        calculateAmount(newRow);
                    });

                    const removeBtn = newRow.querySelector('.remove-sale-row');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function() {
                            newRow.remove();
                            updateTotalAmount();
                            updateMedicineOptions();
                        });
                    }
                });
            }

            // -------------------------
            // Initialize Existing Rows
            // -------------------------
            document.querySelectorAll('.sale-entry').forEach(row => {
                attachSelect2(row);

                // if row already has a selected item, populate its price & label
                const sel = row.querySelector('[name="item_id[]"]');
                if (sel && sel.value) {
                    tellPrice(row);
                    calculateAmount(row);
                }

                const qty = row.querySelector('[name="quantity[]"]');
                if (qty) qty.addEventListener('input', function() {
                    calculateAmount(row);
                });
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Function to attach calculation to inputs
            function attachCalculateTotal(modal) {
                const quantityInput = modal.querySelector('#floatingQuantity');
                const unitPriceInput = modal.querySelector('#floatingUnitPrice');
                const totalPriceInput = modal.querySelector('#floatingTotalPrice');

                function calculateTotal() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const unitPrice = parseFloat(unitPriceInput.value) || 0;
                    const total = quantity * unitPrice;

                    totalPriceInput.value = total > 0 ?
                        new Intl.NumberFormat('en-TZ', {
                            style: 'currency',
                            currency: 'TZS',
                            minimumFractionDigits: 0
                        }).format(total) :
                        '';
                }

                quantityInput.addEventListener('input', calculateTotal);
                unitPriceInput.addEventListener('input', calculateTotal);
            }

            // Listen for modal show
            const salesNoteModal = document.getElementById('createSalesNoteModal');
            salesNoteModal.addEventListener('shown.bs.modal', function() {
                attachCalculateTotal(salesNoteModal);
            });
        });
    </script>

    <!-- jQuery Script -->
    <script>
        $(document).ready(function() {

            // Show password input when clicking the eye
            $("#eye-icon").click(function() {
                $("#unlock-section").hide();
                $("#password-section").show();
            });

            // Check password
            $("#check-password-btn").click(function() {
                // diable button to prevent multiple clicks
                $(this).prop('disabled', true);
                // display loading state
                $(this).html(
                    '<span class="spinner-border w-5 h-5 spinner-border-sm" role="status" aria-hidden="true"></span>'
                );


                let enteredPassword = $("#unlock-password").val();

                // send backend check request
                $.ajax({
                    url: '{{ route('checkPassword') }}',
                    method: 'POST',
                    data: {
                        password: enteredPassword,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#password-section").hide();
                            $(".myreportcheck").show();
                            $("#totalSalesCard").show();
                            setTimeout(function() {
                                $(".myreportcheck").hide();
                                $("#totalSalesCard").hide()
                                $("#unlock-section").show();
                                $("#unlock-password").val('');
                            }, 5000); // hide after 5 seconds
                            $("#unlock-password").val(response.password).focus();
                            // enable button and reset text
                            $("#check-password-btn").prop('disabled', false).html('Unlock');
                        } else {
                            // alert(response.message || "Incorrect password. Please try again.");
                            $("#unlock-password").val(response.password).focus();
                            $("#passwordCHechError").text(response.message ||
                                "Incorrect password. Please try again.");
                            setTimeout(function() {
                                $("#passwordCHechError").text('');
                            }, 5000); // clear error after 5 seconds
                            // enable button and reset text
                            $("#check-password-btn").prop('disabled', false).html('Unlock');
                        }
                    },
                    error: function() {
                        // alert("Error checking password. Please try again.");
                        $("#unlock-password").val('').focus();
                        $("#passwordCHechError").text(
                            "Error checking password. Please try again.");
                        setTimeout(function() {
                            $("#passwordCHechError").text('');
                        }, 5000); // clear error after 5 seconds
                        // enable button and reset text
                        $("#check-password-btn").prop('disabled', false).html('Unlock');
                    }
                });

            });
        });
    </script>
