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
                        <th class="text-danger">Expired Quantity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockBalances as $stock)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stock->item->name ?? 'Unknown' }}</td>
                            <td class="text-dark fw-bold">{{ $stock->remain_quantity }}</td>
                            <td
                                class="{{ $stock->expired_remain_quantity > 0 ? 'text-danger fw-bold' : 'text-dark fw-bold' }}">
                                {{ $stock->expired_remain_quantity }}
                            </td>
                            <td>
                                @if ($stock->remain_quantity > 0)
                                    <span class="text-success fw-bold">Available</span>
                                @elseif ($stock->expired_remain_quantity > 0)
                                    <span class="text-danger fw-bold">Expired</span>
                                @else
                                    <span class="text-warning fw-bold">Finished</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm view-stock-btn" data-bs-toggle="modal"
                                    data-bs-target="#stockModal" data-item-id="{{ $stock->item_id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No stocks available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $stockBalances->links() }}
        </div>

        <!-- Modal -->
        <div class="modal fade" id="stockModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Stock Details for <span id="modalItemName"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="stockTabs">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#fineStock">Fine</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#expiredStock">Expired</button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
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

            $('#TableOne').DataTable({
                paging: false, // disable DataTables pagination
                info: false, // disable "Showing page..." info
                searching: true, // keep search box
                ordering: true, // optional: keep column sorting
                lengthChange: false, // disable "Show _MENU_ entries"
                language: {
                    zeroRecords: "No records found",
                    search: "Search:"
                }
            });

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
                        console.log(response);
                        var elapsed = Date.now() - start;
                        var remaining = MIN_LOADING_MS - elapsed;

                        function renderData() {
                            if (!response.success) {
                                $('#modalItemName').text('Error');
                                $('#fineStockTableBody').html(
                                    '<tr><td colspan="5">Could not load data</td></tr>');
                                $('#expiredStockTableBody').html(
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
                    }
                });
            });

            $('#stockModal').on('hidden.bs.modal', function() {
                $('#modalItemName').text('');
                $('#fineStockTableBody').empty();
                $('#expiredStockTableBody').empty();
                $('#stockModal .nav-link:first').addClass('active');
                $('#stockModal .nav-link:last').removeClass('active');
                $('#fineStock').addClass('show active');
                $('#expiredStock').removeClass('show active');
            });
        });
    </script>
@endsection
