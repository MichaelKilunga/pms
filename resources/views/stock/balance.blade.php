@extends('stock.app')

@section('content')
    <div class="container">
        <h4 class="text-primary fs-2 fw-bold mt-2 mb-1">Stock Balance Summary</h4>
        <hr class="mt-2 mb-2">

        {{-- <hr class="mb-2"> --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="TableOne">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        {{-- <th>Stocked Quantity</th> --}}
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
                            <td>{{ $stock->item->name }}</td>
                            {{-- <td>{{ $stock->quantity }}</td> --}}
                            <td class="text-dark fw-bold">{{ $stock->remain_Quantity }}</td>
                            @if ($stock->expired_remain_Quantity > 0)
                                <td class="text-danger fw-bold">{{ $stock->expired_remain_Quantity }}</td>
                            @else
                                <td class="text-dark fw-bold">{{ $stock->expired_remain_Quantity }}</td>
                            @endif
                            <td>
                                @if ($stock->remain_Quantity > 0)
                                    <span class="text-success fw-bold"><p>Available</p></span>
                                @elseif ($stock->expired_remain_Quantity > 0)
                                    <span class="text-danger fw-bold">Expired</span>
                                @else
                                    <span class="text-warning fw-bold">Finished</span>
                                @endif
                            </td>
                            <td>
                                {{-- button to open modal that show all stocks of this medicine both fine ones and expired ones in a table manner --}}
                                <button type="button"
                                        class="btn btn-primary btn-sm view-stock-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#stockModal"
                                        data-item-id="{{ $stock->item_id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>

                    @empty

                    @endforelse
                </tbody>
            </table>
            {{ $stockBalances->links() }}
        </div>

        <!-- Single modal used for all items -->
        <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Stock Details for <span id="modalItemName"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="stockTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="fine-tab" data-bs-toggle="tab" data-bs-target="#fineStock" type="button" role="tab">Fine</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="expired-tab" data-bs-toggle="tab" data-bs-target="#expiredStock" type="button" role="tab">Expired</button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
                            <div class="tab-pane fade show active" id="fineStock" role="tabpanel">
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
                                        <tbody id="fineStockTableBody">
                                            <!-- injected rows -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="expiredStock" role="tabpanel">
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
                                        <tbody id="expiredStockTableBody">
                                            <!-- injected rows -->
                                        </tbody>
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
        // initialize datatable
        $(document).ready(function() {
            $('#TableOne').DataTable({
                pagenation: false,
                // "pageLength": 10,
                // "lengthMenu": [5, 10, 25, 50, 100],
                "language": {
                    "lengthMenu": "Show _MENU_ entries",
                    "zeroRecords": "No records found",
                    "info": "Showing page _PAGE_ of _PAGES_",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "search": "Search:",
                    // "paginate": {
                    //     "first": "First",
                    //     "last": "Last",
                    //     "next": "Next",
                    //     "previous": "Previous"
                    // }
                }
            });
        });
    </script>

    <!-- Example JavaScript for populating and opening the modal (needs medicine data from backend) -->
    {{-- <script>
        $(function() {
            // helper: safe-escape text for insertion
            function escapeHtml(str) {
                if (str === null || str === undefined) return '';
                return String(str)
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // When the modal is about to be shown, populate it
            $('#stockModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that opened the modal
                var itemId = button.data('item-id');

                $('#modalItemName').text('Loading...');
                $('#fineStockTableBody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
                $('#expiredStockTableBody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');

                // Fetch data
                $.get('{{ route("stock.details") }}', { item_id: itemId }, function(response) {
                    if (!response || !response.success) {
                        $('#modalItemName').text('Error');
                        $('#fineStockTableBody').html('<tr><td colspan="5" class="text-danger text-center">Could not load data</td></tr>');
                        $('#expiredStockTableBody').html('<tr><td colspan="5" class="text-danger text-center">Could not load data</td></tr>');
                        return;
                    }

                    $('#modalItemName').text(response.item.name || 'Unknown');

                    // Fill fine stock
                    var fineBody = $('#fineStockTableBody').empty();
                    if (response.fine && response.fine.length) {
                        $.each(response.fine, function(i, row) {
                            fineBody.append(
                                '<tr>' +
                                    '<td>' + escapeHtml(row.batch_no) + '</td>' +
                                    '<td>' + escapeHtml(row.qty) + '</td>' +
                                    '<td>' + escapeHtml(row.manufacture_date || '') + '</td>' +
                                    '<td>' + escapeHtml(row.expiry_date || '') + '</td>' +
                                    '<td>' + escapeHtml(row.location || '') + '</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        fineBody.html('<tr><td colspan="5" class="text-muted text-center">No fine stock</td></tr>');
                    }

                    // Fill expired stock
                    var expiredBody = $('#expiredStockTableBody').empty();
                    if (response.expired && response.expired.length) {
                        $.each(response.expired, function(i, row) {
                            expiredBody.append(
                                '<tr>' +
                                    '<td>' + escapeHtml(row.batch_no) + '</td>' +
                                    '<td>' + escapeHtml(row.qty) + '</td>' +
                                    '<td>' + escapeHtml(row.manufacture_date || '') + '</td>' +
                                    '<td>' + escapeHtml(row.expiry_date || '') + '</td>' +
                                    '<td>' + escapeHtml(row.location || '') + '</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        expiredBody.html('<tr><td colspan="5" class="text-muted text-center">No expired stock</td></tr>');
                    }
                }).fail(function(xhr) {
                    $('#modalItemName').text('Error');
                    $('#fineStockTableBody').html('<tr><td colspan="5" class="text-danger text-center">Error loading data</td></tr>');
                    $('#expiredStockTableBody').html('<tr><td colspan="5" class="text-danger text-center">Error loading data</td></tr>');
                    console.error('Stock details error:', xhr.responseText);
                });
            });

            // Optional: clear modal content when hidden (keeps it tidy)
            $('#stockModal').on('hidden.bs.modal', function () {
                $('#modalItemName').text('');
                $('#fineStockTableBody').empty();
                $('#expiredStockTableBody').empty();
                // reset to first tab
                $('#stockModal .nav-tabs .nav-link').removeClass('active');
                $('#stockModal .tab-pane').removeClass('show active');
                $('#stockModal .nav-tabs .nav-link#fine-tab').addClass('active');
                $('#stockModal .tab-pane#fineStock').addClass('show active');
            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            // keep your existing escapeHtml helper here
            function escapeHtml(str) {
                if (str === null || str === undefined) return '';
                return String(str)
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Minimum time (ms) the loading UI will be visible
            var MIN_LOADING_MS = 800;

            $('#stockModal').off('show.bs.modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var itemId = button.data('item-id');

                // Spinner markup (Bootstrap)
                var spinnerRow = '<tr><td colspan="5" class="text-center py-4">' +
                    '<div class="spinner-border" role="status" aria-hidden="true"></div>' +
                    '<div class="mt-2 small">Loading...</div>' +
                    '</td></tr>';

                // Set placeholders
                $('#modalItemName').css('color', 'red');
                $('#modalItemName').text('Loading...');
                $('#fineStockTableBody').html(spinnerRow);
                $('#expiredStockTableBody').html(spinnerRow);

                var start = Date.now();
                var responded = false;

                function showErrorBodies(msg) {
                    var errorRow = '<tr><td colspan="5" class="text-danger text-center py-2">' + escapeHtml(msg) + '</td></tr>';
                    $('#fineStockTableBody').html(errorRow);
                    $('#expiredStockTableBody').html(errorRow);
                }

                function renderResponse(response) {
                    // render item name
                    $('#modalItemName').text(response.item.name || 'Unknown');

                    // fill fine
                    var fineBody = $('#fineStockTableBody').empty();
                    if (response.fine && response.fine.length) {
                        $.each(response.fine, function(i, row) {
                            fineBody.append(
                                '<tr>' +
                                    '<td>' + escapeHtml(row.batch_no) + '</td>' +
                                    '<td>' + escapeHtml(row.qty) + '</td>' +
                                    '<td>' + escapeHtml(row.supplier || '') + '</td>' +
                                    '<td>' + escapeHtml(row.stocked_on || '') + '</td>' +
                                    '<td>' + escapeHtml(row.expiry_date || '') + '</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        fineBody.html('<tr><td colspan="5" class="text-muted text-center">No fine stock</td></tr>');
                    }

                    // fill expired
                    var expiredBody = $('#expiredStockTableBody').empty();
                    if (response.expired && response.expired.length) {
                        $.each(response.expired, function(i, row) {
                            expiredBody.append(
                                '<tr>' +
                                    '<td>' + escapeHtml(row.batch_no) + '</td>' +
                                    '<td>' + escapeHtml(row.qty) + '</td>' +
                                    '<td>' + escapeHtml(row.supplier || '') + '</td>' +
                                    '<td>' + escapeHtml(row.stocked_on || '') + '</td>' +
                                    '<td>' + escapeHtml(row.expiry_date || '') + '</td>' +
                                '</tr>'
                            );
                        });
                    } else {
                        expiredBody.html('<tr><td colspan="5" class="text-muted text-center">No expired stock</td></tr>');
                    }
                }

                // Fire AJAX (using $.ajax)
                $.ajax({
                    url: `/stock/details/${itemId}`,
                    method: 'GET',
                    data: { item_id: itemId },
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        responded = true;
                        var elapsed = Date.now() - start;
                        var remaining = MIN_LOADING_MS - elapsed;

                        console.log(response.fine);

                        function finishRender() {
                            if (!response || !response.success) {
                                $('#modalItemName').text('Error');
                                showErrorBodies('Could not load data');
                                return;
                            }
                            renderResponse(response);
                        }

                        if (remaining > 0) {
                            setTimeout(finishRender, remaining);
                        } else {
                            finishRender();
                        }
                    },
                    error: function(xhr, status, error) {
                        responded = true;
                        var elapsed = Date.now() - start;
                        var remaining = MIN_LOADING_MS - elapsed;

                        function finishError() {
                            $('#modalItemName').text('Error');
                            var message = 'Error loading data';
                            try {
                                var json = JSON.parse(xhr.responseText || '{}');
                                if (json && json.message) message = json.message;
                            } catch (e) {}
                            showErrorBodies(message);
                            console.error('Stock details error:', xhr.responseText);
                        }

                        if (remaining > 0) {
                            setTimeout(finishError, remaining);
                        } else {
                            finishError();
                        }
                    }
                });

            });

            // optional: clear and reset tabs when modal hidden
            $('#stockModal').on('hidden.bs.modal', function () {
                $('#modalItemName').text('');
                $('#fineStockTableBody').empty();
                $('#expiredStockTableBody').empty();
                // reset to first tab
                $('#stockModal #fine-tab').addClass('active');
                $('#stockModal #expired-tab').removeClass('active');
                $('#stockModal #fineStock').addClass('show active');
                $('#stockModal #expiredStock').removeClass('show active');
            });
        });
</script>


@endsection
