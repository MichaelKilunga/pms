<div class="container mt-4">
    @if (Auth::user()->role == 'owner' || Auth::user()->role == 'admin')
        {{-- Quick Actions Section --}}
        <div class="row mb-4 g-4 justify-content-center text-center">
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('medicines') }}" class="card bg-primary text-white shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-plus-circle fs-1#"></i> Add Medicine</h6>

                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('staff') }}" class="card bg-success text-white shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-people-fill fs-1#"></i> Manage Staff</h6>

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
                <a href="{{ route('sales') }}" class="card bg-danger text-white shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-cart-plus fs-1#"></i> Create new Sales</h6>

                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('sales') }}" class="card bg-info text-white shadow text-decoration-none">
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
                <div class="card bg-danger text-white shadow">
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
                            <i class="bi bi-people-fill fs-3# me-2"></i>Staff
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
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-currency-exchange fs-3# me-2"></i>Total Sales
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
    @if (Auth::user()->role == 'staff')
        {{-- Quick Actions Section --}}
        <div class="row mb-4 g-4 justify-content-center text-center">
            <div class="col-6 col-md-4 col-lg-2">
                <a href="{{ route('sales') }}" class="card bg-danger text-white shadow text-decoration-none">
                    <div class="card-body">
                        <h6><i class="bi bi-cart-plus fs-1#"></i> Create new Sales</h6>

                    </div>
                </a>
            </div>
            {{-- Summary Section --}}
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-capsule fs-3# me-2"></i>Medicines
                        </h6>
                        <p class="fs-5 fw-bold">{{ $totalMedicines }}</p>
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
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-currency-exchange fs-3# me-2"></i>Total Sales
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

        <div class="col-md-6 mb-4 mb-4">
            {{-- Search   Medicine --}}
            <div class="row m-2">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <h4 class="text-center mb-3">Search Medicine</h4>
                            <form id="search-Form" class="row gy-2 gx-3 align-items-center justify-content-center">
                                <div class="col-auto">
                                    <input type="text" class="search">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="bi bi-search"></i></button>
                                </div>
                            </form>
                            <div class="mt-3 text-center">
                                <h5 class="fw-bold">
                                    Status: <span class="text-success avaiable-medicine">available</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Sales filter --}}
            <div class="row  m-2">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <h4 class="text-center mb-3">Total Sales</h4>
                            <form id="filter-Form" class="row gy-2 gx-3 align-items-center justify-content-center">
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

                    $('.salesGraph').removeClass('text-muted');
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
    });
</script>
