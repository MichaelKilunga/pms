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
                        <option value="stocks">Stock</option>
                        {{-- <option value="returns">Returns</option> --}}
                        {{-- <option value="expired">Expired</option>
                        <option value="profit">Profit</option> --}}
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    {{-- <input type="text" class="form-control" placeholder="Search by ID or Name"> --}}
                    <label for="medicine">Medicine</label>
                    <select class="form-select onReport" name="medicine" id="medicine" required>
                        <option selected value="0">All Medicines</option>
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
                        <h6 class="card-title">Total Stocks</h6>
                        <h3 id="totalStocks" class="fw-bold text-danger">fetching...</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Total Sales</h6>
                        <h3 id="totalSales" class="fw-bold text-primary">fetching...</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Total Returns</h6>
                        <h3 id="totalReturns" class="fw-bold text-warning">fetching...</h3>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Total Profit</h6>
                        <h3 id="totalProfit" class="fw-bold text-success">fetching...</h3>
                    </div>
                </div>
            </div>
            <div id="reportSendDiv" class="hidden col-6 col-md-4 col-lg-2">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Send Report</h6>
                        {{-- pass start date and end date --}}
                        <form action="{{ route('reports.send') }}" method="POST">
                            @csrf
                            <input type="hidden" name="start_date" id="report_start_date">
                            <input type="hidden" name="end_date" id="report_end_date">
                            <button type="submit" class="btn btn-primary">Send Report</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table Section -->
        <div class="row mb-2 g-4 justify-content-center text-center">
            <div class="col-12">
                <div class="table-responsive reportTable">
                    <i>Table will appear here!!!</i>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row mb-4 g-4 justify-content-center text-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title" id="chartHead">Fetching...</h6>
                        <canvas id="transactionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div><p>{{$medicine->pharmacies->name}}</p></div> --}}
    </div>
    <script>
        $(document).ready(function() {
            var transactionChart = null;
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

            $(document).ready(function() {
                $('#dateFilter').trigger('change');
            });

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
                console.log(value);
                $('#startDate, #endDate').addClass('d-none');

                switch (value) {
                    case 'today':
                        console.log('Filtering for Today');
                        $('#report_start_date').val(formatDate(today));
                        $('#report_end_date').val(formatDate(today));
                        $('#reportSendDiv').removeClass('hidden');
                        filterData(formatDate(today), formatDate(today), category, medicine);
                        break;
                    case 'this_week':
                        console.log('Filtering for This Week');
                        $('#report_start_date').val(formatDate(startOfWeek));
                        $('#report_end_date').val(formatDate(new Date()));
                        $('#reportSendDiv').removeClass('hidden');
                        filterData(formatDate(startOfWeek), formatDate(new Date()), category, medicine);
                        break;
                    case 'this_month':
                        console.log('Filtering for This Month');
                        $('#report_start_date').val(formatDate(startOfMonth));
                        $('#report_end_date').val(formatDate(new Date()));
                        $('#reportSendDiv').removeClass('hidden');
                        filterData(formatDate(startOfMonth), formatDate(new Date()), category, medicine);
                        break;
                    case 'last_month':
                        console.log('Filtering for Last Month');
                        $('#report_start_date').val(formatDate(startOfLastMonth));
                        $('#report_end_date').val(formatDate(endOfLastMonth));
                        $('#reportSendDiv').removeClass('hidden');
                        filterData(formatDate(startOfLastMonth), formatDate(endOfLastMonth), category,
                            medicine);
                        break;
                    case 'this_year':
                        console.log('Filtering for This Year');
                        $('#report_start_date').val(formatDate(startOfYear));
                        $('#report_end_date').val(formatDate(new Date()));
                        $('#reportSendDiv').removeClass('hidden');
                        filterData(formatDate(startOfYear), formatDate(new Date()), category, medicine);
                        break;
                    case 'custom_range':
                        console.log('Custom Range Selected');
                        $('#report_start_date').val('');
                        $('#report_end_date').val('');
                        $('#reportSendDiv').addClass('hidden');
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
                    $('#report_start_date').val(startDate);
                    $('#report_end_date').val(endDate);
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
                        medicine: medicine
                    },
                    dataType: 'json',
                    success: function(response) {
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
                                                <td colspan="1" class="text-center fw-bolder" id="">${new Intl.NumberFormat('en-TZ', { style: 'currency', currency: 'TZS', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(response.totalProfit)}</td>
                                            </tr>
                                        </tfoot>
                                    `);
                            } else if (category == 'stocks') {
                                $('.reportTable').html(stocksTable);
                            }



                            // data = [2200, 2900, 4000, 6000, 3000];
                            // labels = ['June', 'July', 'Aug', 'Sept', 'Nov'];
                            $('#chartHead').html(
                                `<p class="h3 sm-h4"><strong  class="text-danger">Sales</strong> trends of <strong class="text-danger">${selectedMedicineName}</strong> </p> <p>From: <span class="h5 sm-h6 text-primary">${start}</span> | To: <span class="h5 sm-h6 text-primary">${end}<span></p>`
                            );
                            labels = response.labels;
                            data = response.data;
                            drawGraph(labels, data);
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
                            $('.reportsTable').DataTable().destroy(); // Destroy the old table
                            getBase64Image(function(logoBase64) {
                                // alert(logoBase64);
                                $('.reportsTable').DataTable({
                                    paging: true, // Enable paging
                                    searching: true, // Enable search bar
                                    ordering: true, // Enable column sorting
                                    info: true, // Enable information display
                                    lengthMenu: [10, 25, 50,
                                        100
                                    ], // Dropdown for records per page
                                    pageLength: 10, // Default number of records per page
                                    dom: 'Bfrtip', // Add Buttons to the table
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
                                                let reportType = category ===
                                                    "sales" ? "Sales Report" :
                                                    "Stocks Report";
                                                let printDate =
                                                    "From: pillpointone.com \n Date: " +
                                                    new Date().toLocaleString();

                                                // Header Layout: Logo on Left, Title on Right
                                                let headerLayout = {
                                                    columns: [
                                                        // Left Side: Logo (if available)
                                                        {
                                                            image: logoBase64 ||
                                                                '', // Use logo if available
                                                            width: 80, // Adjust size as needed
                                                            alignment: 'left',
                                                            margin: [0,
                                                                0,
                                                                10,
                                                                0
                                                            ] // Spacing
                                                        },
                                                        // Right Side: Report Titles
                                                        {
                                                            stack: [{
                                                                    text: "PHARMACY MANAGEMENT SYSTEM",
                                                                    fontSize: 18,
                                                                    color: '#0000FF', // Blue
                                                                    bold: true,
                                                                    alignment: 'left'
                                                                },
                                                                   {
                                                                    text: "{{ strtoupper($pharmacy->name) }}",
                                                                    fontSize: 17,
                                                                    italics: true,
                                                                    color: '#0000FF', // Blue
                                                                    bold: true,
                                                                    alignment: 'left'
                                                                },
                                                                {
                                                                    text: reportType,
                                                                    fontSize: 15,
                                                                    color: '#008000', // Green
                                                                    bold: true,
                                                                    alignment: 'left',
                                                                    margin: [
                                                                        0,
                                                                        5,
                                                                        0,
                                                                        0
                                                                    ]
                                                                },
                                                                {
                                                                    text: 'FROM: ' +
                                                                        start +
                                                                        ' TO: ' +
                                                                        end,
                                                                    fontSize: 12,
                                                                    italics: true,
                                                                    alignment: 'left',
                                                                    margin: [
                                                                        0,
                                                                        0,
                                                                        0,
                                                                        20
                                                                    ]
                                                                }
                                                            ],
                                                            width: '*'
                                                        }
                                                    ],
                                                    margin: [0, 0, 0,
                                                        10
                                                    ] // Space after the header
                                                };

                                                //  Print Date (Below header, right-aligned)
                                                let printDateText = {
                                                    text: printDate,
                                                    fontSize: 10,
                                                    italics: true,
                                                    alignment: 'right',
                                                    margin: [0, 0, 0, 10]
                                                };

                                                //  **Apply Table Styles**
                                                doc.styles.tableHeader = {
                                                    bold: true,
                                                    fontSize: 12,
                                                    color: 'white',
                                                    fillColor: '#333', // Dark gray header
                                                    alignment: 'center'
                                                };
                                                doc.styles.tableBody = {
                                                    fontSize: 10,
                                                    color: '#000', // Black text
                                                    alignment: 'center'
                                                };

                                                doc.content.unshift(
                                                    printDateText
                                                ); // Add print date

                                                doc.content.unshift(
                                                    headerLayout
                                                );

                                                // Format Table Borders
                                                doc.content.forEach(function(
                                                    content) {
                                                    if (content.table) {
                                                        content
                                                            .layout = {
                                                                hLineWidth: function(
                                                                    i,
                                                                    node
                                                                ) {
                                                                    return 0.5;
                                                                }, // Horizontal lines
                                                                vLineWidth: function(
                                                                    i,
                                                                    node
                                                                ) {
                                                                    return 0.5;
                                                                }, // Vertical lines
                                                                hLineColor: function(
                                                                    i,
                                                                    node
                                                                ) {
                                                                    return '#aaa';
                                                                }, // Light gray lines
                                                                vLineColor: function(
                                                                    i,
                                                                    node
                                                                ) {
                                                                    return '#aaa';
                                                                } // Light gray lines
                                                            };
                                                        // Set all columns to auto-adjust width
                                                        content.table
                                                            .widths =
                                                            Array(
                                                                content
                                                                .table
                                                                .body[0]
                                                                .length)
                                                            .fill('*');
                                                    }
                                                });

                                                //PROPER APPEND FOR TOTAL SALES
                                                // Append TOTAL row for sales report

                                                doc.content.forEach(function(
                                                    content) {
                                                    if (content.table) {
                                                        if (category ===
                                                            'sales') {
                                                            // SALES FOOTER
                                                            content
                                                                .table
                                                                .body
                                                                .push([{
                                                                        text: 'TOTAL',
                                                                        bold: true,
                                                                        alignment: 'center',
                                                                        colSpan: 4
                                                                    },
                                                                    {},
                                                                    {},
                                                                    {},
                                                                    {
                                                                        text: $(
                                                                                '#totalSales'
                                                                            )
                                                                            .text(),
                                                                        bold: true,
                                                                        alignment: 'center'
                                                                    },
                                                                    {
                                                                        text: $(
                                                                                '#totalProfit'
                                                                            )
                                                                            .text(),
                                                                        bold: true,
                                                                        alignment: 'center'
                                                                    }
                                                                ]);
                                                        }
                                                    }
                                                });

                                                // page margins
                                                doc.pageMargins = [20, 60, 20,
                                                    40
                                                ];

                                                // Add page numbers (footer)
                                                doc.footer = function(currentPage, pageCount) {
                                                    return {
                                                        columns: [
                                                            {
                                                                text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                                                                alignment: 'right',
                                                                fontSize: 9,
                                                                margin: [0, 0, 20, 0]
                                                            }
                                                        ]
                                                    };
                                                };

                                                doc.content.forEach(function(
                                                    content) {
                                                    if (content.table) {
                                                        // define adaptive widths for stock table
                                                        if (category === 'stocks') {
                                                            content
                                                                .table
                                                                .widths = [
                                                                    'auto', // #
                                                                    'auto', // Batch Number
                                                                    'auto', // Buying Price
                                                                    'auto', // Selling Price
                                                                    'auto', // Expiry Date
                                                                    'auto', // Low Stock
                                                                    'auto', // Supplier
                                                                    '*', // Medicine (flexible because names are long)
                                                                    'auto', // Stocked Qty
                                                                    'auto', // Remained Qty
                                                                    'auto', // Total Sales
                                                                    'auto', // Total Profit
                                                                    'auto' // Expired Loss
                                                                ];
                                                        }

                                                        //define adaptive width with sales
                                                    //  if (category === 'sales') 
                                                    //     {
                                                    //         content
                                                    //             .table
                                                    //             .widths = [
                                                    //                 'auto', // #
                                                    //                 'auto', // Date
                                                    //                 '*', // Medicine
                                                    //                 'auto', // Quantity
                                                    //                 'auto', // Total Sales
                                                    //                 'auto' // Total profit
                                                    //             ];
                                                    //     }


                                                    if (category === 'sales') {
                                                content.table.widths = [
                                                    'auto',   // #
                                                    'auto',   // Date
                                                    // 'auto',   // Medicine
                                                    '*',      // Medicine
                                                    'auto',   // Quantity
                                                    'auto',   // Total Sales
                                                    'auto'    // Total profit
                                                ];

                                                // Force left + top alignment & wrapping for Medicine column
                                                content.table.body.forEach((row, rowIndex) => {
                                                    if (rowIndex > 0) { // skip header row
                                                        row[2] = { 
                                                            text: row[2], 
                                                            alignment: 'left', 
                                                            noWrap: false, 
                                                            margin: [0, 0, 0, 0],   // optional: remove padding
                                                            style: 'medicineCell'
                                                        };
                                                    }
                                                });

                                                // Add a style in your pdfmake doc definition
                                                content.styles = {
                                                    medicineCell: {
                                                        alignment: 'left',
                                                        valign: 'top'   // ensure text starts at top of cell
                                                    }
                                                };
                                            }


                                                        // format alignment
                                                        content.table
                                                            .body
                                                            .forEach(
                                                                function(
                                                                    row,
                                                                    rowIndex
                                                                ) {
                                                                    if (rowIndex ===
                                                                        0
                                                                    )
                                                                        return; // skip header
                                                                    // align money columns to the right
                                                                    [2, 3,
                                                                        10,
                                                                        11,
                                                                        12
                                                                    ]
                                                                    .forEach
                                                                        (function(
                                                                            colIndex
                                                                        ) {
                                                                            if (row[
                                                                                    colIndex
                                                                                ] &&
                                                                                row[
                                                                                    colIndex
                                                                                ]
                                                                                .text !==
                                                                                undefined
                                                                            ) {
                                                                                row[colIndex]
                                                                                    .alignment =
                                                                                    'right';
                                                                            } else if (
                                                                                typeof row[
                                                                                    colIndex
                                                                                ] ===
                                                                                'string'
                                                                            ) {
                                                                                row[colIndex] = {
                                                                                    text: row[
                                                                                        colIndex
                                                                                    ],
                                                                                    alignment: 'right'
                                                                                };
                                                                            }
                                                                        });
                                                                    // center align qty columns
                                                                    [0, 8,
                                                                        9
                                                                    ]
                                                                    .forEach
                                                                        (function(
                                                                            colIndex
                                                                        ) {
                                                                            if (row[
                                                                                    colIndex
                                                                                ] &&
                                                                                row[
                                                                                    colIndex
                                                                                ]
                                                                                .text !==
                                                                                undefined
                                                                            ) {
                                                                                row[colIndex]
                                                                                    .alignment =
                                                                                    'center';
                                                                            } else if (
                                                                                typeof row[
                                                                                    colIndex
                                                                                ] ===
                                                                                'string'
                                                                            ) {
                                                                                row[colIndex] = {
                                                                                    text: row[
                                                                                        colIndex
                                                                                    ],
                                                                                    alignment: 'center'
                                                                                };
                                                                            }
                                                                        });
                                                                });
                                                    }

                                                });

                                            }
                                            // hapa##
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
                    },
                    error: function() {
                        $('#loader-overlay').hide(); // Hide loader
                        alert('Failed to filter Reports.');
                    }
                });
            }

            // Initialize Chart.js
            function drawGraph(labels, data) {
                var ctx = $('#transactionChart')[0].getContext('2d');
                if (transactionChart) {
                    transactionChart.destroy(); // Destroy the old chart
                    transactionChart = null;
                }
                transactionChart = new Chart(ctx, {
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

            // Fetch the base64 image before initializing DataTables
            function getBase64Image(callback) {
                $.ajax({
                    url: "/get_logo", // Create this route in Laravel
                    type: "GET",
                    success: function(response) {
                        // console.log("Logo fetched successfully:", response);
                        callback(response.base64); // Pass Base64 image to callback
                    },
                    error: function(error) {
                        // console.error("Error fetching logo. Using default.");
                        console.log("Response Error:", error.responseText);
                        callback(null); // If error, use null
                    }
                });
            }

        });
    </script>
@endsection
