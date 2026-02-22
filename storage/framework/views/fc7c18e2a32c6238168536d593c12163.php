<?php $__env->startSection('content'); ?>
    <style>
        .reportTable {
            max-width: 100%;
            overflow-x: auto;
            position: relative;
        }

        .reportsTable {
            width: 100% !important;
        }

        #DivchartHead canvas {
            max-height: 400px;
        }
    </style>
    <div class="container pt-2">
        <!-- Page Title -->
        <div class="row mb-4 text-center">
            
            <h2 class="fw-bold h2 text-primary">Pharmacy Analytics & Reports</h2>
            <p class="text-muted h5">Monitor your transactions, trends, and financial activities here.</p>
            
        </div>
        <!-- Filters Section -->
        <div class="container">
            <div class="row g-4 d-flex justify-content-center form-control mb-2 p-2 text-center">
                <div class="col-12 col-md-6 col-lg-3 fs-4 text-success">
                    <p class="">Filter By:</p>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-2">
                    <label class="label" for="duration">Duration</label>
                    <select class="form-select" id="dateFilter" required>
                        <option selected value="">-- Select Duration --</option>
                        <option selected value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="this_week">This Week</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="this_year">This Year</option>
                        <option value="custom_range">Custom Range</option>
                    </select>
                    <div class="dateDiv">
                        <input class="form-control d-none bg-success text-light mt-2 rounded" id="startDate"
                            placeholder="Start Date" type="date">
                        <input class="form-control d-none bg-success text-light mt-2 rounded" id="endDate"
                            placeholder="End Date" type="date">
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb-2">
                    <label for="category">Category</label>
                    <select class="onReport form-select" id="category" name="category" required>
                        
                        <option selected value="sales">Sales</option>
                        <option value="stocks">Stock</option>
                        <option value="expenses">Expenses</option>
                        <option value="debts">Debts</option>
                        <option value="installments">Installments</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3" id="medicineDiv">
                    
                    <label for="medicine">Medicine</label>
                    <select class="onReport form-select" id="medicine" name="medicine" required>
                        <option selected value="0">All Medicines</option>
                        <?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($medicine->id); ?>"><?php echo e($medicine->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 justify-content-center mb-4 text-center">
            <div class="col-6 col-md-4 col-lg-2" id="totalStocksDiv">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body p-2">
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-box-seam fs-5"></i>
                        </div>
                        <h6 class="text-muted small mb-1">Total Stocks</h6>
                        <h4 class="fw-bold mb-0 text-dark" id="totalStocks">fetching...</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2" id="totalSalesDiv">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body p-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-cart-check fs-5"></i>
                        </div>
                        <h6 class="text-muted small mb-1">Total Sales</h6>
                        <h4 class="fw-bold mb-0 text-dark" id="totalSales">fetching...</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2" id="totalReturnsDiv">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body p-2">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-arrow-return-left fs-5"></i>
                        </div>
                        <h6 class="text-muted small mb-1">Total Returns</h6>
                        <h4 class="fw-bold mb-0 text-dark" id="totalReturns">fetching...</h4>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2" id="totalExpensesDiv">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body p-2">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-cash-stack fs-5"></i>
                        </div>
                        <h6 class="text-muted small mb-1">Total Expenses</h6>
                        <h4 class="fw-bold mb-0 text-dark" id="totalExpenses">fetching...</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 hidden" id="totalDebtDiv">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body p-2">
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-person-exclamation fs-5"></i>
                        </div>
                        <h6 class="text-muted small mb-1">Total Debt</h6>
                        <h4 class="fw-bold mb-0 text-dark" id="totalDebt">fetching...</h4>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2 hidden" id="totalDeptsPaidDiv">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body p-2">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-check2-circle fs-5"></i>
                        </div>
                        <h6 class="text-muted small mb-1">Total Debt Paid</h6>
                        <h4 class="fw-bold mb-0 text-dark" id="totalDeptsPaid">fetching...</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 hidden" id="totalDeptsRemainingDiv">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body p-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                        <h6 class="text-muted small mb-1">Totat Dept Remain</h6>
                        <h4 class="fw-bold mb-0 text-dark" id="totalDeptsRemaining">fetching...</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 hidden" id="totalDebtsDiv">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body p-2">
                        <div class="rounded-circle bg-dark bg-opacity-10 text-dark d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-list-check fs-5"></i>
                        </div>
                        <h6 class="text-muted small mb-1">Total Debts</h6>
                        <h4 class="fw-bold mb-0 text-dark" id="totalDebts">fetching...</h4>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2" id="totalInstallmentsDiv">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body p-2">
                        <div class="rounded-circle bg-info bg-opacity-10 text-info d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-credit-card fs-5"></i>
                        </div>
                        <h6 class="text-muted small mb-1">Total Installments</h6>
                        <h4 class="fw-bold mb-0 text-dark" id="totalInstallments">fetching...</h4>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4 col-lg-2" id="totalProfitDiv">
                <div class="card border-0 shadow-sm h-100 py-2">
                    <div class="card-body p-2">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-graph-up-arrow fs-5"></i>
                        </div>
                        <h6 class="text-muted small mb-1">Net Profit</h6>
                        <h4 class="fw-bold mb-0 text-dark" id="totalProfit">fetching...</h4>
                    </div>
                </div>
            </div>

            
            <div class="col-6 col-md-4 col-lg-2" id="reportSendDiv">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Send Report</h6>
                        
                        <form action="<?php echo e(route('reports.send')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input id="report_start_date" name="start_date" type="hidden">
                            <input id="report_end_date" name="end_date" type="hidden">
                            <div class="d-flex justify-content-between gap-1">
                                <button class="d-flex gap-1 small btn btn-primary" type="submit"><i
                                        class="fab fa-whatsapp"></i> <i class="fas fa-envelope"></i> <i
                                        class="fas fa-comments"></i></button>
                                <button class="btn btn-success" name="channel" type="submit" value="whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table Section -->
        <div class="row g-4 justify-content-center mb-2 text-center">
            <div class="col-12">
                <div class="table-responsive reportTable">
                    <i>Table will appear here!!!</i>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row g-4 justify-content-center mb-4 text-center" id="DivchartHead">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-primary">Top 10 Most Sold Medicines</h5>
                        <div style="height: 400px;">
                            <canvas id="topSalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 text-danger">Bottom 10 Sold Medicines</h5>
                        <div style="height: 400px;">
                            <canvas id="bottomSalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
    <script>
        $(document).ready(function() {
            var topSalesChart = null;
            var bottomSalesChart = null;
            var cachedLogo = null;
            $('.dateDiv').addClass('hidden');
            var medicine = $('#medicine').val();
            var selectedMedicineName = $('#medicine').find(':selected').text();
            var category = $('#category').val();
            var selectedCategoryName = $('#category').find(':selected').text();
            var currentGrossProfit = 0;

            // yesterday date calculation
            function getYesterdayDate() {
                const today = new Date();
                const yesterday = new Date(today);
                yesterday.setDate(today.getDate() - 1);
                return yesterday;
            }
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

                //if category is expenses selected hide the medicine filter
                if (category === 'expenses' || category === 'debts' || category === 'installments') {
                    $('#medicineDiv').addClass('hidden');
                    $('#DivchartHead').addClass('hidden');
                }

                if (category === 'sales' || category === 'returns' || category === 'stocks') {
                    $('#medicineDiv').removeClass('hidden');
                    $('#totalExpensesDiv').removeClass('hidden'); // Show expenses in sales
                    $('#totalDebtDiv').addClass('hidden');
                    //add class hidden for totalDeptsRemainingDiv,totalDeptsPaidDiv,totalDebtsDiv
                    $('#totalDeptsPaidDiv').addClass('hidden');
                    $('#totalDeptsRemainingDiv').addClass('hidden');
                    $('#totalDebtsDiv').addClass('hidden');

                    // remove  hidden to total total, totalSales,totalReturns,totalStocks
                    $('#totalProfitDiv').removeClass('hidden');
                    $('#totalSalesDiv').removeClass('hidden');
                    $('#totalReturnsDiv').removeClass('hidden');
                    $('#totalStocksDiv').removeClass('hidden');

                    // show installments in sales
                    $('#totalInstallmentsDiv').removeClass('hidden');

                    //remove hidden class to div graph
                    // $('#DivchartHead').removeClass('hidden');
                }
                if (category === 'sales') {
                    //remove hidden class to div graph
                    $('#DivchartHead').removeClass('hidden');
                }
                if (category === 'stocks') {
                    //remove hidden class to div graph
                    $('#DivchartHead').addClass('hidden');
                }

                if (category === 'expenses') {
                    //show totalExpensesDiv
                    $('#totalExpensesDiv').removeClass('hidden');
                    //hide totalDebtDiv
                    $('#totalDebtDiv').addClass('hidden');
                    //hide totalDeptsRemainingDiv,totalDeptsPaidDiv,totalDebtsDiv
                    $('#totalDeptsPaidDiv').addClass('hidden');
                    $('#totalDeptsRemainingDiv').addClass('hidden');
                    $('#totalDebtsDiv').addClass('hidden');
                    //hide totalReturn,totalStocks,totalSales,totalProfit
                    $('#totalProfitDiv').addClass('hidden');
                    $('#totalSalesDiv').addClass('hidden');
                    $('#totalReturnsDiv').addClass('hidden');
                    $('#totalStocksDiv').addClass('hidden');

                    //hide installments
                    $('#totalInstallmentsDiv').addClass('hidden');

                }

                if (category === 'debts') {
                    //show totalDebtDiv
                    $('#totalDebtDiv').removeClass('hidden');
                    $('#totalDeptsPaidDiv').removeClass('hidden');
                    $('#totalDeptsRemainingDiv').removeClass('hidden');
                    //hide totalExpensesDiv
                    $('#totalExpensesDiv').addClass('hidden');
                    // hide totalSales,totalReturns,totalStocks,totalProfit
                    $('#totalSalesDiv').addClass('hidden');
                    $('#totalReturnsDiv').addClass('hidden');
                    $('#totalStocksDiv').addClass('hidden');
                    $('#totalProfitDiv').addClass('hidden');

                    //hide installments
                    $('#totalInstallmentsDiv').addClass('hidden');
                }

                if (category === 'installments') {
                    $('#totalInstallmentsDiv').removeClass('hidden');
                    // hide totalSales,totalReturns,totalStocks,totalProfit
                    $('#totalSalesDiv').addClass('hidden');
                    $('#totalReturnsDiv').addClass('hidden');
                    $('#totalStocksDiv').addClass('hidden');
                    $('#totalProfitDiv').addClass('hidden');

                    //hide totalDeptsRemainingDiv,totalDeptsPaidDiv,totalDebtsDiv
                    $('#totalDebtDiv').addClass('hidden');
                    $('#totalDeptsPaidDiv').addClass('hidden');
                    $('#totalDeptsRemainingDiv').addClass('hidden');
                    $('#totalDebtsDiv').addClass('hidden');

                    //hide totalExpensesDiv
                    $('#totalExpensesDiv').addClass('hidden');
                }
            });


            $('#medicine').on('change', function() {
                medicine = $(this).val();
                selectedMedicineName = $(this).find(':selected').text();
                $('#dateFilter').trigger('change');
            });

            // Handle filter changes
            $('#dateFilter').on('change', function() {
                const value = $(this).val();
                console.log(value);
                // console.log(category);
                $('#startDate, #endDate').addClass('d-none');


                switch (value) {
                    case 'today':
                        console.log('Filtering for Today');
                        $('#report_start_date').val(formatDate(today));
                        $('#report_end_date').val(formatDate(today));
                        // $('#reportSendDiv').removeClass('hidden');
                        if (category === 'expenses' || category === 'debts' || category ===
                            'installments') {
                            filterData(formatDate(today), formatDate(today), category);
                        } else {
                            filterData(formatDate(today), formatDate(today), category, medicine);
                        }

                        break;
                    case 'yesterday':
                        const yesterday = getYesterdayDate();
                        console.log('Filtering for Yesterday');
                        $('#report_start_date').val(formatDate(yesterday));
                        $('#report_end_date').val(formatDate(yesterday));
                        // $('#reportSendDiv').removeClass('hidden');
                        if (category === 'expenses' || category === 'debts' || category ===
                            'installments') {
                            filterData(formatDate(yesterday), formatDate(yesterday), category);
                        } else {
                            filterData(formatDate(yesterday), formatDate(yesterday), category, medicine);
                        }
                        break;
                    case 'this_week':
                        console.log('Filtering for This Week');
                        $('#report_start_date').val(formatDate(startOfWeek));
                        $('#report_end_date').val(formatDate(new Date()));
                        // $('#reportSendDiv').removeClass('hidden');
                        if (category === 'expenses' || category === 'debts' || category ===
                            'installments') {
                            filterData(formatDate(startOfWeek), formatDate(new Date()), category);
                        } else {
                            filterData(formatDate(startOfWeek), formatDate(new Date()), category, medicine);
                        }
                        break;
                    case 'this_month':
                        console.log('Filtering for This Month');
                        $('#report_start_date').val(formatDate(startOfMonth));
                        $('#report_end_date').val(formatDate(new Date()));
                        // $('#reportSendDiv').removeClass('hidden');
                        if (category === 'expenses' || category === 'debts' || category ===
                            'installments') {
                            filterData(formatDate(startOfMonth), formatDate(new Date()), category);
                        } else {
                            filterData(formatDate(startOfMonth), formatDate(new Date()), category,
                                medicine);
                        }
                        break;
                    case 'last_month':
                        console.log('Filtering for Last Month');
                        $('#report_start_date').val(formatDate(startOfLastMonth));
                        $('#report_end_date').val(formatDate(endOfLastMonth));
                        // $('#reportSendDiv').removeClass('hidden');
                        if (category === 'expenses' || category === 'debts' || category ===
                            'installments') {
                            filterData(formatDate(startOfLastMonth), formatDate(endOfLastMonth), category);
                        } else {
                            filterData(formatDate(startOfLastMonth), formatDate(endOfLastMonth), category,
                                medicine);
                        }
                        break;
                    case 'this_year':
                        console.log('Filtering for This Year');
                        $('#report_start_date').val(formatDate(startOfYear));
                        $('#report_end_date').val(formatDate(new Date()));
                        // $('#reportSendDiv').removeClass('hidden');
                        if (category === 'expenses' || category === 'debts' || category ===
                            'installments') {
                            filterData(formatDate(startOfYear), formatDate(new Date()), category);
                        } else {
                            filterData(formatDate(startOfYear), formatDate(new Date()), category, medicine);
                        }
                        break;
                    case 'custom_range':
                        console.log('Custom Range Selected');
                        $('#report_start_date').val('');
                        $('#report_end_date').val('');
                        $('.dateDiv').removeClass('hidden');
                        $('#startDate, #endDate').removeClass('d-none');
                        // $('#reportSendDiv').addClass('hidden');
                        break;
                }
            });

            // Listen for custom date range inputs
            $('#startDate, #endDate').on('change', function() {
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();
                if (startDate && endDate) {
                    // $('#reportSendDiv').removeClass('hidden');
                    $('#report_start_date').val(startDate);
                    $('#report_end_date').val(endDate);
                    // console.log(`Filtering from ${startDate} to ${endDate}`);
                    if (category === 'expenses' || category === 'debts' || category === 'installments') {
                        filterData(startDate, endDate, category);
                    } else {
                        filterData(startDate, endDate, category, medicine);
                    }
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
            function filterData(start, end, category, medicine = null) {
                if ($.fn.DataTable.isDataTable('.reportsTable')) {
                    $('.reportsTable').DataTable().destroy();
                }
                $('#loader-overlay').show(); // show loader initially
                var transactionChart = null;
                // Add your AJAX request or data filtering logic here
                $.ajax({
                    url: '/filterReports',
                    method: 'GET',
                    data: {
                        start: start,
                        end: end,
                        category: category,
                        medicine: medicine // NULL for expenses/debts
                    },
                    dataType: 'json',
                    success: function(response) {

                        console.log("Response received:", response);
                        // --- FIX HERE ---
                        response.sales = response.sales || [];
                        response.stocks = response.stocks || [];
                        response.expenses = response.expenses || [];
                        response.debts = response.debts || [];
                        response.installments = response.installments || [];
                        response.totalExpenses = response.totalExpenses || 0;
                        response.totalInstallments = response.totalInstallments || 0;
                        response.topLabels = response.topLabels || [];
                        response.topData = response.topData || [];
                        response.bottomLabels = response.bottomLabels || [];
                        response.bottomData = response.bottomData || [];
                        // ----------------

                        //capture response type
                        if (response.success) {
                            $('#loader-overlay').hide(); // Hide loader
                            // Update the summary cards with 0 decimal points
                            $('#totalStocks').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalStocks));

                            $('#totalSales').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalSales));

                            $('#totalProfit').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalProfit));

                            $('#totalReturns').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalReturns));

                            // Update total expenses and total debt
                            $('#totalExpenses').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalExpenses));

                            $('#totalDebt').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalDebts));

                            currentGrossProfit = response.grossProfit;
                            $('#totalDeptsRemaining').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalDeptsRemaining));
                            $('#totalDeptsPaid').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalDeptsPaid));

                            //installments
                            $('#totalInstallments').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalInstallments));

                            //place the table into block with id="reportsTable", use returned data on bases of category filtered
                            //start with table when category if sales

                            var salesTable = `
                                <table class="table table-striped table-bordered table-hover reportsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Medicine</th>
                                            <th>Quantity</th>
                                            <th>Amount</th>
                                            <th>Profit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${response.sales.map((sale, index) => `
                                                                                                                                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                                                                                                                                        <td>${index + 1}</td>
                                                                                                                                                                                                                                                                                                                                                                                        <td>${sale.date}</td>
                                                                                                                                                                                                                                                                                                                                                                                        <td class ="text-left">${sale.item['name']}</td>
                                                                                                                                                                                                                                                                                                                                                                                        <td>${sale.quantity}</td>
                                                                                                                                                                                                                                                                                                                                                                                       <td class="text-left">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS' }).format(sale.quantity * sale.stock['selling_price'])}</td>
                                                                                                                                                                                                                                                                                                                                                                                       <td class="text-left">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS' }).format(sale.quantity *((sale.stock['selling_price'])-(sale.stock['buying_price'])))}</td>

                                                                                                                                                                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                                                                                                                                                                `).join('')}
                                            ${response.sales.length == 0 ? ` <tr> <td colspan="6" class="text-center">No data found</td> </tr> ` : ''}
                                             
                                            </tbody>
                                </table>
                            `;

                            //lets implement table when category is stocks, the stocks table should list (serial number(#), Batch number, stocked quantity, remained quantity, total sales amount made, total profit made, and total expired loss amount)
                            var stocksTable = `
                                <table class="table table-striped table-bordered table-hover reportsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Medicine</th>
                                            <th>Stocked Quantity</th>
                                            <th>Remained Quantity</th>
                                            <th>Buying Price</th>
                                            <th>Selling Price</th>
                                            <th>Low Stock When</th>
                                            <th>Expiry Date</th>
                                            <th>Batch Number</th>
                                            <th>Supplier name</th>
                                            <th>Total Sales</th>
                                            <th>Total Profit</th>
                                            <th>Expired Loss</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${response.stocks.map((stock, index) => `
                                                                                                                                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                                                                                                                                            <td>${index + 1}</td>
                                                                                                                                                                                                                                                                                                                                                                                            <td class="text-left">${stock.item['name']}</td>
                                                                                                                                                                                                                                                                                                                                                                                            <td>${stock.quantity}</td>
                                                                                                                                                                                                                                                                                                                                                                                            <td>${stock.remain_Quantity}</td>
                                                                                                                                                                                                                                                                                                                                                                                            <td>${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(stock.buying_price)}</td>
                                                                                                                                                                                                                                                                                                                                                                                            <td>${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(stock.selling_price)}</td>
                                                                                                                                                                                                                                                                                                                                                                                            <td>${stock.low_stock_percentage}</td>
                                                                                                                                                                                                                                                                                                                                                                                            <td>${stock.expire_date}</td>
                                                                                                                                                                                                                                                                                                                                                                                            <td>${stock.batch_number}</td>
                                                                                                                                                                                                                                                                                                                                                                                            <td>${stock.supplier}</td> 
                                                                                                                                                                                                                                                                                                                                                                                            <td>${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(stock.selling_price*(stock.quantity-stock.remain_Quantity))}</td>
                                                                                                                                                                                                                                                                                                                                                                                            <td>${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format((stock.quantity-stock.remain_Quantity)*(stock.selling_price-stock.buying_price))}</td>
                                                                                                                                                                                                                                                                                                                                                                                            ${stock.expire_date < today ? `<td>${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(stock.buying_price*stock.remain_Quantity)}</td>`:`<td>Tsh 0</td>`}
                                                                                                                                                                                                                                                                                                                                                                                        </tr>`).join('')}
                                            ${response.stocks.length == 0 ? `<tr><td colspan="8" class="text-center">No data found</td></tr>` : ''}
                                         
                                    </tbody>
                                </table>
                             `;

                            //implement table when category is expenses
                            var expensesTable = `
                              <table class="table table-striped table-bordered table-hover reportsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Category</th>
                                            <th>Vendor</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            ${response.expenses.map((expense, index) => `
                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                            <td>${index + 1}</td>
                                                                                                                                                                                                            <td>${formatReadableDate(expense.expense_date)}</td>
                                                                                                                                                                                                            <td class="text-left">${expense.category?.name ?? 'N/A'}</td>
                                                                                                                                                                                                            <td class="text-left">${expense.vendor?.name ?? 'N/A'}</td>
                                                                                                                                                                                                            <td>${expense.payment_method}</td>
                                                                                                                                                                                                            <td class="text-left">${expense.status}</td>
                                                                                                                                                                                                            <td class="text-left">${new Intl.NumberFormat('en-TZ', { 
                                                                                                                                                                                                                style: 'currency', 
                                                                                                                                                                                                                currency: 'TZS' 
                                                                                                                                                                                                            }).format(expense.amount)}</td>
                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                    `).join('')}

                                            ${response.expenses.length == 0 ? `<tr><td colspan="7" class="text-center">No data found</td></tr>` : ''}
                                         
                                    </tbody>
                                </table>
                             `;

                            //implement table when category is debt
                            var debtTable = `
                             <table class="table table-striped table-bordered table-hover reportsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Medicine & Batch</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                                <th>Status</th>
                                                 <th>Debt Amount</th>
                                                 <th>Paid Dept</th>
                                                <th>Remain Dept</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${response.debts.map((debt, index) => `
                                                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                                                        <td>${index + 1}</td>
                                                                                                                                                                                                                                                                                                        <td class="text-left">
                                                                                                                                                                                                                                                                                                        <strong>${debt.stock?.item?.name || 'N/A'}</strong> | 
                                                                                                                                                                                                                                                                                                        <small>${debt.stock?.batch_number || 'N/A'} (${debt.stock?.supplier || 'N/A'})</small>
                                                                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                                                                        <td>${formatReadableDate(debt.created_at)}</td>
                                                                                                                                                                                                                                                                                                        <td>${formatReadableDate(debt.updated_at)}</td>
                                                                                                                                                                                                                                                                                                        <td>${debt.status}</td>
                                                                                                                                                                                                                                                                                                        <td class="text-left">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS' }).format(debt.debtAmount)}</td>
                                                                                                                                                                                                                                                                                                        <td class="text-left">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS' }).format(debt.totalPaid)}</td>
                                                                                                                                                                                                                                                                                                        <td class="text-left">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS' }).format(debt.debtAmount - debt.totalPaid)}</td>
                                                                                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                                                                                `).join('')}
                                                ${response.debts.length == 0 ? `<tr><td colspan="10" class="text-center">No data found</td></tr>` : ''}
                                             
                                        </tbody>
                            </table>
                             `;

                            //implement table when category is installment
                            var installmentTable = `
                                <table class="table table-striped table-bordered table-hover reportsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Stock</th>
                                            <th>Description</th>
                                            <th>Created Date</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${response.installments.map((installment, index) => `
                                                                                                                                                                                                                                <tr>
                                                                                                                                                                                                                                    <td>${index + 1}</td>
                                                                                                                                                                                                                                    <td class="text-left">
                                                                                                                                                                                                                                        <strong>${installment.debt?.stock?.item?.name || 'N/A'}</strong> | 
                                                                                                                                                                                                                                        <small>${installment.debt?.stock?.batch_number || 'N/A'} (${installment.debt?.stock?.supplier || 'N/A'})</small>
                                                                                                                                                                                                                                    </td>
                                                                                                                                                                                                                                    <td class="text-left">${installment.description}</td>
                                                                                                                                                                                                                                    <td>${formatReadableDate(installment.created_at)}</td>
                                                                                                                                                                                                                                    <td class="text-left">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS' }).format(installment.amount)}</td>
                                                                                                                                                                                                                                </tr>
                                                                                                                                                                                                                        `).join('')}
                                            ${response.installments.length == 0 ? `<tr><td colspan="5" class="text-center">No data found</td></tr>` : ''}
                                         
                                    </tbody>
                                </table>
                                    `;


                            //place the table into block with id="reportsTable", use returned data on bases of category filtered
                            if (category == 'sales') {
                                $('.reportTable').html(salesTable);
                                //append footer with total profit to the table after the tbody
                                //capture the table body object to append the footer
                                var tableBody = $('.reportTable').find('tbody');
                                //append the footer to the table
                                tableBody.after(`
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-center fw-bolder fs-5">TOTAL </td>
                                                <td colspan="1" class="text-center fw-bolder" id="">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(response.totalSales)}</td>
                                                <td colspan="1" class="text-center fw-bolder" id="">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(response.grossProfit)}</td>
                                            </tr>
                                        </tfoot>
                                    `);
                            } else if (category == 'stocks') {
                                $('.reportTable').html(stocksTable);
                            } else if (category == 'expenses') {
                                $('.reportTable').html(expensesTable);
                                var tableBody = $('.reportTable').find('tbody');
                                //append the footer to the table
                                tableBody.after(`
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-center fw-bolder fs-5">TOTAL </td>
                                                <td colspan="1" class="text-left fw-bolder" id="">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(response.totalExpenses)}</td>
                                            </tr>
                                        </tfoot>
                                    `);
                            } else if (category == 'debts') {
                                $('.reportTable').html(debtTable);
                                var tableBody = $('.reportTable').find('tbody');
                                //append the footer to the table
                                tableBody.after(`
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-center fw-bolder fs-5">TOTAL </td>
                                                <td colspan="1" class="text-left fw-bolder" id="">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(response.totalDebts)}</td>
                                                <td colspan="1" class="text-left fw-bolder" id="">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(response.totalDeptsPaid)}</td>
                                                <td colspan="1" class="text-left fw-bolder" id="">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(response.totalDeptsRemaining)}</td>
                                            </tr>
                                        </tfoot>
                                    `);
                            } else if (category == 'installments') {
                                $('.reportTable').html(installmentTable);
                                var tableBody = $('.reportTable').find('tbody');
                                //append the footer to the table
                                tableBody.after(`
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td colspan="3" class="text-center fw-bolder fs-5">TOTAL </td>
                                                <td colspan="1" class="text-left fw-bolder" id="">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(response.totalInstallments)}</td>
                                            </tr>
                                        </tfoot>
                                    `);
                            } else {
                                $('.reportTable').html(
                                    '<i>No data available for the selected category.</i>');
                            }

                            // data = [2200, 2900, 4000, 6000, 3000];
                            // labels = ['June', 'July', 'Aug', 'Sept', 'Nov'];
                            drawSalesChart('topSalesChart', response.topLabels, response.topData,
                                'Top 10 Sold', 'rgba(54, 162, 235, 0.7)', 'rgba(54, 162, 235, 1)',
                                topSalesChart,
                                function(chart) {
                                    topSalesChart = chart;
                                });
                            drawSalesChart('bottomSalesChart', response.bottomLabels, response
                                .bottomData, 'Bottom 10 Sold', 'rgba(255, 99, 132, 0.7)',
                                'rgba(255, 99, 132, 1)', bottomSalesChart,
                                function(chart) {
                                    bottomSalesChart = chart;
                                });


                        } else {
                            $('#loader-overlay').hide(); // Hide loader
                            $('#totalStocks').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalStocks));

                            $('#totalSales').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalSales));

                            $('#totalProfit').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(response.totalProfit));

                            $('#totalReturns').text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0,
                                decimal: 0
                            }).format(response.totalReturns));

                            // alert(response.error);
                        }

                        let columnDefs = [];
                        if (category == 'stocks') {
                            columnDefs = [{
                                    targets: 2,
                                    visible: true,
                                    searchable: false
                                },
                                {
                                    targets: 3,
                                    visible: true
                                },
                                {
                                    targets: 4,
                                    visible: false
                                },
                                {
                                    targets: 5,
                                    visible: false
                                },
                                {
                                    targets: 6,
                                    visible: false
                                }
                            ];
                        }

                        // Initialize DataTable, but count the number of rows in the table first, if it is greater than 0, then destroy the table and reinitialize it, otherwise skip the initialization
                        if (response.rows > 0) {
                            getBase64Image(function(logoBase64) {
                                $('.reportsTable').DataTable({
                                    paging: true, // Enable paging
                                    searching: true, // Enable search bar
                                    ordering: true, // Enable column sorting
                                    info: true, // Enable information display
                                    // lengthMenu: [10, 25, 50,
                                    //     100
                                    // ], // Dropdown for records per page
                                    lengthMenu: [
                                        [10, 25, 50, 100, -1],
                                        [10, 25, 50, 100, "All"]
                                    ], // Dropdown for records per page
                                    pageLength: 10, // Default number of records per page
                                    dom: 'lBfrtip', // Add Buttons to the table
                                    buttons: [{
                                            extend: 'csvHtml5',
                                            title: '',
                                            text: 'Download CSV',
                                            className: 'btn btn-primary reportsDownloadButton',
                                            exportOptions: {
                                                // columns: ':visible', // Export only visible columns
                                                format: {
                                                    body: function(data, row,
                                                        column, node) {
                                                        return $('<div>').html(
                                                                data)
                                                            .text() // Convert HTML to plain text
                                                            .replace(/\s+/g,
                                                                ' '
                                                            ) // Replace multiple spaces with a single space
                                                            .replace(/\u00A0/g,
                                                                ''
                                                            ) // Remove non-breaking spaces (&nbsp;)
                                                            .replace(
                                                                /TSh|TZS|,/g,
                                                                ''
                                                            ) // Remove currency symbols and commas
                                                            .trim(); // Remove leading and trailing spaces
                                                    }
                                                }

                                            },
                                            customize: function(csv) {
                                                let reportType =
                                                    "Sales Report"; // Replace with dynamic report type if needed
                                                let dateRange =
                                                    "From: 2024-04-01 To: 2024-04-30";
                                                let customHeader =
                                                    `"Company Name","${reportType}","${dateRange}"\n`;
                                                return customHeader +
                                                    csv; // Prepend custom header to CSV file
                                            }
                                        },
                                        {
                                            extend: 'pdfHtml5',
                                            title: '',
                                            text: 'Download PDF',
                                            className: 'btn btn-secondary reportsDownloadButton',
                                            //if category is sales orientation should be Portrait
                                            orientation: category ===
                                                "sales" ? "portrait" : "landscape",
                                            //  orientation: 'landscape',
                                            //set page size to A4
                                            pageSize: 'A4', // A4 page size
                                            footer: false, // hii inasaidia kwenye baadhi ya versions (footer ikiwa true onadisplay)
                                            customize: function(doc) {
                                                let reportTitles = {
                                                    sales: "SALES REPORT",
                                                    stocks: "STOCKS REPORT",
                                                    expenses: "EXPENSES REPORT",
                                                    debts: "DEBTS REPORT",
                                                    installments: "INSTALLMENTS REPORT"
                                                };

                                                let reportType = reportTitles[
                                                        category] ??
                                                    "PHARMACY REPORT";
                                                let printDate = new Date()
                                                    .toLocaleString();

                                                // Define Styles
                                                doc.styles.title = {
                                                    color: '#2c3e50',
                                                    fontSize: 20,
                                                    bold: true,
                                                    alignment: 'left',
                                                    margin: [0, 0, 0, 5]
                                                };
                                                doc.styles.subtitle = {
                                                    color: '#34495e',
                                                    fontSize: 16,
                                                    bold: true,
                                                    alignment: 'left',
                                                    margin: [0, 0, 0, 2]
                                                };
                                                doc.styles.header = {
                                                    color: '#7f8c8d',
                                                    fontSize: 10,
                                                    italics: true,
                                                    alignment: 'left',
                                                    margin: [0, 0, 0, 20]
                                                };
                                                doc.styles.tableHeader = {
                                                    bold: true,
                                                    fontSize: 11,
                                                    color: 'white',
                                                    fillColor: '#2980b9',
                                                    alignment: 'center',
                                                    margin: [5, 5, 5, 5]
                                                };
                                                doc.styles.tableBody = {
                                                    fontSize: 9,
                                                    alignment: 'center'
                                                };
                                                doc.styles.tableFooter = {
                                                    bold: true,
                                                    fontSize: 10,
                                                    fillColor: '#ecf0f1',
                                                    alignment: 'center'
                                                };

                                                // Header Layout
                                                let header = {
                                                    columns: [
                                                        logoBase64 ? {
                                                            image: logoBase64,
                                                            width: 70,
                                                            alignment: 'left'
                                                        } : {
                                                            text: '',
                                                            width: 70
                                                        },
                                                        {
                                                            stack: [{
                                                                    text: "<?php echo e(strtoupper($pharmacy->name)); ?>",
                                                                    style: 'title'
                                                                },
                                                                {
                                                                    text: reportType,
                                                                    style: 'subtitle'
                                                                },
                                                                {
                                                                    text: 'Period: ' +
                                                                        start +
                                                                        ' to ' +
                                                                        end,
                                                                    fontSize: 11,
                                                                    color: '#34495e'
                                                                },
                                                                {
                                                                    text: 'Generated on: ' +
                                                                        printDate,
                                                                    fontSize: 9,
                                                                    color: '#95a5a6'
                                                                }
                                                            ],
                                                            margin: [10,
                                                                0,
                                                                0, 0
                                                            ]
                                                        }
                                                    ],
                                                    margin: [0, 0, 0, 20]
                                                };

                                                // Summary Section (for sales especially)
                                                let summary = {
                                                    table: {
                                                        widths: ['*', '*',
                                                            '*', '*'
                                                        ],
                                                        body: [
                                                            [{
                                                                text: 'Total Sales',
                                                                style: 'tableHeader'
                                                            }, {
                                                                text: 'Total Expenses',
                                                                style: 'tableHeader'
                                                            }, {
                                                                text: 'Total Installments',
                                                                style: 'tableHeader'
                                                            }, {
                                                                text: 'Net Profit',
                                                                style: 'tableHeader'
                                                            }],
                                                            [{
                                                                text: $(
                                                                        '#totalSales'
                                                                    )
                                                                    .text(),
                                                                style: 'tableBody'
                                                            }, {
                                                                text: $(
                                                                        '#totalExpenses'
                                                                    )
                                                                    .text(),
                                                                style: 'tableBody'
                                                            }, {
                                                                text: $(
                                                                        '#totalInstallments'
                                                                    )
                                                                    .text(),
                                                                style: 'tableBody'
                                                            }, {
                                                                text: $(
                                                                        '#totalProfit'
                                                                    )
                                                                    .text(),
                                                                style: 'tableBody'
                                                            }]
                                                        ]
                                                    },
                                                    layout: 'lightHorizontalLines',
                                                    margin: [0, 0, 0, 20]
                                                };

                                                // Clean up any default blank title/space at the start
                                                // The original DataTables PDF button generates a default title.
                                                // We'll find the actual table content and then rebuild doc.content.
                                                let mainTable = doc.content
                                                    .find(c => !!c.table);

                                                // Clear default content and rebuild it with our custom layout
                                                doc.content = [header];

                                                // Add Summary Section for Sales
                                                // if (category === 'sales') {
                                                //     doc.content.push(summary);
                                                // }

                                                // Put the main data table back
                                                if (mainTable) {
                                                    doc.content.push(mainTable);
                                                }

                                                // Table formatting (formatting the mainTable we just added)
                                                let table = mainTable;
                                                if (table) {
                                                    table.layout = {
                                                        hLineWidth: (i,
                                                                node) =>
                                                            0.5,
                                                        vLineWidth: (i,
                                                                node) =>
                                                            0.5,
                                                        hLineColor: (i,
                                                                node) =>
                                                            '#bdc3c7',
                                                        vLineColor: (i,
                                                                node) =>
                                                            '#bdc3c7',
                                                        paddingLeft: (i,
                                                                node) =>
                                                            category ===
                                                            'stocks' ? 2 :
                                                            4,
                                                        paddingRight: (i,
                                                                node) =>
                                                            category ===
                                                            'stocks' ? 2 :
                                                            4,
                                                        paddingTop: (i,
                                                            node) => 4,
                                                        paddingBottom: (i,
                                                            node) => 4
                                                    };

                                                    // Auto widths
                                                    table.table.widths = Array(
                                                        table.table.body[0]
                                                        .length).fill('*');

                                                    // Category-specific formatting
                                                    if (category === 'sales') {
                                                        table.table.widths = [
                                                            'auto', '15%',
                                                            '*', 'auto',
                                                            '15%', '15%'
                                                        ];
                                                        table.table.body
                                                            .forEach((row,
                                                                rowIndex
                                                            ) => {
                                                                if (rowIndex >
                                                                    0) {
                                                                    // Medicine (Col 2)
                                                                    if (row[
                                                                            2
                                                                        ]) {
                                                                        if (typeof row[
                                                                                2
                                                                            ] ===
                                                                            'string'
                                                                        )
                                                                            row[
                                                                                2
                                                                            ] = {
                                                                                text: row[
                                                                                    2
                                                                                ]
                                                                            };
                                                                        row[2]
                                                                            .alignment =
                                                                            'left';
                                                                    }
                                                                    // Qty, Amount, Profit (Cols 3, 4, 5)
                                                                    [3, 4,
                                                                        5
                                                                    ]
                                                                    .forEach
                                                                        (i => {
                                                                            if (row[
                                                                                    i
                                                                                ]) {
                                                                                if (typeof row[
                                                                                        i
                                                                                    ] ===
                                                                                    'string'
                                                                                )
                                                                                    row[
                                                                                        i
                                                                                    ] = {
                                                                                        text: row[
                                                                                            i
                                                                                        ]
                                                                                    };
                                                                                row[i]
                                                                                    .alignment =
                                                                                    'right';
                                                                            }
                                                                        });
                                                                }
                                                            });
                                                    } else if (category ===
                                                        'expenses') {
                                                        table.table.widths = [
                                                            'auto', 'auto',
                                                            '*', '*',
                                                            'auto', 'auto',
                                                            '15%'
                                                        ];
                                                        table.table.body
                                                            .forEach((row,
                                                                rowIndex
                                                            ) => {
                                                                if (rowIndex >
                                                                    0 &&
                                                                    row[6]
                                                                ) {
                                                                    if (typeof row[
                                                                            6
                                                                        ] ===
                                                                        'string'
                                                                    )
                                                                        row[
                                                                            6
                                                                        ] = {
                                                                            text: row[
                                                                                6
                                                                            ]
                                                                        };
                                                                    row[6]
                                                                        .alignment =
                                                                        'right';
                                                                }
                                                            });
                                                    } else if (category ===
                                                        'debts') {
                                                        table.table.widths = [
                                                            'auto', '*',
                                                            'auto', 'auto',
                                                            'auto', '12%',
                                                            '12%', '12%'
                                                        ];
                                                        table.table.body
                                                            .forEach((row,
                                                                rowIndex
                                                            ) => {
                                                                if (rowIndex >
                                                                    0) {
                                                                    [5, 6,
                                                                        7
                                                                    ]
                                                                    .forEach
                                                                        (i => {
                                                                            if (row[
                                                                                    i
                                                                                ]) {
                                                                                if (typeof row[
                                                                                        i
                                                                                    ] ===
                                                                                    'string'
                                                                                )
                                                                                    row[
                                                                                        i
                                                                                    ] = {
                                                                                        text: row[
                                                                                            i
                                                                                        ]
                                                                                    };
                                                                                row[i]
                                                                                    .alignment =
                                                                                    'right';
                                                                            }
                                                                        });
                                                                }
                                                            });
                                                    } else if (category ===
                                                        'installments') {
                                                        table.table.widths = [
                                                            'auto', '*',
                                                            '*', 'auto',
                                                            '15%'
                                                        ];
                                                        table.table.body
                                                            .forEach((row,
                                                                rowIndex
                                                            ) => {
                                                                if (rowIndex >
                                                                    0 &&
                                                                    row[4]
                                                                ) {
                                                                    if (typeof row[
                                                                            4
                                                                        ] ===
                                                                        'string'
                                                                    )
                                                                        row[
                                                                            4
                                                                        ] = {
                                                                            text: row[
                                                                                4
                                                                            ]
                                                                        };
                                                                    row[4]
                                                                        .alignment =
                                                                        'right';
                                                                }
                                                            });
                                                    } else if (category ===
                                                        'stocks') {
                                                        // 13 columns - Reduce font size and optimize widths
                                                        table.table.widths = [
                                                            'auto', '*',
                                                            'auto', 'auto',
                                                            'auto', 'auto',
                                                            'auto', 'auto',
                                                            'auto', 'auto',
                                                            'auto', 'auto',
                                                            'auto'
                                                        ];
                                                        table.table.body
                                                            .forEach((row,
                                                                rowIndex
                                                            ) => {
                                                                row.forEach(
                                                                    (cell,
                                                                        cellIndex
                                                                    ) => {
                                                                        // Convert to object if string to apply font size
                                                                        let cellContent =
                                                                            (typeof cell ===
                                                                                'string'
                                                                            ) ?
                                                                            {
                                                                                text: cell
                                                                            } :
                                                                            cell;
                                                                        if (
                                                                            cellContent
                                                                        ) {
                                                                            cellContent
                                                                                .fontSize =
                                                                                7; // Smaller font for 13 columns

                                                                            // Alignments
                                                                            if (rowIndex >
                                                                                0
                                                                            ) {
                                                                                if (cellIndex ===
                                                                                    1
                                                                                ) { // Medicine
                                                                                    cellContent
                                                                                        .alignment =
                                                                                        'left';
                                                                                } else if (
                                                                                    [4, 5,
                                                                                        10,
                                                                                        11,
                                                                                        12
                                                                                    ]
                                                                                    .includes(
                                                                                        cellIndex
                                                                                    )
                                                                                ) { // Currency
                                                                                    cellContent
                                                                                        .alignment =
                                                                                        'right';
                                                                                } else if (
                                                                                    [2, 3,
                                                                                        6
                                                                                    ]
                                                                                    .includes(
                                                                                        cellIndex
                                                                                    )
                                                                                ) { // Numbers
                                                                                    cellContent
                                                                                        .alignment =
                                                                                        'right';
                                                                                }
                                                                            }
                                                                            row[cellIndex] =
                                                                                cellContent;
                                                                        }
                                                                    });
                                                            });
                                                    }

                                                    // Add Footer Row
                                                    let footerData = [];
                                                    if (category === 'sales') {
                                                        footerData = [{
                                                                text: 'TOTAL',
                                                                colSpan: 4,
                                                                style: 'tableFooter'
                                                            }, {}, {}, {},
                                                            {
                                                                text: $(
                                                                        '#totalSales'
                                                                    )
                                                                    .text(),
                                                                style: 'tableFooter',
                                                                alignment: 'right'
                                                            },
                                                            {
                                                                text: new Intl
                                                                    .NumberFormat(
                                                                        'en-TZ', {
                                                                            style: 'currency',
                                                                            currency: 'TZS',
                                                                            minimumFractionDigits: 0,
                                                                            maximumFractionDigits: 0
                                                                        })
                                                                    .format(
                                                                        currentGrossProfit
                                                                        ),
                                                                style: 'tableFooter',
                                                                alignment: 'right'
                                                            }
                                                        ];
                                                    } else if (category ===
                                                        'expenses') {
                                                        footerData = [{
                                                                text: 'TOTAL',
                                                                colSpan: 6,
                                                                style: 'tableFooter'
                                                            }, {}, {}, {},
                                                            {}, {},
                                                            {
                                                                text: $(
                                                                        '#totalExpenses'
                                                                    )
                                                                    .text(),
                                                                style: 'tableFooter',
                                                                alignment: 'right'
                                                            }
                                                        ];
                                                    } else if (category ===
                                                        'debts') {
                                                        footerData = [{
                                                                text: 'TOTAL',
                                                                colSpan: 5,
                                                                style: 'tableFooter'
                                                            }, {}, {}, {},
                                                            {},
                                                            {
                                                                text: $(
                                                                        '#totalDebt'
                                                                    )
                                                                    .text(),
                                                                style: 'tableFooter',
                                                                alignment: 'right'
                                                            },
                                                            {
                                                                text: $(
                                                                        '#totalDeptsPaid'
                                                                    )
                                                                    .text(),
                                                                style: 'tableFooter',
                                                                alignment: 'right'
                                                            },
                                                            {
                                                                text: $(
                                                                        '#totalDeptsRemaining'
                                                                    )
                                                                    .text(),
                                                                style: 'tableFooter',
                                                                alignment: 'right'
                                                            }
                                                        ];
                                                    } else if (category ===
                                                        'installments') {
                                                        footerData = [{
                                                                text: 'TOTAL',
                                                                colSpan: 4,
                                                                style: 'tableFooter'
                                                            }, {}, {}, {},
                                                            {
                                                                text: $(
                                                                        '#totalInstallments'
                                                                    )
                                                                    .text(),
                                                                style: 'tableFooter',
                                                                alignment: 'right'
                                                            }
                                                        ];
                                                    }

                                                    if (footerData.length > 0) {
                                                        table.table.body.push(
                                                            footerData);
                                                    }
                                                }

                                                // Page layout
                                                doc.pageMargins = [40, 40, 40,
                                                    40
                                                ];
                                                doc.footer = function(
                                                    currentPage, pageCount
                                                ) {
                                                    return {
                                                        columns: [{
                                                                text: 'pillpointone.com',
                                                                alignment: 'left',
                                                                fontSize: 8,
                                                                color: '#95a5a6',
                                                                margin: [
                                                                    40,
                                                                    0
                                                                ]
                                                            },
                                                            {
                                                                text: 'Page ' +
                                                                    currentPage +
                                                                    ' of ' +
                                                                    pageCount,
                                                                alignment: 'right',
                                                                fontSize: 8,
                                                                color: '#95a5a6',
                                                                margin: [
                                                                    0,
                                                                    0,
                                                                    40,
                                                                    0
                                                                ]
                                                            }
                                                        ]
                                                    };
                                                };
                                            }

                                        }
                                    ],
                                    columnDefs: columnDefs,
                                    error: function(settings, helpPage, message) {
                                        console.error('DataTables Error:',
                                            message
                                        ); // Log the error to the console
                                        // Optionally handle specific errors here
                                    }
                                });
                            });
                        }
                    },
                    complete: function() {
                        $('#loader-overlay').hide(); // Hide loader
                        $('#reportSendDiv').removeClass('hidden');
                    },
                    error: function() {
                        $('#loader-overlay').hide(); // Hide loader
                        alert('Failed to filter Reports.');
                    }
                });
            }

            // Initialize Chart.js
            function drawSalesChart(canvasId, labels, data, label, bgColor, borderColor, chartInstance,
                setChartInstance) {
                var ctx = document.getElementById(canvasId).getContext('2d');
                if (chartInstance) {
                    chartInstance.destroy();
                }
                var newChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: bgColor,
                            borderColor: borderColor,
                            borderWidth: 1,
                            borderRadius: 5,
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Better for medicine names
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Sold: ' + context.raw;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
                setChartInstance(newChart);
            }

            // Fetch the base64 image before initializing DataTables
            function getBase64Image(callback) {
                if (cachedLogo) {
                    callback(cachedLogo);
                    return;
                }
                $.ajax({
                    url: "/get_logo", // Create this route in Laravel
                    type: "GET",
                    success: function(response) {
                        cachedLogo = response.base64;
                        callback(cachedLogo);
                    },
                    error: function(error) {
                        console.log("Response Error:", error.responseText);
                        callback(null);
                    }
                });
            }

            //format date to dd-mm-yyyy
            function formatReadableDate(dateString) {
                const date = new Date(dateString);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0'); // months are 0-indexed
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            }

            $('#dateFilter').trigger('change');

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('reports.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /media/michaelkilunga/C/SKYLINK/pms/resources/views/reports/reports.blade.php ENDPATH**/ ?>