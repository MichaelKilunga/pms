    @section('title', 'Dashboard')
    @section('meta_description', 'Pharmacy Management System Dashboard')
    @section('meta_keywords', 'Pharmacy Management System Dashborad')

    <style>
        .quick-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border-radius: 12px;
            overflow: hidden;
        }

        .quick-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .quick-card .card-body {
            padding: 1.5rem;
        }

        .quick-card i {
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .modal-content {
            border-radius: 15px;
        }

        .btn-outline-primary:hover,
        .btn-outline-success:hover,
        .btn-outline-danger:hover {
            color: white !important;
        }

        .medicine-select-row .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
    </style>

    <div class="container mt-2">
        @hasanyrole('Owner|Manager')
            {{-- Quick Actions Section --}}
            <div class="row g-4 justify-content-center mb-4 text-center">
                <div class="col-6 col-md-4 col-lg-3">
                    <button class="card bg-primary text-decoration-none text-white shadow-sm w-100 border-0 h-100"
                        data-bs-target="#quickAccessModal" data-bs-toggle="modal">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">

                            <i class="bi bi-plus-circle fs-1 mb-2"></i>
                            <h6 class="mb-0">Add Stock</h6>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a class="card bg-success text-decoration-none text-white shadow-sm w-100 border-0 h-100"
                        href="{{ route('stocks.balance') }}">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-wallet2 fs-1 mb-2"></i>
                            <h6 class="mb-0">Stock Balance</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a class="card bg-warning text-dark text-decoration-none shadow-sm w-100 border-0 h-100"
                        href="{{ route('stock') }}">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-box-seam fs-1 mb-2"></i>
                            <h6 class="mb-0">Preview Stocks</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-3">
                    <a class="card bg-danger text-decoration-none text-white shadow-sm w-100 border-0 h-100"
                        href="{{ route('stockTransfers.index') }}">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-arrow-left-right fs-1 mb-2"></i>
                            <h6 class="mb-0">Stock Transfer</h6>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row g-4 justify-content-center mb-4 text-center">
                <div class="col-6 col-md-4 col-lg-2">
                    <button class="card bg-info text-decoration-none text-white shadow-sm w-100 border-0 h-100"
                        data-bs-target="#createSalesNoteModal" data-bs-toggle="modal">
                        <div class="card-body">
                            <h6><i class="bi bi-plus-circle fs-3 mb-1"></i> Sales Notebook</h6>
                        </div>
                    </button>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a class="card bg-secondary text-decoration-none text-white shadow-sm w-100 border-0 h-100"
                        href="{{ route('staff') }}">
                        <div class="card-body">
                            <h6><i class="bi bi-people-fill fs-3 mb-1"></i>Manage Pharmacist</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a class="card bg-dark text-white text-decoration-none shadow-sm w-100 border-0 h-100"
                        href="{{ route('pharmacies') }}">
                        <div class="card-body">
                            <h6><i class="bi bi-hospital fs-3 mb-1"></i> View Pharmacies</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a class="card bg-primary text-decoration-none text-white shadow-sm w-100 border-0 h-100"
                        data-bs-target="#createSalesModal" data-bs-toggle="modal" href="">
                        <div class="card-body">
                            <h6><i class="bi bi-cart-plus fs-3 mb-1"></i> Create new Sales</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a class="card bg-success text-decoration-none text-white shadow-sm w-100 border-0 h-100"
                        href="{{ route('reports.all') }}">
                        <div class="card-body">
                            <h6><i class="bi bi-file-earmark-bar-graph fs-3 mb-1"></i> Reports</h6>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <a class="card bg-warning text-dark text-decoration-none shadow-sm w-100 border-0 h-100"
                        href="{{ route('reports.index') }}">
                        <div class="card-body">
                            <h6><i class="bi bi-graph-up fs-3 mb-1"></i> Analytics</h6>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Summary Section --}}
            <div class="row g-4 justify-content-center mb-4 text-center">
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card bg-primary text-white shadow">
                        <div class="card-body">
                            <h6>
                                <i class="bi bi-capsule fs-3# me-2"></i>Medicines
                            </h6>
                            <p class="fs-5 fw-bold">{{ $totalMedicines }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                            <h6>
                                <i class="bi bi-people-fill fs-3# me-2"></i>Pharmacists
                            </h6>
                            <p class="fs-5 fw-bold">{{ $totalStaff }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card bg-warning text-dark shadow">
                        <div class="card-body">
                            <h6>
                                <i class="bi bi-hospital fs-2# me-2"></i>Pharmacies
                            </h6>
                            <p class="fs-5 fw-bold">{{ $totalPharmacies }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card bg-danger text-white shadow">
                        <div class="card-body">
                            <h6>
                                <i class="bi bi-exclamation-triangle fs-3# me-2"></i>Expired
                            </h6>
                            <p class="fs-5 fw-bold">{{ $stockExpired }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card bg-info text-white shadow">
                        <div class="card-body">
                            <h6>
                                <i class="bi bi-currency-exchange fs-3# me-2"></i>Today Sales
                            </h6>
                            <p class="fs-5 fw-bold">{{ number_format($totalSales, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card bg-secondary text-light shadow">
                        <div class="card-body">
                            <h6>
                                <i class="bi bi-box-seam fs-3# me-2"></i>Low Stock
                            </h6>
                            <p class="fs-5 fw-bold">{{ $lowStockCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endhasanyrole
    </div>

    {{-- Quick Access Modal --}}
    <div aria-hidden="true" aria-labelledby="quickAccessModalLabel" class="modal fade" id="quickAccessModal"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg px-2">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold" id="quickAccessModalLabel">
                        <i class="bi bi-lightning-fill me-2"></i>Quick Addition
                    </h5>
                    <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        type="button"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <button
                                class="btn btn-outline-primary w-100 py-3 d-flex align-items-center justify-content-between shadow-sm"
                                data-bs-target="#createStockModal" data-bs-toggle="modal">
                                <span class="fs-5 fw-bold"><i class="bi bi-plus-square-fill me-3"></i>Add New
                                    Stock</span>
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                        <div class="col-12">
                            <button
                                class="btn btn-outline-success w-100 py-3 d-flex align-items-center justify-content-between shadow-sm"
                                data-bs-target="#createMedicineStockModal" data-bs-toggle="modal">
                                <span class="fs-5 fw-bold"><i class="bi bi-bag-plus-fill me-3"></i>Medicine +
                                    Stock</span>
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                        <div class="col-12">
                            <button
                                class="btn btn-outline-danger w-100 py-3 d-flex align-items-center justify-content-between shadow-sm"
                                data-bs-target="#importMedicineStockModal" data-bs-toggle="modal">
                                <span class="fs-5 fw-bold"><i class="bi bi-file-earmark-arrow-up-fill me-3"></i>Import
                                    CSV</span>
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $all_medicines = App\Models\Items::where('pharmacy_id', session('current_pharmacy_id'))
            ->with('lastStock')
            ->get();
    @endphp

    {{-- <!-- Add New Stock Modal --> --}}
    <div aria-hidden="true" aria-labelledby="createStockModalLabel" class="modal fade" id="createStockModal"
        tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold" id="createStockModalLabel">Add New Stock</h5>
                    <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        type="button"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('stock.store') }}" id="stockFormDashboard" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stock Batch Number</label>
                                <input class="form-control bg-light batch_num_field" name="batch_number" readonly
                                    type="text">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Supplier Name</label>
                                <input class="form-control" name="supplier" placeholder="Enter supplier name"
                                    required type="text">
                            </div>
                        </div>
                        <div id="stockFieldsDashboard">
                            <div class="row stock-entry align-items-end g-3 mb-3 border-bottom pb-3">
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label fw-bold">Medicine Name</label>
                                    <select class="medicineSelect chosen form-select" name="item_id[]" required>
                                        <option value="">Select medicine...</option>
                                        @foreach ($all_medicines as $medicine)
                                            <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-3">
                                    <label class="form-label fw-bold">Buying Price</label>
                                    <input class="form-control" min="1" name="buying_price[]" required
                                        type="number">
                                </div>
                                <div class="col-lg-2 col-md-3">
                                    <label class="form-label fw-bold">Selling Price</label>
                                    <input class="form-control" min="1" name="selling_price[]" required
                                        type="number">
                                </div>
                                <div class="col-lg-1 col-md-3">
                                    <label class="form-label fw-bold">Qty</label>
                                    <input class="form-control" min="1" name="quantity[]" required
                                        type="number">
                                </div>
                                <div class="col-lg-1 col-md-3">
                                    <label class="form-label fw-bold">Low</label>
                                    <input class="form-control" min="1" name="low_stock_percentage[]" required
                                        type="number">
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <label class="form-label fw-bold">Expire Date</label>
                                    <input class="form-control" name="expire_date[]" required type="date">
                                </div>
                                <input name="in_date[]" type="hidden" value="{{ now() }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button class="btn btn-outline-primary" id="addStockBtnDashboard" type="button">
                                <i class="bi bi-plus-lg me-2"></i>Add Row
                            </button>
                            <button class="btn btn-primary px-5" type="submit">
                                <i class="bi bi-save me-2"></i>Save Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Medicine + Stock Modal -->
    <div aria-hidden="true" aria-labelledby="createMedicineStockModalLabel" class="modal fade"
        id="createMedicineStockModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title fw-bold" id="createMedicineStockModalLabel">Add New Stock and Medicine</h5>
                    <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        type="button"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('medicineStock.store') }}" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Stock Batch Number</label>
                                <input class="form-control bg-light batch_num_field" name="batch_number" readonly
                                    type="text">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Supplier Name</label>
                                <input class="form-control" name="supplier" placeholder="Enter supplier name"
                                    required type="text">
                            </div>
                        </div>
                        <div id="medicineStockFieldsDashboard">
                            <div class="row stock-entry align-items-end g-3 mb-3 border-bottom pb-3">
                                <div class="col-lg-3 col-md-6">
                                    <label class="form-label fw-bold">New Medicine Name</label>
                                    <input class="form-control" name="item_name[]" placeholder="e.g. Panadol"
                                        required type="text">
                                </div>
                                <div class="col-lg-2 col-md-3">
                                    <label class="form-label fw-bold">Buying Price</label>
                                    <input class="form-control" name="buying_price[]" required type="number">
                                </div>
                                <div class="col-lg-2 col-md-3">
                                    <label class="form-label fw-bold">Selling Price</label>
                                    <input class="form-control" name="selling_price[]" required type="number">
                                </div>
                                <div class="col-lg-1 col-md-3">
                                    <label class="form-label fw-bold">Qty</label>
                                    <input class="form-control" name="quantity[]" required type="number">
                                </div>
                                <div class="col-lg-1 col-md-3">
                                    <label class="form-label fw-bold">Low</label>
                                    <input class="form-control" min="1" name="low_stock_percentage[]" required
                                        type="number">
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <label class="form-label fw-bold">Expire Date</label>
                                    <input class="form-control" name="expire_date[]" required type="date">
                                </div>
                                <input name="in_date[]" type="hidden" value="{{ now() }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button class="btn btn-outline-success" id="addMedicineStockBtnDashboard" type="button">
                                <i class="bi bi-plus-lg me-2"></i>Add Row
                            </button>
                            <button class="btn btn-success px-5" type="submit">
                                <i class="bi bi-save me-2"></i>Save Medicine & Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Import Medicine and Stock Modal --}}
    <div aria-hidden="true" aria-labelledby="importMedicineStockModalLabel" class="modal fade"
        id="importMedicineStockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold" id="importMedicineStockModalLabel">Import Medicines and Stock</h5>
                    <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        type="button"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Import Instructions</h6>
                                <p class="mb-0 small">Please use our template to ensure correct data formatting.
                                    Required columns: item_name, buying_price, selling_price, quantity,
                                    low_stock_percentage, expire_date, supplier.</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a class="btn btn-sm btn-outline-info fw-bold"
                                href="/templates/medicine_import_template.csv" download>
                                <i class="bi bi-download me-1"></i>Download CSV Template
                            </a>
                        </div>
                    </div>
                    <form action="{{ route('importMedicineStock') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold" for="file">Select CSV File</label>
                            <input accept=".csv" class="form-control form-control-lg" name="file" required
                                type="file">
                        </div>
                        <input class="batch_num_field" name="batch_number__" type="hidden">
                        <input name="in_date" type="hidden" value="{{ now() }}">
                        <button class="btn btn-danger w-100 py-2" type="submit">
                            <i class="bi bi-upload me-2"></i>Start Import Process
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script shadow>
        $(document).ready(function() {
            const medicinesData = @json($all_medicines);

            // Batch number generation
            const today = new Date();
            const formattedDate = today.getFullYear() +
                String(today.getMonth() + 1).padStart(2, '0') +
                String(today.getDate()).padStart(2, '0');
            $('.batch_num_field').val(formattedDate);

            function initializeChosen() {
                $(".chosen").each(function() {
                    let $select = $(this);
                    let $modal = $select.closest(".modal");
                    $select.select2({
                        width: "100%",
                        dropdownParent: $modal.length ? $modal : $("body")
                    });
                });
            }

            function setStockFieldsData(row) {
                const selectedMedicineId = $(row).find('[name="item_id[]"]').val();
                const selectedMedicine = medicinesData.find(m => m.id == selectedMedicineId);

                if (selectedMedicine && selectedMedicine.last_stock) {
                    $(row).find('[name="buying_price[]"]').val(selectedMedicine.last_stock.buying_price);
                    $(row).find('[name="selling_price[]"]').val(selectedMedicine.last_stock.selling_price);
                    $(row).find('[name="low_stock_percentage[]"]').val(selectedMedicine.last_stock
                        .low_stock_percentage);
                } else {
                    $(row).find('[name="buying_price[]"]').val('');
                    $(row).find('[name="selling_price[]"]').val('');
                    $(row).find('[name="low_stock_percentage[]"]').val('');
                }
            }

            function attachPriceValidation(modalSelector) {
                const $modal = $(modalSelector);
                const $form = $modal.find('form');

                $form.on('input change', '[name="buying_price[]"], [name="selling_price[]"]', function() {
                    const $row = $(this).closest('.stock-entry');
                    const bp = parseFloat($row.find('[name="buying_price[]"]').val()) || 0;
                    const sp = parseFloat($row.find('[name="selling_price[]"]').val()) || 0;

                    $row.find('.field-error').remove();

                    if (sp > 0 && bp > 0 && sp < bp) {
                        $row.append(
                            '<div class="field-error text-danger fw-bold small mt-1 w-100">Selling price must be ≥ Buying price</div>'
                        );
                        $form.find('[type="submit"]').prop('disabled', true);
                    } else {
                        let hasGlobalErrors = false;
                        $form.find('.stock-entry').each(function() {
                            const rowBp = parseFloat($(this).find('[name="buying_price[]"]')
                                .val()) || 0;
                            const rowSp = parseFloat($(this).find('[name="selling_price[]"]')
                                .val()) || 0;
                            if (rowSp > 0 && rowBp > 0 && rowSp < rowBp) hasGlobalErrors = true;
                        });
                        $form.find('[type="submit"]').prop('disabled', hasGlobalErrors);
                    }
                });
            }

            initializeChosen();
            attachPriceValidation('#createStockModal');
            attachPriceValidation('#createMedicineStockModal');

            $(document).on('change', '.medicineSelect', function() {
                setStockFieldsData($(this).closest('.stock-entry'));
            });

            // Add new stock row
            $('#addStockBtnDashboard').on('click', function() {
                const newRow = $(`
                    <div class="row stock-entry align-items-end g-3 mb-3 border-bottom pb-3">
                        <div class="col-lg-3 col-md-6">
                            <select class="medicineSelect chosen form-select" name="item_id[]" required>
                                <option value="">Select medicine...</option>
                                @foreach ($all_medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <input class="form-control" min="1" name="buying_price[]" required type="number">
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <input class="form-control" min="1" name="selling_price[]" required type="number">
                        </div>
                        <div class="col-lg-1 col-md-3">
                            <input class="form-control" min="1" name="quantity[]" required type="number">
                        </div>
                        <div class="col-lg-1 col-md-3">
                            <input class="form-control" min="1" name="low_stock_percentage[]" required type="number">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <input class="form-control" name="expire_date[]" required type="date">
                        </div>
                        <div class="col-lg-1 col-md-1 d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-row-dashboard"><i class="bi bi-trash"></i></button>
                        </div>
                        <input name="in_date[]" type="hidden" value="{{ now() }}">
                    </div>
                `);
                $('#stockFieldsDashboard').append(newRow);
                initializeChosen();
            });

            // Add new medicine+stock row
            $('#addMedicineStockBtnDashboard').on('click', function() {
                const newRow = $(`
                    <div class="row stock-entry align-items-end g-3 mb-3 border-bottom pb-3">
                        <div class="col-lg-3 col-md-6">
                            <input class="form-control" name="item_name[]" required type="text">
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <input class="form-control" name="buying_price[]" required type="number">
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <input class="form-control" name="selling_price[]" required type="number">
                        </div>
                        <div class="col-lg-1 col-md-3">
                            <input class="form-control" name="quantity[]" required type="number">
                        </div>
                        <div class="col-lg-1 col-md-3">
                            <input class="form-control" min="1" name="low_stock_percentage[]" required type="number">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <input class="form-control" name="expire_date[]" required type="date">
                        </div>
                        <div class="col-lg-1 col-md-1 d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-row-dashboard"><i class="bi bi-trash"></i></button>
                        </div>
                        <input name="in_date[]" type="hidden" value="{{ now() }}">
                    </div>
                `);
                $('#medicineStockFieldsDashboard').append(newRow);
            });

            $(document).on('click', '.remove-row-dashboard', function() {
                $(this).closest('.stock-entry').remove();
            });
        });
    </script>
    @hasrole('Staff')
        {{-- Quick Actions Section --}}
        <div class="row g-4 justify-content-center mb-4 text-center">
            <div class="col-6 col-md-4 col-lg-2">
                <a class="card bg-primary text-decoration-none text-white shadow" data-bs-target="#createSalesModal"
                    data-bs-toggle="modal" href="">
                    <div class="card-body">
                        <h6><i class="bi bi-cart-plus fs-1#"></i> Create new Sales</h6>

                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <button class="card bg-info text-decoration-none text-white shadow" data-bs-target="#createSalesNoteModal"
                    data-bs-toggle="modal">
                    <div class="card-body">
                        <h6><i class="bi bi-plus-circle fs-1#"></i> Create Sales Notebook</h6>
                        {{-- <p class="fs-5 fw-bold">{{ $totalMedicines }}</p> --}}
                    </div>
                </button>
            </div>
            {{-- Summary Section --}}
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-secondary text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-capsule fs-3# me-2"></i>Medicines
                        </h6>
                        <p class="fs-5 fw-bold">{{ $totalMedicines }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-exclamation-triangle fs-3# me-2"></i>Expired
                        </h6>
                        <p class="fs-5 fw-bold">{{ $stockExpired }}</p>
                    </div>
                </div>
            </div>
            <!-- Locked sales card (hidden initially) -->
            <div class="col-6 col-md-4 col-lg-2" id="totalSalesCard" style="display: none;">
                <div class="card bg-success text-white shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-currency-exchange fs-3# me-2"></i>Today Sales
                        </h6>
                        <p class="fs-5 fw-bold">{{ number_format($totalSales, 2) }} TZS</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card bg-warning text-dark shadow">
                    <div class="card-body">
                        <h6>
                            <i class="bi bi-box-seam fs-3# me-2"></i>Low Stock
                        </h6>
                        <p class="fs-5 fw-bold">{{ $lowStockCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endhasrole

    {{-- Sales Filter Section --}}
    <div class="row mb-4">
        {{-- Sales Vs medicine graph --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow">
                <div class="card-body salesGraph">
                    <h4 class="text-center">Sales vs Medicines Graph</h4>
                    <canvas id="salesGraph"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            {{-- Search   Medicine --}}
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <h4 class="mb-3 text-center">Search Medicine</h4>
                            <form class="row gy-2 gx-3 align-items-center justify-content-center" id="search_form">
                                <hr>
                                <div class="col-auto">
                                    <input class="form-control search" id="medicine" name="searchValue"
                                        type="text">
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="bi bi-search"></i></button>
                                </div>
                            </form>
                            <div class="ml-5 mt-3">
                                <h5 class="fw-bold ml-7">
                                    Status: <span class="text-success" id="avaiable_medicine"></span>
                                    <br>Similar Medicines Available: <span class="text-warning"
                                        id="similar_medicine"></span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sales filter --}}
            @hasanyrole('Owner|Manager')
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <h4 class="mb-3 text-center">Total Sales</h4>
                                <form class="row gy-2 gx-3 align-items-center justify-content-center" id="filter-Form">
                                    <hr>
                                    <div class="col-auto">
                                        <select class="form-select" name="filter" required>
                                            <option {{ $filter == 'day' ? 'selected' : '' }} value="day">Today
                                            </option>
                                            <option {{ $filter == 'week' ? 'selected' : '' }} value="week">This
                                                Week
                                            </option>
                                            <option {{ $filter == 'month' ? 'selected' : '' }} value="month">This
                                                Month
                                            </option>
                                            <option {{ $filter == 'year' ? 'selected' : '' }} value="year">This
                                                Year
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-primary" id="apply-filter-btn" type="submit">Apply
                                            Filter</button>
                                    </div>
                                </form>

                                <div class="mt-3 text-center">
                                    <h5 class="fw-bold">
                                        Total Sales in Selected Range: <span
                                            class="text-success total-sales">{{ number_format($filteredTotalSales) }}
                                            TZS</span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endhasanyrole

            @hasrole('Staff')
                <!-- Eye icon (initial display) -->
                <div class="mt-3 text-center" id="unlock-section">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <h4 class="mb-3 text-center">Total Sales</h4>
                                <hr>
                                <button class="btn btn-md btn-info mt-2 text-white" id="eye-icon">
                                    <i class="fas fa-eye-slash fa-1x" style="cursor: pointer;"></i>
                                </button>
                                <p>Click the eye icon to unlock the sales report.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Password prompt (hidden initially) -->
                <div class="mt-3 text-center" id="password-section" style="display: none;">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <h4 class="mb-3 text-center">Total Sales</h4>
                                <hr class="mb-2">
                                <input class="form-control d-inline-block w-auto" id="unlock-password"
                                    placeholder="Enter Password" required type="password">
                                <button class="btn btn-primary" id="check-password-btn">Unlock</button> <br>
                                <span class="text-danger text-small" id="passwordCHechError"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Locked report (hidden initially) -->
                <div class="row myreportcheck mt-2" style="display: none;">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <h4 class="mb-3 text-center">Total Sales</h4>
                                <form class="row gy-2 gx-3 align-items-center justify-content-center" id="filter-Form">
                                    <hr>
                                    <div class="col-auto">
                                        <select class="form-select" name="filter" required>
                                            <option {{ $filter == 'day' ? 'selected' : '' }} value="day">Today
                                            </option>
                                            {{-- <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>This Week
                                        </option>
                                        <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>This
                                            Month
                                        </option>
                                        <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>This Year
                                        </option> --}}
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-primary" id="apply-filter-btn" type="submit">Apply
                                            Filter</button>
                                    </div>
                                </form>

                                <div class="mt-3 text-center">
                                    <h5 class="fw-bold">
                                        Total Sales in Selected Range:
                                        <span class="text-success total-sales">{{ number_format($filteredTotalSales) }}
                                            TZS</span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endhasrole

        </div>
    </div>

    {{-- Stock Section --}}
    <div class="row mb-4">
        {{-- Stock Summary Table --}}
        <div class="col-md-6 mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="text-center">Stock Summary</h4>
                        <div class="table-responsive mt-2">
                            <table class="table-striped table-hover table" id="Table">
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
        {{-- Stock Vs medicine graph --}}
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow">
                <div class="card-body">
                    <h4 class="text-center">Stock vs Medicines Graph</h4>
                    <canvas id="stockGraph"></canvas>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Create Sales Modal -->
    <div aria-hidden="true" aria-labelledby="createSalesModalLabel" class="modal fade" id="createSalesModal"
        tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createSalesModalLabel">Add New Sale</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sales.store') }}" id="salesForm" method="POST">
                        @csrf
                        <div id="salesFields">
                            <div class="row sale-entry align-items-center mb-3">
                                <input hidden name="stock_id[]" required type="text">
                                <div class="col-md-4">
                                    <label class="form-label">Medicine</label>
                                    <select class="chosen form-select" name="item_id[]" required>
                                        <option disabled selected value="">Select Item</option>
                                        @foreach ($sellMedicines as $sellMedicine)
                                            {{-- <option value="{{ $sellMedicine->item->id }}">{{ $sellMedicine->item->name }}
                                        </option> --}}
                                            {{-- <option value="{{ $sellMedicine->id }}">
                                            {{ $sellMedicine->item->name }}
                                            <br><strong
                                                class="text-danger">({{ number_format($sellMedicine->selling_price) }}Tsh)</strong>
                                        </option> --}}
                                            <option
                                                value="{{ $sellMedicine->item->id }}_{{ $sellMedicine->selling_price }}">
                                                {{ $sellMedicine->item->name }}
                                                ({{ number_format($sellMedicine->selling_price) }}Tsh)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Price(TZS)</label>
                                    <input class="form-control" name="total_price[]" placeholder="Price" readonly
                                        required type="text" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label stock-label" for="label[]">Quantity</label>
                                    <input class="form-control" min="1" name="quantity[]"
                                        placeholder="Quantity" required title="Only 10 has remained in stock!"
                                        type="number">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Amount</label>
                                    <input class="form-control amount" name="amount[]" placeholder="Amount" readonly
                                        type="number">
                                </div>
                                <div class="col-md-0" hidden>
                                    <label class="form-label">Date</label>
                                    <input class="form-control date" name="date[]" required type="text"
                                        value="{{ now() }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-8 text-end">
                                <strong>Total Amount:</strong>
                            </div>
                            <div class="col-md-3 text-end">
                                <input class="form-control text-danger" id="totalAmount" readonly type="text"
                                    value="0">
                            </div>
                            <div class="col-md-1 text-end">
                                <!-- <input class="btn btn-outline-danger" disabled id="totalAmount" value="0"> -->
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="col-md-11 d-flex justify-content-between">
                                <button class="btn btn-outline-primary" id="addSaleRow" type="button">
                                    <i class="bi bi-plus-lg"></i> Add Row
                                </button>
                                <button class="btn btn-success" type="submit">
                                    <i class="bi bi-save"></i> Save Sales
                                </button>
                            </div>
                            <div class="col-md-1">

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Sales Note Modal --}}
    <div aria-hidden="true" aria-labelledby="createSalesNoteModalLabel" class="modal fade modal-lg"
        id="createSalesNoteModal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('salesNotes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="createSalesNoteModalLabel">Create Sales Note</h5>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                            type="button"></button>
                    </div>
                    <div class="modal-body d-flex flex-column">
                        <div class="form-floating mb-2">
                            <input class="form-control" id="floatingName" name="name" placeholder="Amoxicillin"
                                required type="text">
                            <label class="form-label" for="floatingName">Medicine Name<span
                                    class="text-danger">*</span></label>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3 form-floating">
                                <input class="form-control" id="floatingQuantity" min="1" name="quantity"
                                    placeholder="50" required type="number">
                                <label class="form-label" for="floatingQuantity">Quantity<span
                                        class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-5 form-floating">
                                <input class="form-control" id="floatingUnitPrice" min="1" name="unit_price"
                                    placeholder="200" required type="number">
                                <label class="form-label" for="floatingUnitPrice">Unit Selling Price<span
                                        class="text-danger">*</span></label>
                            </div>

                            {{-- quantity* unit selling price --}}
                            <div class="col-md-4 form-floating">
                                <input class="form-control fw-bold text-success" id="floatingTotalPrice"
                                    name="total_price" placeholder="100000" readonly type="text">
                                <label class="form-label fw-bold text-dark" for="TotalAmount">Total Price
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-floating mb-2">
                            <input class="form-control" id="floatingDescription" name="description"
                                placeholder="Sold for headache" type="text">
                            <label class="form-label" for="floatingDescription">Description <span
                                    class="text-success">(optional)</span></label>
                        </div>

                        <input hidden name="pharmacy_id" placeholder="Pharmacy ID" readonly required type="text"
                            value="{{ session('current_pharmacy_id') }}">
                        <input hidden name="staff_id" placeholder="Staff ID" readonly required type="text"
                            value="{{ auth()->id() }}">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script for Two Separate Graphs --}}
    <script>
        $(document).ready(function() {
            // Sales Graph Initialization
            function initializeGraph(context, labels, data, label, backgroundColor, borderColor) {
                return new Chart(context, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            backgroundColor: backgroundColor,
                            borderColor: borderColor,
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
            }

            // Initialize Sales Graph
            const salesGraphCtx = $('#salesGraph')[0].getContext('2d');
            const salesChart = initializeGraph(
                salesGraphCtx,
                {!! json_encode($medicineNames) !!},
                {!! json_encode($medicineSales) !!},
                'Sales',
                'rgba(54, 162, 235, 0.6)',
                'rgba(54, 162, 235, 1)'
            );

            // Initialize Stock Graph
            const stockGraphCtx = $('#stockGraph')[0].getContext('2d');
            initializeGraph(
                stockGraphCtx,
                {!! json_encode($medicineNames) !!},
                {!! json_encode($medicineStock) !!},
                'Stock',
                'rgba(255, 99, 132, 0.6)',
                'rgba(255, 99, 132, 1)'
            );

            // Filter Form Submission
            $('#filter-Form').on('submit', function(event) {
                event.preventDefault();

                var duration = $('select[name="filter"]').val();

                // Update loading state
                $('.total-sales')
                    .removeClass('text-success')
                    .addClass('text-muted')
                    .html(
                        '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>'
                    );

                $('#salesGraph').attr('hidden', true);
                $('.salesGraph')
                    .addClass('text-muted')
                    .append(
                        '<div class="spinner-border remove-spinner spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>'
                    );

                // Perform AJAX request
                $.ajax({
                    url: `/sales/filter/${duration}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            console.log(response);
                            // alert('Succeeded:  '+response.message);
                            $('.total-sales')
                                .removeClass('text-muted')
                                .addClass('text-success')
                                .text(new Intl.NumberFormat('en-TZ', {
                                    style: 'currency',
                                    currency: 'TZS'
                                }).format(response.filteredTotalSales));

                            $('.salesGraph').removeClass('text-muted').removeClass(
                                    'text-danger')
                                .addClass('text-success');
                            $('#salesGraph').removeAttr('hidden');
                            $('.remove-spinner').remove();

                            // Update the graph
                            salesChart.data.labels = response.medicineNames;
                            salesChart.data.datasets[0].data = response.medicineSales;
                            salesChart.update();
                        } else {
                            console.log(response);
                            $('.total-sales')
                                .removeClass('text-muted')
                                .addClass('text-danger')
                                .text(new Intl.NumberFormat('en-TZ', {
                                    style: 'currency',
                                    currency: 'TZS'
                                }).format(0));

                            $('.salesGraph').removeClass('text-muted').removeClass(
                                    'text-success')
                                .addClass('text-danger');
                            $('#salesGraph').removeAttr('hidden');
                            $('.remove-spinner').remove();

                            salesChart.data.labels = [];
                            salesChart.data.datasets[0].data = [];
                            salesChart.update();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr);
                        // alert('Errored: ' + xhr.statusText);
                        $('.total-sales')
                            .removeClass('text-success')
                            .addClass('text-danger')
                            .text(new Intl.NumberFormat('en-TZ', {
                                style: 'currency',
                                currency: 'TZS'
                            }).format(0));


                        $('.salesGraph').removeClass('text-muted').removeClass('text-danger')
                            .addClass('text-success');
                        $('#salesGraph').removeAttr('hidden');
                        $('.remove-spinner').remove();

                        // Update the graph
                        salesChart.data.labels = [];
                        salesChart.data.datasets[0].data = [];
                        salesChart.update();
                    }
                });
            });


            // Search Form Submission
            $('#medicine').on('input', function(event) {
                event.preventDefault();

                var searchValue = $('#medicine').val();
                var avaiable_medicine = $('#avaiable_medicine');
                var similar_medicine = $('#similar_medicine');
                var spinner =
                    '<div class="spinner-border spinner-border-sm" id="spinner" role="status"><span class="visually-hidden">Loading...</span></div>';
                var spinnerId = $('#spinner');

                // Update loading state
                avaiable_medicine.removeClass('text-success').addClass('text-muted').html(spinner);
                similar_medicine.removeClass('text-success').addClass('text-muted').html(spinner);

                // Perform AJAX request
                $.ajax({
                    url: '{{ route('medicines.search') }}',
                    method: 'GET',
                    data: {
                        search: searchValue
                    },
                    dataType: 'json',
                    success: function(response) {
                        spinnerId.remove();
                        avaiable_medicine.addClass('text-success').removeClass('text-muted')
                            .text(response.availableMedicine);
                        similar_medicine.addClass('text-success').removeClass('text-muted')
                            .text(response.similarMedicines.join(', '));
                    },
                    error: function(error) {
                        console.error('Error fetching sales data:', error);
                        spinnerId.remove();
                        avaiable_medicine.text('There is an Error!').removeClass('text-success')
                            .addClass('text-danger').html();
                        similar_medicine.text('There is an Error!').removeClass('text-success')
                            .addClass('text-danger').html();
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // raw data from Laravel
            const rawMedicines = @json($sellMedicines); // ensure this includes item relation

            // normalize and add compositeKey = "<medicine_id>_<selling_price>"
            const medicines = (rawMedicines || []).map(m => {
                const itemId = (m.item && m.item.id) ? m.item.id : (m.item_id || '');
                return Object.assign({}, m, {
                    compositeKey: itemId + '_' + m.selling_price
                });
            });

            // -------------------------
            // Reusable Functions
            // -------------------------
            function calculateAmount(row) {
                const price = parseFloat(row.querySelector('[name="total_price[]"]').value) || 0;
                const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 0;
                const amount = price * quantity;
                row.querySelector('.amount').value = amount;

                updateTotalAmount();
            }

            function tellPrice(row) {
                const selectedKey = row.querySelector('[name="item_id[]"]').value;
                if (!selectedKey) return;

                const selectedMedicine = medicines.find(m => m.compositeKey === selectedKey);
                if (!selectedMedicine) return;

                // set hidden stock_id[] to actual stock id (so server can use it)
                const stockInput = row.querySelector('[name="stock_id[]"]');
                if (stockInput) stockInput.value = selectedMedicine.id;

                // set price, set max quantity, update label
                const priceInput = row.querySelector('[name="total_price[]"]');
                if (priceInput) priceInput.value = selectedMedicine.selling_price;

                const qtyInput = row.querySelector('[name="quantity[]"]');
                if (qtyInput) qtyInput.setAttribute('max', selectedMedicine.remain_Quantity || 0);

                const labelElement = row.querySelector('.stock-label');
                if (labelElement) {
                    labelElement.innerHTML = '';
                    const appendedText = document.createElement('small');
                    appendedText.innerHTML = 'In stock (&darr;' + (selectedMedicine.remain_Quantity || 0) + ')';
                    appendedText.classList.add(
                        (selectedMedicine.remain_Quantity || 0) < (selectedMedicine.low_stock_percentage || 0) ?
                        'text-danger' :
                        'text-success'
                    );
                    labelElement.appendChild(appendedText);
                }
            }

            function updateTotalAmount() {
                let total = 0;
                document.querySelectorAll('.sale-entry').forEach(row => {
                    total += parseFloat(row.querySelector('.amount').value) || 0;
                });
                const totalEl = document.getElementById('totalAmount');
                if (totalEl) {
                    totalEl.value = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'TZS'
                    }).format(total);
                }
            }

            // -------------------------
            // Disable/Enable Selected Options (based on compositeKey)
            // -------------------------
            function updateMedicineOptions() {
                let selectedKeys = [];
                document.querySelectorAll('[name="item_id[]"]').forEach(select => {
                    if (select.value) selectedKeys.push(select.value); // these are composite keys now
                });

                document.querySelectorAll('[name="item_id[]"]').forEach(select => {
                    const currentValue = select.value;
                    $(select).find('option').each(function() {
                        // this.value is also a compositeKey
                        if (this.value && selectedKeys.includes(this.value) && this.value !==
                            currentValue) {
                            $(this).prop("disabled", true);
                        } else {
                            $(this).prop("disabled", false);
                        }
                    });

                    // refresh select2 display
                    try {
                        $(select).trigger('change.select2');
                    } catch (e) {
                        // ignore if select2 not present
                    }
                });
            }

            // -------------------------
            // Initialize Select2 for a row
            // -------------------------
            function attachSelect2(row) {
                // init select2 on selects inside this row
                const $sel = $(row).find('select.chosen');
                if ($sel.length) {
                    $sel.select2({
                        width: '100%',
                        minimumResultsForSearch: 5,
                        dropdownParent: $('#createSalesModal')
                    }).on('select2:select select2:unselect change', function() {
                        // when user selects or unselects, update price & options
                        tellPrice(row);
                        calculateAmount(row);
                        updateMedicineOptions();
                    });
                } else {
                    // fallback: plain select change
                    const sel = row.querySelector('select.chosen');
                    if (sel) {
                        sel.addEventListener('change', function() {
                            tellPrice(row);
                            calculateAmount(row);
                            updateMedicineOptions();
                        });
                    }
                }
            }

            // -------------------------
            // Add Row Functionality
            // -------------------------
            const addSaleBtn = document.getElementById('addSaleRow');
            if (addSaleBtn) {
                addSaleBtn.addEventListener('click', function() {
                    const salesFields = document.getElementById('salesFields');
                    const newRow = document.createElement('div');
                    newRow.classList.add('row', 'mb-3', 'sale-entry', 'align-items-center');

                    newRow.innerHTML = `
                <input type="text" name="stock_id[]" hidden required>
                <div class="col-md-4">
                    <select name="item_id[]" class="form-select chosen" required>
                        <option selected disabled value="">Select Item</option>
                        @foreach ($sellMedicines as $sellMedicine)
                            <option value="{{ $sellMedicine->item->id }}_{{ $sellMedicine->selling_price }}">
                                {{ $sellMedicine->item->name }} ({{ number_format($sellMedicine->selling_price) }}Tsh)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" value="0" name="total_price[]" readonly required>
                </div>
                <div class="col-md-2">
                    <label class="form-label stock-label">Quantity</label>
                    <input type="number" class="form-control" name="quantity[]" min="1" placeholder="Quantity" required>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control amount" name="amount[]" placeholder="Amount" readonly>
                </div>
                <div class="col-md-0" hidden>
                    <input type="text" class="form-control date" name="date[]" value="{{ now() }}" required>
                </div>
                <div class="col-md-1 d-flex justify-content-center">
                    <button type="button" class="btn btn-danger btn-sm remove-sale-row">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;

                    salesFields.appendChild(newRow);

                    attachSelect2(newRow);
                    updateMedicineOptions();

                    const qty = newRow.querySelector('[name="quantity[]"]');
                    if (qty) qty.addEventListener('input', function() {
                        calculateAmount(newRow);
                    });

                    const removeBtn = newRow.querySelector('.remove-sale-row');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function() {
                            newRow.remove();
                            updateTotalAmount();
                            updateMedicineOptions();
                        });
                    }
                });
            }

            // -------------------------
            // Initialize Existing Rows
            // -------------------------
            document.querySelectorAll('.sale-entry').forEach(row => {
                attachSelect2(row);

                // if row already has a selected item, populate its price & label
                const sel = row.querySelector('[name="item_id[]"]');
                if (sel && sel.value) {
                    tellPrice(row);
                    calculateAmount(row);
                }

                const qty = row.querySelector('[name="quantity[]"]');
                if (qty) qty.addEventListener('input', function() {
                    calculateAmount(row);
                });
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Function to attach calculation to inputs
            function attachCalculateTotal(modal) {
                const quantityInput = modal.querySelector('#floatingQuantity');
                const unitPriceInput = modal.querySelector('#floatingUnitPrice');
                const totalPriceInput = modal.querySelector('#floatingTotalPrice');

                function calculateTotal() {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const unitPrice = parseFloat(unitPriceInput.value) || 0;
                    const total = quantity * unitPrice;

                    totalPriceInput.value = total > 0 ?
                        new Intl.NumberFormat('en-TZ', {
                            style: 'currency',
                            currency: 'TZS',
                            minimumFractionDigits: 0
                        }).format(total) :
                        '';
                }

                quantityInput.addEventListener('input', calculateTotal);
                unitPriceInput.addEventListener('input', calculateTotal);
            }

            // Listen for modal show
            const salesNoteModal = document.getElementById('createSalesNoteModal');
            salesNoteModal.addEventListener('shown.bs.modal', function() {
                attachCalculateTotal(salesNoteModal);
            });
        });
    </script>

    <!-- jQuery Script -->
    <script>
        $(document).ready(function() {

            // Show password input when clicking the eye
            $("#eye-icon").click(function() {
                $("#unlock-section").hide();
                $("#password-section").show();
            });

            // Check password
            $("#check-password-btn").click(function() {
                // diable button to prevent multiple clicks
                $(this).prop('disabled', true);
                // display loading state
                $(this).html(
                    '<span class="spinner-border w-5 h-5 spinner-border-sm" role="status" aria-hidden="true"></span>'
                );


                let enteredPassword = $("#unlock-password").val();

                // send backend check request
                $.ajax({
                    url: '{{ route('checkPassword') }}',
                    method: 'POST',
                    data: {
                        password: enteredPassword,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#password-section").hide();
                            $(".myreportcheck").show();
                            $("#totalSalesCard").show();
                            setTimeout(function() {
                                $(".myreportcheck").hide();
                                $("#totalSalesCard").hide()
                                $("#unlock-section").show();
                                $("#unlock-password").val('');
                            }, 5000); // hide after 5 seconds
                            $("#unlock-password").val(response.password).focus();
                            // enable button and reset text
                            $("#check-password-btn").prop('disabled', false).html('Unlock');
                        } else {
                            // alert(response.message || "Incorrect password. Please try again.");
                            $("#unlock-password").val(response.password).focus();
                            $("#passwordCHechError").text(response.message ||
                                "Incorrect password. Please try again.");
                            setTimeout(function() {
                                $("#passwordCHechError").text('');
                            }, 5000); // clear error after 5 seconds
                            // enable button and reset text
                            $("#check-password-btn").prop('disabled', false).html('Unlock');
                        }
                    },
                    error: function() {
                        // alert("Error checking password. Please try again.");
                        $("#unlock-password").val('').focus();
                        $("#passwordCHechError").text(
                            "Error checking password. Please try again.");
                        setTimeout(function() {
                            $("#passwordCHechError").text('');
                        }, 5000); // clear error after 5 seconds
                        // enable button and reset text
                        $("#check-password-btn").prop('disabled', false).html('Unlock');
                    }
                });

            });
        });
    </script>
