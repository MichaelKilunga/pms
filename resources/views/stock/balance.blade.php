@extends('stock.app')

@section('content')
    <div class="container">
        <h4 class="text-primary fs-2 fw-bold mt-2 mb-1">Stock Balance Summary</h4>
        <hr>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="TableOne">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Stocked Remaining Quantity</th>
                        <th>Expired Quantity</th>
                        <th>Status</th>
                        <th>Stock Check</th> <!-- ✅ NEW COLUMN -->
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="stockModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-box-seam me-2"></i>
                            Stock Details for <span id="modalItemName" class="ms-1"></span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="stockTabs">
                            <li class="nav-item">
                                <button class="nav-link active text-success fw-bold" data-bs-toggle="tab"
                                    data-bs-target="#fineStock">
                                    <i class="bi bi-check-circle me-1"></i> Fine
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link text-danger fw-bold" data-bs-toggle="tab"
                                    data-bs-target="#expiredStock">
                                    <i class="bi bi-x-circle me-1"></i> Expired
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link text-warning fw-bold" data-bs-toggle="tab"
                                    data-bs-target="#lowStock">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Low Stock
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link text-dark fw-bold" data-bs-toggle="tab"
                                    data-bs-target="#finishedStock">
                                    <i class="bi bi-dash-circle me-1"></i> Finished
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
                            <!-- Fine -->
                            <div class="tab-pane fade show active" id="fineStock">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Batch No</th>
                                                <th>Remain Quantity</th>
                                                <th>Supplier</th>
                                                <th>Stocked On</th>
                                                <th>Expiry Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="fineStockTableBody"></tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Expired -->
                            <div class="tab-pane fade" id="expiredStock">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Batch No</th>
                                                <th>Remain Quantity</th>
                                                <th>Supplier</th>
                                                <th>Stocked On</th>
                                                <th>Expiry Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="expiredStockTableBody"></tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Low Stock -->
                            <div class="tab-pane fade" id="lowStock">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Batch No</th>
                                                <th>Remain Quantity</th>
                                                <th>Supplier</th>
                                                <th>Stocked On</th>
                                                <th>Expiry Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="lowStockTableBody"></tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Finished -->
                            <div class="tab-pane fade" id="finishedStock">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Batch No</th>
                                                <th>Remain Quantity</th>
                                                <th>Supplier</th>
                                                <th>Stocked On</th>
                                                <th>Expiry Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="finishedStockTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            function escapeHtml(str) {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            var MIN_LOADING_MS = 500;

            $('#stockModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var itemId = button.data('item-id');

                var spinnerRow = '<tr><td colspan="5" class="text-center py-4">' +
                    '<div class="spinner-border" role="status"></div>' +
                    '<div class="mt-2 small">Loading...</div></td></tr>';

                $('#modalItemName').text('Loading...');
                $('#fineStockTableBody').html(spinnerRow);
                $('#expiredStockTableBody').html(spinnerRow);
                $('#lowStockTableBody').html(spinnerRow);
                $('#finishedStockTableBody').html(spinnerRow);

                var start = Date.now();

                $.ajax({
                    url: `/stock/details/${itemId}`,
                    method: 'GET',
                    data: {
                        item_id: itemId
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        var elapsed = Date.now() - start;
                        var remaining = MIN_LOADING_MS - elapsed;

                        function renderData() {
                            if (!response.success) {
                                $('#modalItemName').text('Error');
                                $('#fineStockTableBody').html(
                                    '<tr><td colspan="5">Could not load data</td></tr>');
                                $('#expiredStockTableBody').html(
                                    '<tr><td colspan="5">Could not load data</td></tr>');
                                $('#lowStockTableBody').html(
                                    '<tr><td colspan="5">Could not load data</td></tr>');
                                $('#finishedStockTableBody').html(
                                    '<tr><td colspan="5">Could not load data</td></tr>');
                                return;
                            }

                            $('#modalItemName').text(response.item.name || 'Unknown');

                            // Fine
                            var fineBody = $('#fineStockTableBody').empty();
                            if (response.fine.length) {
                                $.each(response.fine, function(i, row) {
                                    fineBody.append('<tr>' +
                                        '<td>' + escapeHtml(row.batch_no) +
                                        '</td>' +
                                        '<td>' + (row.qty) + '</td>' +
                                        '<td>' + escapeHtml(row.supplier) +
                                        '</td>' +
                                        '<td>' + escapeHtml(row.stocked_on) +
                                        '</td>' +
                                        '<td>' + escapeHtml(row.expiry_date) +
                                        '</td>' +
                                        '</tr>');
                                });
                            } else {
                                fineBody.html(
                                    '<tr><td colspan="5" class="text-center text-muted">No fine stock</td></tr>'
                                );
                            }

                            // Expired
                            var expiredBody = $('#expiredStockTableBody').empty();
                            if (response.expired.length) {
                                $.each(response.expired, function(i, row) {
                                    expiredBody.append('<tr>' +
                                        '<td>' + escapeHtml(row.batch_no) +
                                        '</td>' +
                                        '<td>' + escapeHtml(row.qty) + '</td>' +
                                        '<td>' + escapeHtml(row.supplier) +
                                        '</td>' +
                                        '<td>' + escapeHtml(row.stocked_on) +
                                        '</td>' +
                                        '<td>' + escapeHtml(row.expiry_date) +
                                        '</td>' +
                                        '</tr>');
                                });
                            } else {
                                expiredBody.html(
                                    '<tr><td colspan="5" class="text-center text-muted">No expired stock</td></tr>'
                                );
                            }

                            // Low Stock
                            var lowBody = $('#lowStockTableBody').empty();
                            if (response.lowStock.length) {
                                $.each(response.lowStock, function(i, row) {
                                    lowBody.append('<tr>' +
                                        '<td>' + escapeHtml(row.batch_no) +
                                        '</td>' +
                                        '<td>' + (row.qty) + '</td>' +
                                        '<td>' + escapeHtml(row.supplier) +
                                        '</td>' +
                                        '<td>' + escapeHtml(row.stocked_on) +
                                        '</td>' +
                                        '<td>' + escapeHtml(row.expiry_date) +
                                        '</td>' +
                                        '</tr>');
                                });
                            } else {
                                lowBody.html(
                                    '<tr><td colspan="5" class="text-center text-muted">No low stock</td></tr>'
                                );
                            }


                            // Finished
                            var finishedBody = $('#finishedStockTableBody').empty();
                            if (response.finished.length) {
                                $.each(response.finished, function(i, row) {
                                    finishedBody.append('<tr>' +
                                        '<td>' + escapeHtml(row.batch_no) +
                                        '</td>' +
                                        '<td>' + (row.qty) + '</td>' +
                                        '<td>' + escapeHtml(row.supplier) +
                                        '</td>' +
                                        '<td>' + escapeHtml(row.stocked_on) +
                                        '</td>' +
                                        '<td>' + escapeHtml(row.expiry_date) +
                                        '</td>' +
                                        '</tr>');
                                });
                            } else {
                                finishedBody.html(
                                    '<tr><td colspan="5" class="text-center text-muted">No finished stock</td></tr>'
                                );
                            }

                        }

                        if (remaining > 0) {
                            setTimeout(renderData, remaining);
                        } else {
                            renderData();
                        }
                    },
                    error: function(xhr) {
                        $('#modalItemName').text('Error');
                        $('#fineStockTableBody').html(
                            '<tr><td colspan="5">Error loading data</td></tr>');
                        $('#expiredStockTableBody').html(
                            '<tr><td colspan="5">Error loading data</td></tr>');
                        $('#lowStockTableBody').html(
                            '<tr><td colspan="5">Error loading data</td></tr>');
                        $('#finishedStockTableBody').html(
                            '<tr><td colspan="5">Error loading data</td></tr>');
                    }
                });
            });

            $('#stockModal').on('hidden.bs.modal', function() {
                $('#modalItemName').text('');
                $('#fineStockTableBody').empty();
                $('#expiredStockTableBody').empty();
                $('#lowStockTableBody').empty();
                $('#finishedStockTableBody').empty();
                $('#stockModal .nav-link:first').addClass('active');
                $('#stockModal .nav-link:last').removeClass('active');
                $('#fineStock').addClass('show active');
                $('#expiredStock').removeClass('show active');
                $('#lowStock').removeClass('show active');
                $('#finishedStock').removeClass('show active');

            });
        });

        $(function() {

            $.fn.dataTable.ext.errMode = 'throw'; // show errors in console

            // allow exporting as pdf and excel
            $('#TableOne').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('stocks.balance.data') }}", // JSON endpoint
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'medicine_name',
                        name: 'item.name'
                    },
                    {
                        data: 'remain_quantity',
                        name: 'remain_quantity'
                    },
                    {
                        data: 'expired_remain_quantity',
                        name: 'expired_remain_quantity',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: true
                    },

                    {
                        data: 'stock_check_status',
                        name: 'stock_check_status',
                        orderable: false,
                        searchable: false,
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                pageLength: 10,
                order: [
                    [1, 'asc']
                ], // default sort by medicine name
                dom: 'Bfrtip', //make sure it does not disable dynamic page length set section
            });
        });

        // Handle stock check save
        $(document).on('click', '.save-stock-check', function() {
            const button = $(this);
            const row = button.closest('tr');
            const itemId = button.data('item-id');
            const quantity = row.find('.physical-qty').val();

            if (quantity === '' || quantity < 0) {
                alert('Please enter a valid quantity.');
                return;
            }

            button.prop('disabled', true).text('Saving...');

            $.ajax({
                url: "{{ route('stocks.check.save') }}", // ✅ create this route
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    item_id: itemId,
                    physical_quantity: quantity
                },

                success: function(response) {


                    if (response.success) {

                        // alert('Item ID: ' + itemId + ', Quantity: ' + quantity);

                        // alert('Stock check saved successfully!');
                        $('#TableOne').DataTable().ajax.reload(null,
                            false); // reload without changing page
                    } else {
                        alert('Failed to save stock check.');
                    }
                },
                error: function() {
                    //check if item id and quantity are being sent
                    // alert('Item ID: ' + itemId + ', Quantity: ' + quantity);
                    alert('Error saving stock check.');
                },
                complete: function() {
                    button.prop('disabled', false).text('Save');
                }
            });
        });
    </script>
@endsection
