@extends('reports.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">Pharmacy Analytics & Reports</h2>
            <p class="text-muted">Monitor your transactions, trends, and financial activity.</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6 mb-2">
            <input type="text" id="dateRange" class="form-control" placeholder="Select Date Range">
        </div>
        <div class="col-lg-3 col-md-6 mb-2">
            <select class="form-select">
                <option selected>All Pharmacies</option>
                <option value="1">Pharmacy A</option>
                <option value="2">Pharmacy B</option>
            </select>
        </div>
        <div class="col-lg-3 col-md-6 mb-2">
            <select class="form-select">
                <option selected>All Categories</option>
                <option value="sales">Sales</option>
                <option value="purchases">Stock</option>
                <option value="returns">Returns</option>
                <option value="expired">Expired</option>
                <option value="returns">Profit</option>
            </select>
        </div>
        <div class="col-lg-3 col-md-6 mb-2">
            <input type="text" class="form-control" placeholder="Search by ID or Name">
        </div>
    </div>

    <!-- Download Buttons -->
    <div class="row mb-4">
        <div class="col-md-3">
            <button class="btn btn-primary w-100 mb-2" id="downloadPdf">Download PDF</button>
        </div>
        <div class="col-md-3">
            <button class="btn btn-secondary w-100 mb-2" id="downloadCsv">Download CSV</button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Purchases</h6>
                    <h3 class="fw-bold text-danger">$12,000</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Sales</h6>
                    <h3 class="fw-bold text-primary">1,200</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Returns</h6>
                    <h3 class="fw-bold text-warning">50</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Total Profit</h6>
                    <h3 class="fw-bold text-success">$45,000</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <table class="table table-hover align-middle">
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
                    <!-- Example Row -->
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
                    <!-- Repeat for other rows -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Transaction Trends</h6>
                    <canvas id="transactionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Include Date Range Picker and Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Initialize Datepicker
    $('#dateRange').daterangepicker({
        opens: 'left',
        autoApply: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });

    // Initialize Chart.js for Trends
    var ctx = document.getElementById('transactionChart').getContext('2d');
    var transactionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Transactions',
                data: [1200, 1900, 3000, 5000, 2000],
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
            }
        }
    });

    // Download Report as PDF
    document.getElementById('downloadPdf').addEventListener('click', function() {
        alert('PDF download functionality is pending implementation.');
        // Use a library like jsPDF or implement server-side PDF generation
    });

    // Download Report as CSV
    document.getElementById('downloadCsv').addEventListener('click', function() {
        alert('CSV download functionality is pending implementation.');
        // Implement logic to generate CSV and download
    });
</script>
@endpush
