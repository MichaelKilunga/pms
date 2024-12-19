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
                <div class="card-body">
                    <h4 class="text-center">Sales vs Medicines Graph</h4>
                    <canvas id="salesGraph"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4 mb-4">
            {{-- Search   Medicine filter --}}
            <div class="row m-2">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <h4 class="text-center mb-3">Search Medicine</h4>
                            <form id="filter-Form" class="row gy-2 gx-3 align-items-center justify-content-center">
                                <div class="col-auto">
                                <input type="text" class="search">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary"><i class="bi bi-search" ></i></button>
                                </div>
                            </form>
                            <div class="mt-3 text-center">
                                <h5 class="fw-bold">
                                    Status: <span
                                        class="text-success total-sales">available</span>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Graph
        const salesCtx = document.getElementById('salesGraph').getContext('2d');
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($medicineNames) !!},
                datasets: [{
                    label: 'Sales',
                    data: {!! json_encode($medicineSales) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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

        // Stock Graph
        const stockCtx = document.getElementById('stockGraph').getContext('2d');
        new Chart(stockCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($medicineNames) !!},
                datasets: [{
                    label: 'Stock',
                    data: {!! json_encode($medicineStock) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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
    });
</script>

{{-- Script for sales filter --}}
{{-- <script>
    $(document).ready(function() {
        // Listen for form submission
        $('#filter-Form').on('submit', function(event) {
            event.preventDefault(); // Prevent page reload

            var filterValue = $('select[name="filter"]').val(); // Get selected filter value
            // $('.total-sales').text(filterValue);
            // Perform AJAX request
            $.ajax({
                url: '{{ route('sales.filter') }}',
                method: 'GET',
                data: {
                    filter: filterValue
                },
                dataType: 'json',
                success: function(response) {
                    // Update the total sales value in the DOM
                    $('.total-sales').addClass('text-success');
                    $('.total-sales').text((response.filteredTotalSales) + " " + "TZS");

                    console.log('Sales data updated successfully.');
                },
                error: function(error) {
                    console.error('Error fetching sales data:', error);
                    $('.total-sales').text("There is an Error!");
                    $('.total-sales').removeClass('text-success');
                    $('.total-sales').addClass('text-danger');
                }
            });
        });
    });
</script> --}}
<script>
    $(document).ready(function() {
        const salesCtx = document.getElementById('salesGraph').getContext('2d');
        let salesChart = new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($medicineNames) !!}, // Initial data
                datasets: [{
                    label: 'Sales',
                    data: {!! json_encode($medicineSales) !!}, // Initial data
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
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

        // Listen for form submission
        $('#filter-Form').on('submit', function(event) {
            event.preventDefault(); // Prevent page reload

            var filterValue = $('select[name="filter"]').val(); // Get selected filter value

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
                    $('.total-sales').addClass('text-success').text(response
                        .filteredTotalSales + " TZS");

                    // Update the graph
                    salesChart.data.labels = response.medicineNames;
                    salesChart.data.datasets[0].data = response.medicineSales;
                    salesChart.update();

                    console.log('Graph updated successfully.');
                },
                error: function(error) {
                    console.error('Error fetching sales data:', error);
                    $('.total-sales').text("There is an Error!")
                        .removeClass('text-success')
                        .addClass('text-danger');
                }
            });
        });
    });
</script>
