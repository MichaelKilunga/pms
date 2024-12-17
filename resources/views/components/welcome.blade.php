<div class="container mt-4">
    {{-- Summary Section --}}
    <div class="row mb-4 g-4 justify-content-center text-center">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <h6>Medicines</h6>
                    <p class="fs-5 fw-bold">{{ $totalMedicines }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h6>Staff</h6>
                    <p class="fs-5 fw-bold">{{ $totalStaff }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h6>Pharmacies</h6>
                    <p class="fs-5 fw-bold">{{ $totalPharmacies }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <h6>Expired</h6>
                    <p class="fs-5 fw-bold">{{ $stockExpired }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h6>Total Sales</h6>
                    <p class="fs-5 fw-bold">{{ $totalSales }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h6>Low Stock</h6>
                    <p class="fs-5 fw-bold">{{ $lowStockCount }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Sales Filter Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="text-center mb-3">Total Sales</h4>
                    <form id="filter-Form" class="row gy-2 gx-3 align-items-center justify-content-center">
                        <div class="col-auto">
                            <select name="filter" class="form-select" required>
                                <option value="day" {{ $filter == 'day' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>This Month</option>
                                <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>This Year</option>
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

    {{-- Graphs Section --}}
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h4 class="text-center">Sales vs Medicines Graph</h4>
                    <canvas id="salesGraph"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h4 class="text-center">Stock vs Medicines Graph</h4>
                    <canvas id="stockGraph"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Stock Summary Table --}}
    <div class="row">
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

<script>
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
                    $('.total-sales').text((response.filteredTotalSales) +" "+"TZS");

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
</script>
