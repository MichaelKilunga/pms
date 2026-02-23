@extends('reports.app')

@section('content')
    <style>
        .analytics-card {
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
        }

        .analytics-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .metric-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .section-card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
        }

        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .table-analytics {
            font-size: 0.9rem;
        }

        .table-analytics th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border: none;
        }

        .chart-container {
            position: relative;
            height: 350px;
            margin: 20px 0;
        }

        .suggestion-item {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 12px;
            border-left: 4px solid;
            background: #f8f9fa;
        }

        .suggestion-maximize {
            border-left-color: #28a745;
            background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
        }

        .suggestion-minimize {
            border-left-color: #ffc107;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        }

        .suggestion-drop {
            border-left-color: #dc3545;
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner-border-custom {
            width: 4rem;
            height: 4rem;
            border-width: 0.4rem;
        }

        .gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
        }

        .gradient-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        }

        .gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .gradient-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay" style="display: none;">
            <div class="text-center">
                <div class="spinner-border spinner-border-custom text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h4 class="mt-3 text-primary">Analyzing Your Data...</h4>
                <p class="text-muted">This may take a few moments</p>
            </div>
        </div>

        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="fw-bold mb-2"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            <i class="bi bi-graph-up-arrow me-2"></i>Advanced Analytics Dashboard
                        </h1>
                        <p class="text-muted h5">Comprehensive insights to maximize your pharmacy's profitability</p>
                    </div>
                    <div>
                        <button class="btn btn-outline-primary" onclick="window.location.href='{{ route('reports.all') }}'">
                            <i class="bi bi-file-earmark-text me-2"></i>View Reports
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Selector -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card section-card">
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-calendar-range me-2"></i>Start Date
                                </label>
                                <input type="date" class="form-control form-control-lg" id="startDate" required>
                                <small class="text-muted">Minimum 1 month range required</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-calendar-check me-2"></i>End Date
                                </label>
                                <input type="date" class="form-control form-control-lg" id="endDate" required>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-lg w-100 text-white gradient-primary" id="generateAnalytics">
                                    <i class="bi bi-lightning-charge me-2"></i>Generate Analytics
                                </button>
                            </div>
                        </div>
                        <div id="dateError" class="alert alert-danger mt-3" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Content (Hidden until generated) -->
        <div id="analyticsContent" style="display: none;">

            <!-- Tab Navigation -->
            <ul class="nav nav-pills mb-4 justify-content-center bg-white p-3 rounded shadow-sm section-card" id="analyticsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-2 fw-bold" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab">
                        <i class="bi bi-speedometer2 me-2"></i>Summary
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2 fw-bold" id="inventory-tab" data-bs-toggle="pill" data-bs-target="#inventory" type="button" role="tab">
                        <i class="bi bi-boxes me-2"></i>Inventory
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2 fw-bold" id="analysis-tab" data-bs-toggle="pill" data-bs-target="#analysis" type="button" role="tab">
                        <i class="bi bi-graph-up-arrow me-2"></i>Performance
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2 fw-bold" id="financials-tab" data-bs-toggle="pill" data-bs-target="#financials" type="button" role="tab">
                        <i class="bi bi-wallet2 me-2"></i>Financials
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="analyticsTabContent">

                <!-- Tab 1: Summary -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <!-- Overview Metrics -->
                    <div class="row mb-4" id="overviewSection">
                        <div class="col-12 mb-3">
                            <h3 class="fw-bold"><i class="bi bi-speedometer2 me-2"></i>Performance Overview</h3>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card analytics-card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="metric-icon gradient-success text-white me-3">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-0 small">Total Sales</h6>
                                            <h3 class="fw-bold mb-0" id="totalSales">-</h3>
                                        </div>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar gradient-success" role="progressbar" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card analytics-card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="metric-icon gradient-primary text-white me-3">
                                            <i class="bi bi-graph-up"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-0 small">Gross Profit</h6>
                                            <h3 class="fw-bold mb-0" id="grossProfit">-</h3>
                                        </div>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar gradient-primary" role="progressbar" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card analytics-card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="metric-icon gradient-danger text-white me-3">
                                            <i class="bi bi-wallet2"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-0 small">Total Expenses</h6>
                                            <h3 class="fw-bold mb-0" id="totalExpenses">-</h3>
                                        </div>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar gradient-danger" role="progressbar" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card analytics-card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="metric-icon gradient-info text-white me-3">
                                            <i class="bi bi-trophy"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-muted mb-0 small">Net Profit</h6>
                                            <h3 class="fw-bold mb-0" id="netProfit">-</h3>
                                        </div>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar gradient-info" role="progressbar" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales by Time of Day -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h3 class="fw-bold"><i class="bi bi-clock-history me-2"></i>Sales Optimization</h3>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="card section-card">
                                <div class="card-header gradient-info text-white">
                                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Sales by Time of Day</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="salesByHourChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 mb-4">
                        <button class="btn btn-primary rounded-pill px-4" onclick="$('#inventory-tab').click(); window.scrollTo(0,0);">
                            Next: Inventory <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 2: Inventory -->
                <div class="tab-pane fade" id="inventory" role="tabpanel">
                    <!-- Stock Predictions Section -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h3 class="fw-bold"><i class="bi bi-boxes me-2"></i>Stock Predictions & Insights</h3>
                        </div>

                        <!-- Fast Moving Items -->
                        <div class="col-lg-6 mb-3">
                            <div class="card section-card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-rocket-takeoff me-2"></i>Fast-Moving Items</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="fastMovingDataTable" class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Medicine</th>
                                                    <th>Daily Sales</th>
                                                    <th>Stock Left</th>
                                                    <th>Days Left</th>
                                                </tr>
                                            </thead>
                                            <tbody id="fastMovingTable">
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No data available</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slow Moving Items -->
                        <div class="col-lg-6 mb-3">
                            <div class="card section-card h-100">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="bi bi-hourglass-split me-2"></i>Slow-Moving Items</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="slowMovingDataTable" class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Medicine</th>
                                                    <th>Daily Sales</th>
                                                    <th>Stock Left</th>
                                                    <th>Days Supply</th>
                                                </tr>
                                            </thead>
                                            <tbody id="slowMovingTable">
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No data available</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Urgent Restock -->
                        <div class="col-lg-6 mb-3">
                            <div class="card section-card h-100">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Urgent Restock Needed</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="urgentRestockDataTable" class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Medicine</th>
                                                    <th>Current Stock</th>
                                                    <th>Days Until Empty</th>
                                                    <th>Suggested Order</th>
                                                </tr>
                                            </thead>
                                            <tbody id="urgentRestockTable">
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No urgent restocks needed</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Overstocked Items -->
                        <div class="col-lg-6 mb-3">
                            <div class="card section-card h-100">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="bi bi-archive me-2"></i>Overstocked Items</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="overstockedDataTable" class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Medicine</th>
                                                    <th>Current Stock</th>
                                                    <th>Days Supply</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="overstockedTable">
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No overstocked items</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Improvement Suggestions Section -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h3 class="fw-bold"><i class="bi bi-lightbulb me-2"></i>Improvement Suggestions</h3>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="card section-card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-arrow-up-circle me-2"></i>Maximize These Items</h5>
                                    <small>High demand, high profit - stock more!</small>
                                </div>
                                <div class="card-body" id="maximizeList">
                                    <p class="text-muted text-center">No suggestions available</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="card section-card h-100">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="bi bi-arrow-down-circle me-2"></i>Minimize These Items</h5>
                                    <small>Slow-moving - reduce stock levels</small>
                                </div>
                                <div class="card-body" id="minimizeList">
                                    <p class="text-muted text-center">No suggestions available</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="card section-card h-100">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0"><i class="bi bi-x-circle me-2"></i>Consider Dropping</h5>
                                    <small>No sales or high expiry - discontinue</small>
                                </div>
                                <div class="card-body" id="dropList">
                                    <p class="text-muted text-center">No suggestions available</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 mb-4">
                        <button class="btn btn-outline-secondary rounded-pill px-4" onclick="$('#overview-tab').click(); window.scrollTo(0,0);">
                            <i class="bi bi-arrow-left"></i> Previous
                        </button>
                        <button class="btn btn-primary rounded-pill px-4" onclick="$('#analysis-tab').click(); window.scrollTo(0,0);">
                            Next: Performance <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 3: Performance -->
                <div class="tab-pane fade" id="analysis" role="tabpanel">
                    <!-- Profit Analysis & Forecasting -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h3 class="fw-bold"><i class="bi bi-currency-dollar me-2"></i>Profit Analysis & Forecasting</h3>
                        </div>

                        <div class="col-lg-8 mb-3">
                            <div class="card section-card">
                                <div class="card-header gradient-primary text-white">
                                    <h5 class="mb-0"><i class="bi bi-graph-up-arrow me-2"></i>Profit Trend & 30-Day Forecast</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="profitTrendChart"></canvas>
                                    </div>
                                    <div class="row text-center mt-3">
                                        <div class="col-6">
                                            <h6 class="text-muted">Avg Daily Profit</h6>
                                            <h4 class="fw-bold text-success" id="avgDailyProfit">-</h4>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="text-muted">30-Day Forecast</h6>
                                            <h4 class="fw-bold text-primary" id="profitForecast">-</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-3">
                            <div class="card section-card h-100">
                                <div class="card-header gradient-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-gem me-2"></i>High-Margin Products</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="highMarginDataTable" class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Medicine</th>
                                                    <th>Margin %</th>
                                                </tr>
                                            </thead>
                                            <tbody id="highMarginTable">
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted">No data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Performing Staff -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h3 class="fw-bold"><i class="bi bi-star me-2"></i>Top Performing Staff</h3>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="card section-card">
                                <div class="card-header gradient-warning text-white">
                                    <h5 class="mb-0"><i class="bi bi-star me-2"></i>Top Performing Staff</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="staffPerformanceDataTable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Staff Name</th>
                                                    <th>Sales</th>
                                                    <th>Revenue</th>
                                                    <th>Profit</th>
                                                </tr>
                                            </thead>
                                            <tbody id="staffPerformanceTable">
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No data available</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 mb-4">
                        <button class="btn btn-outline-secondary rounded-pill px-4" onclick="$('#inventory-tab').click(); window.scrollTo(0,0);">
                            <i class="bi bi-arrow-left"></i> Previous
                        </button>
                        <button class="btn btn-primary rounded-pill px-4" onclick="$('#financials-tab').click(); window.scrollTo(0,0);">
                            Next: Financials <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab 4: Financials -->
                <div class="tab-pane fade" id="financials" role="tabpanel">
                    <!-- Expense & Debt Analysis -->
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <h3 class="fw-bold"><i class="bi bi-wallet2 me-2"></i>Financial Health Analysis</h3>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="card section-card">
                                <div class="card-header gradient-danger text-white">
                                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Expense Breakdown</h5>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="expenseChart"></canvas>
                                    </div>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold">Expense to Revenue Ratio:</span>
                                            <span class="badge badge-custom bg-danger" id="expenseRatio">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="card section-card">
                                <div class="card-header bg-dark text-white">
                                    <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Debt Management Insights</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <h6 class="text-muted small">Total Debts</h6>
                                            <h5 class="fw-bold" id="totalDebts">-</h5>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="text-muted small">Paid</h6>
                                            <h5 class="fw-bold text-success" id="debtsPaid">-</h5>
                                        </div>
                                        <div class="col-4">
                                            <h6 class="text-muted small">Remaining</h6>
                                            <h5 class="fw-bold text-danger" id="debtsRemaining">-</h5>
                                        </div>
                                    </div>
                                    <div class="progress mb-3" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" id="debtPaymentProgress" style="width: 0%">
                                            <span id="debtPaymentRate">0%</span>
                                        </div>
                                    </div>
                                    <div id="overdueDebtsSection"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-start mt-4 mb-4">
                        <button class="btn btn-outline-secondary rounded-pill px-4" onclick="$('#analysis-tab').click(); window.scrollTo(0,0);">
                            <i class="bi bi-arrow-left"></i> Previous
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            let profitChart = null;
            let salesHourChart = null;
            let expenseChart = null;

            // Set default dates (last 3 months)
            const today = new Date();
            const threeMonthsAgo = new Date(today.getFullYear(), today.getMonth() - 3, today.getDate());

            $('#endDate').val(today.toISOString().split('T')[0]);
            $('#startDate').val(threeMonthsAgo.toISOString().split('T')[0]);

            // Generate Analytics
            $('#generateAnalytics').click(function() {
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();

                if (!startDate || !endDate) {
                    showError('Please select both start and end dates');
                    return;
                }

                // Validate minimum 1 month
                const start = new Date(startDate);
                const end = new Date(endDate);
                const daysDiff = Math.floor((end - start) / (1000 * 60 * 60 * 24));

                if (daysDiff < 30) {
                    showError(
                        'Please select a date range of at least 1 month (30 days) for accurate analytics'
                        );
                    return;
                }

                $('#dateError').hide();
                $('#loadingOverlay').show();

                $.ajax({
                    url: '{{ route('analytics.data') }}',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        if (response.success) {
                            renderAnalytics(response);
                            $('#analyticsContent').fadeIn();
                        } else {
                            showError(response.message || 'Failed to generate analytics');
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message ||
                            'An error occurred while generating analytics';
                        showError(message);
                    },
                    complete: function() {
                        $('#loadingOverlay').hide();
                    }
                });
            });

            function showError(message) {
                $('#dateError').text(message).show();
                setTimeout(() => $('#dateError').fadeOut(), 5000);
            }

            function formatCurrency(amount) {
                return new Intl.NumberFormat('en-TZ', {
                    style: 'currency',
                    currency: 'TZS',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(amount);
            }

            function initDataTable(selector) {
                if ($.fn.DataTable.isDataTable(selector)) {
                    $(selector).DataTable().destroy();
                }
                $(selector).DataTable({
                    paging: true,
                    pageLength: 5,
                    lengthMenu: [5, 10, 25, 50],
                    searching: true,
                    ordering: true,
                    info: false,
                    responsive: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search...",
                        paginate: {
                            previous: '<i class="bi bi-chevron-left"></i>',
                            next: '<i class="bi bi-chevron-right"></i>'
                        }
                    }
                });
            }

            function renderAnalytics(data) {
                // Overview Metrics
                $('#totalSales').text(formatCurrency(data.overview.total_sales));
                $('#grossProfit').text(formatCurrency(data.overview.gross_profit));
                $('#totalExpenses').text(formatCurrency(data.overview.total_expenses));
                $('#netProfit').text(formatCurrency(data.overview.net_profit));

                // Stock Predictions
                renderStockPredictions(data.stock_predictions);

                // Profit Predictions
                renderProfitPredictions(data.profit_predictions);

                // Improvement Suggestions
                renderImprovementSuggestions(data.improvement_suggestions);

                // Sales Optimization
                renderSalesOptimization(data.sales_optimization);

                // Expense Analysis
                renderExpenseAnalysis(data.expense_analysis);

                // Debt Insights
                renderDebtInsights(data.debt_insights);

                // Staff Performance
                renderStaffPerformance(data.staff_performance);
            }

            function renderStockPredictions(data) {
                // Fast Moving
                let fastHtml = '';
                data.fast_moving.forEach(item => {
                    const daysClass = item.days_until_depletion < 7 ? 'text-danger' : 'text-success';
                    fastHtml += `
                <tr>
                    <td class="fw-bold">${item.item_name}</td>
                    <td>${item.avg_daily_sales}</td>
                    <td>${item.current_stock}</td>
                    <td class="${daysClass}">${item.days_until_depletion} days</td>
                </tr>
            `;
                });
                $('#fastMovingTable').html(fastHtml ||
                    '<tr><td colspan="4" class="text-center text-muted">No data</td></tr>');

                // Slow Moving
                let slowHtml = '';
                data.slow_moving.forEach(item => {
                    slowHtml += `
                <tr>
                    <td class="fw-bold">${item.item_name}</td>
                    <td>${item.avg_daily_sales}</td>
                    <td>${item.current_stock}</td>
                    <td>${item.days_until_depletion} days</td>
                </tr>
            `;
                });
                $('#slowMovingTable').html(slowHtml ||
                    '<tr><td colspan="4" class="text-center text-muted">No data</td></tr>');

                // Urgent Restock
                let urgentHtml = '';
                data.urgent_restock.forEach(item => {
                    urgentHtml += `
                <tr class="table-danger">
                    <td class="fw-bold">${item.item_name}</td>
                    <td>${item.current_stock}</td>
                    <td class="text-danger fw-bold">${item.days_until_depletion} days</td>
                    <td class="text-success">${item.suggested_reorder_qty} units</td>
                </tr>
            `;
                });
                $('#urgentRestockTable').html(urgentHtml ||
                    '<tr><td colspan="4" class="text-center text-success">No urgent restocks needed</td></tr>');

                // Overstocked
                let overstockHtml = '';
                data.overstocked.forEach(item => {
                    overstockHtml += `
                <tr>
                    <td class="fw-bold">${item.item_name}</td>
                    <td>${item.current_stock}</td>
                    <td>${Math.round(item.days_until_depletion)} days</td>
                    <td><span class="badge bg-warning">Reduce Orders</span></td>
                </tr>
            `;
                });
                $('#overstockedTable').html(overstockHtml ||
                    '<tr><td colspan="4" class="text-center text-success">No overstocked items</td></tr>');

                // Initialize DataTables
                initDataTable('#fastMovingDataTable');
                initDataTable('#slowMovingDataTable');
                initDataTable('#urgentRestockDataTable');
                initDataTable('#overstockedDataTable');
            }

            function renderProfitPredictions(data) {
                $('#avgDailyProfit').text(formatCurrency(data.avg_daily_profit));
                $('#profitForecast').text(formatCurrency(data.forecast_30_days));

                // Profit Trend Chart
                // ... CHART LOGIC ...
                if (profitChart) profitChart.destroy();

                const ctx = document.getElementById('profitTrendChart').getContext('2d');
                profitChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.daily_profits.map(d => d.date),
                        datasets: [{
                            label: 'Daily Profit',
                            data: data.daily_profits.map(d => d.profit),
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'TZS ' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });

                // High Margin Products
                let marginHtml = '';
                data.high_margin_products.slice(0, 10).forEach(item => {
                    const badgeClass = item.profit_margin > 50 ? 'bg-success' : item.profit_margin > 30 ?
                        'bg-primary' : 'bg-secondary';
                    marginHtml += `
                <tr>
                    <td class="fw-bold small">${item.item_name}</td>
                    <td><span class="badge ${badgeClass}">${item.profit_margin}%</span></td>
                </tr>
            `;
                });
                $('#highMarginTable').html(marginHtml ||
                    '<tr><td colspan="2" class="text-center text-muted">No data</td></tr>');

                initDataTable('#highMarginDataTable');
            }

            function renderImprovementSuggestions(data) {
                // Maximize
                let maxHtml = '';
                data.maximize.forEach(item => {
                    maxHtml += `
                <div class="suggestion-item suggestion-maximize">
                    <h6 class="fw-bold mb-1">${item.item_name}</h6>
                    <small class="text-muted">
                        Margin: ${item.profit_margin.toFixed(1)}% | 
                        Daily Sales: ${item.avg_daily_sales.toFixed(1)} units
                    </small>
                </div>
            `;
                });
                $('#maximizeList').html(maxHtml ||
                '<p class="text-muted text-center">No suggestions available</p>');

                // Minimize
                let minHtml = '';
                data.minimize.forEach(item => {
                    minHtml += `
                <div class="suggestion-item suggestion-minimize">
                    <h6 class="fw-bold mb-1">${item.item_name}</h6>
                    <small class="text-muted">
                        Margin: ${item.profit_margin.toFixed(1)}% | 
                        Daily Sales: ${item.avg_daily_sales.toFixed(1)} units
                    </small>
                </div>
            `;
                });
                $('#minimizeList').html(minHtml ||
                '<p class="text-muted text-center">No suggestions available</p>');

                // Drop
                let dropHtml = '';
                data.drop.forEach(item => {
                    const reason = item.total_sold == 0 ? 'No sales' :
                        `${item.expiry_rate.toFixed(1)}% expiry rate`;
                    dropHtml += `
                <div class="suggestion-item suggestion-drop">
                    <h6 class="fw-bold mb-1">${item.item_name}</h6>
                    <small class="text-muted">${reason}</small>
                </div>
            `;
                });
                $('#dropList').html(dropHtml || '<p class="text-muted text-center">No suggestions available</p>');
            }

            function renderSalesOptimization(data) {
                // Sales by Hour Chart
                if (salesHourChart) salesHourChart.destroy();

                const hourCtx = document.getElementById('salesByHourChart').getContext('2d');
                salesHourChart = new Chart(hourCtx, {
                    type: 'bar',
                    data: {
                        labels: data.sales_by_hour.map(h => h.hour + ':00'),
                        datasets: [{
                            label: 'Revenue',
                            data: data.sales_by_hour.map(h => h.revenue),
                            backgroundColor: 'rgba(79, 172, 254, 0.7)',
                            borderColor: '#4facfe',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'TZS ' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function renderExpenseAnalysis(data) {
                $('#expenseRatio').text(data.expense_to_revenue_ratio.toFixed(1) + '%');

                // Expense Chart
                if (expenseChart) expenseChart.destroy();

                const expCtx = document.getElementById('expenseChart').getContext('2d');
                expenseChart = new Chart(expCtx, {
                    type: 'doughnut',
                    data: {
                        labels: data.by_category.map(c => c.category_name),
                        datasets: [{
                            data: data.by_category.map(c => c.total_amount),
                            backgroundColor: [
                                '#667eea', '#764ba2', '#f093fb', '#f5576c',
                                '#4facfe', '#00f2fe', '#56ab2f', '#a8e063'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            function renderDebtInsights(data) {
                $('#totalDebts').text(formatCurrency(data.total_debts));
                $('#debtsPaid').text(formatCurrency(data.total_paid));
                $('#debtsRemaining').text(formatCurrency(data.total_remaining));

                const paymentRate = data.payment_rate.toFixed(1);
                $('#debtPaymentProgress').css('width', paymentRate + '%');
                $('#debtPaymentRate').text(paymentRate + '%');

                // Overdue debts
                if (data.overdue_debts.length > 0) {
                    let overdueHtml =
                        '<h6 class="text-danger mt-3"><i class="bi bi-exclamation-circle me-2"></i>Overdue Debts</h6><ul class="list-unstyled">';
                    data.overdue_debts.slice(0, 5).forEach(debt => {
                        overdueHtml += `
                    <li class="small mb-2">
                        <strong>${debt.item_name}</strong> - ${formatCurrency(debt.remaining)} 
                        <span class="text-danger">(${debt.days_overdue} days overdue)</span>
                    </li>
                `;
                    });
                    overdueHtml += '</ul>';
                    $('#overdueDebtsSection').html(overdueHtml);
                }
            }

            function renderStaffPerformance(data) {
                let staffHtml = '';
                data.top_performers.forEach((staff, index) => {
                    const medalClass = index === 0 ? 'text-warning' : index === 1 ? 'text-secondary' :
                        'text-bronze';
                    const medal = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : '';
                    staffHtml += `
                <tr>
                    <td>${medal} ${staff.staff_name}</td>
                    <td>${staff.total_transactions}</td>
                    <td class="text-success">${formatCurrency(staff.total_revenue)}</td>
                    <td class="text-primary">${formatCurrency(staff.total_profit)}</td>
                </tr>
            `;
                });
                $('#staffPerformanceTable').html(staffHtml ||
                    '<tr><td colspan="4" class="text-center text-muted">No data available</td></tr>');

                initDataTable('#staffPerformanceDataTable');
            }
        });
    </script>
@endsection
