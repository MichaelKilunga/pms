{{-- <div
    class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
</div>

<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8"></div> --}}

{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}
    <div class="container mt-4">
        <h1 class="mb-4">Dashboard</h1>

        {{-- Summary Section --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h4>Total Medicines</h4>
                        <p class="fs-4">{{ $totalMedicines }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h4>Total Sales</h4>
                        <p class="fs-4">{{ $totalSales }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h4>Low Stock Medicines</h4>
                        <p class="fs-4">{{ $lowStockCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions Section --}}
        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h4>Quick Actions</h4>
                        <div class="d-flex justify-content-start mt-3">
                            <a href="{{ route('medicines.create') }}" class="btn btn-success me-2">Add Medicine</a>
                            <a href="{{ route('sales') }}" class="btn btn-primary me-2">View Sales</a>
                            <a href="{{ route('stock') }}" class="btn btn-warning">Low Stock</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales vs Medicine Bar Graph and Summary Stock Table --}}
        <div class="row">
            {{-- Sales vs Medicine Bar Graph --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4>Sales vs Medicines</h4>
                        <canvas id="salesMedicineChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Summary Stock Table --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4>Stock Summary</h4>
                        <table class="table table-striped" id="Table">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medicines as $medicine)
                                    <tr>
                                        <td>{{ $medicine->name }}</td>
                                        <td>{{ $medicine->stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- @endsection --}}

{{-- @push('scripts') --}}
    {{-- Include Chart.js --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesMedicineChart').getContext('2d');
            const salesMedicineChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($medicineNames) !!}, // Pass medicine names from the controller
                    datasets: [
                        {
                            label: 'Sales',
                            data: {!! json_encode($medicineSales) !!}, // Pass sales data from the controller
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Stock',
                            data: {!! json_encode($medicineStock) !!}, // Pass stock data from the controller
                            backgroundColor: 'rgba(255, 206, 86, 0.6)',
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
{{-- @endpush --}}
