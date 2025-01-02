@extends('reports.app')

@section('content')
    <div class="container pt-2">
        <!-- Page Title -->
        <div class="row mb-4 text-center">
            {{-- <div class="col-6 col-md-4 col-lg-2"> --}}
            <h2 class="fw-bold h2 text-primary">Pharmacy Analytics & Reports</h2>
            <p class="text-muted h5">Monitor your transactions, trends, and financial activities here.</p>
            {{-- </div> --}}
        </div>
        <!-- Filters Section -->
        <div class="container">
            <div class="row mb-2 g-4# p-2 d-flex justify-content-center text-center form-control">
                <div class="col-12 col-md-6 col-lg-3 fs-4 text-success">
                    {{-- <div class="mb-2"></div> --}}
                    {{-- <label class="label" for="filterHead fs-4"></label> --}}
                    <p class="">Filter By:</p>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-2">
                    <label class="label" for="duration">Duration</label>
                    <select id="dateFilter" class="form-select" required>
                        <option selected value="">-- Select Duration --</option>
                        <option selected value="today">Today</option>
                        <option value="this_week">This Week</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="this_year">This Year</option>
                        <option value="custom_range">Custom Range</option>
                    </select>
                    <div class="dateDiv">
                        <input type="date" id="startDate" class="form-control rounded d-none bg-success text-light mt-2"
                            placeholder="Start Date">
                        <input type="date" id="endDate" class="form-control rounded d-none bg-success text-light mt-2"
                            placeholder="End Date">
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-2">
                    <label for="category">Category</label>
                    <select class="form-select onReport" name="category" id="category" required>
                        {{-- <option value="">-- Select Category --</option> --}}
                        <option selected value="sales">Sales</option>
                        <option value="purchases">Stock</option>
                        <option value="returns">Returns</option>
                        <option value="expired">Expired</option>
                        <option value="profit">Profit</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    {{-- <input type="text" class="form-control" placeholder="Search by ID or Name"> --}}
                    <label for="medicine">Medicine</label>
                    <select class="form-select onReport" name="medicine" id="medicine" required>
                        <option selected value="All Medicines">All Medicines</option>
                        @foreach ($medicines as $medicine)
                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <!-- Summary Cards -->
        <div class="row mb-2 g-4 justify-content-center text-center">
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Total Purchases</h6>
                        <h3 class="fw-bold text-danger">$12,000</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Total Sales</h6>
                        <h3 class="fw-bold text-primary">1,200</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Total Returns</h6>
                        <h3 class="fw-bold text-warning">50</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Total Profit</h6>
                        <h3 class="fw-bold text-success">$45,000</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table Section -->
        <div class="row mb-2 g-4 justify-content-center text-center">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle" id="reportsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Pharmacy</th>
                                <th>Transaction Type</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-12-28</td>
                                <td>Pharmacy A</td>
                                <td>Sale</td>
                                <td>$1,200</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- 
            <!-- Download Buttons -->
            <div class="row mb-4 g-4 justify-content-center text-center">
                <div class="col text-center text-md-end">
                    <button class="btn btn-danger mx-2" id="downloadPdf">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </button>
                    <button class="btn btn-success mx-2" id="downloadCsv">
                        <i class="bi bi-file-earmark-spreadsheet"></i> CSV
                    </button>
                </div>
            </div> 
        --}}

        <!-- Chart Section -->
        <div class="row mb-4 g-4 justify-content-center text-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title" id="chartHead">Trends</h6>
                        <canvas id="transactionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var  data = [1200, 1900, 3000, 5000, 2000];
            var labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];
            drawGraph(labels, data);
            $('.dateDiv').addClass('hidden');
            var medicine = $('#medicine').val();
            var selectedMedicineName = $('#medicine').find(':selected').text();
            var category = $('#category').val();
            var selectedCategoryName = $('#category').find(':selected').text();
            const today = new Date();
            const startOfWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - today
                .getDay());
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const startOfLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const endOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            const startOfYear = new Date(today.getFullYear(), 0, 1);

            //CAPTURE CATEGORY & MEDICINES FILTERS
            $('#category').on('change', function() {
                category = $(this).val();
                selectedCategoryName = $(this).find(':selected').text();
                $('#dateFilter').trigger('change');
            });

            $('#medicine').on('change', function() {
                medicine = $(this).val();
                selectedMedicineName = $(this).find(':selected').text();
                $('#dateFilter').trigger('change');
            });

            // Handle filter changes
            $('#dateFilter').on('change', function() {
                const value = $(this).val();
                $('#startDate, #endDate').addClass('d-none');

                switch (value) {
                    case 'today':
                        console.log('Filtering for Today');
                        filterData(formatDate(today), formatDate(today), category, medicine);
                        break;
                    case 'this_week':
                        console.log('Filtering for This Week');
                        filterData(formatDate(startOfWeek), formatDate(new Date()), category, medicine);
                        break;
                    case 'this_month':
                        console.log('Filtering for This Month');
                        filterData(formatDate(startOfMonth), formatDate(new Date()), category, medicine);
                        break;
                    case 'last_month':
                        console.log('Filtering for Last Month');
                        filterData(formatDate(startOfLastMonth), formatDate(endOfLastMonth), category,
                            medicine);
                        break;
                    case 'this_year':
                        console.log('Filtering for This Year');
                        filterData(formatDate(startOfYear), formatDate(new Date()), category, medicine);
                        break;
                    case 'custom_range':
                        console.log('Custom Range Selected');
                        $('.dateDiv').removeClass('hidden');
                        $('#startDate, #endDate').removeClass('d-none');
                        break;
                }
            });
            // Listen for custom date range inputs
            $('#startDate, #endDate').on('change', function() {
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();
                if (startDate && endDate) {
                    // console.log(`Filtering from ${startDate} to ${endDate}`);
                    filterData(startDate, endDate, category, medicine);
                }
            });
            // Format date to YYYY-MM-DD
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
            // Example filterData function
            function filterData(start, end, category, medicine) {

                // Add your AJAX request or data filtering logic here


                $('#chartHead').html(
                    `<u>${selectedCategoryName}</u> trends of <u>${selectedMedicineName}</u> <br> From: <span class="text-primary">${start}</span> | To: <span class="text-primary">${end}<span>`
                    );
                    drawGraph(labels, data);
            }
            // Initialize Chart.js
            function drawGraph(labels, data) {
                var ctx = $('#transactionChart')[0].getContext('2d');
                var transactionChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Transactions',
                            data: data,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
