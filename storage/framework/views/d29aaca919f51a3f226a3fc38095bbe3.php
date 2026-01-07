<?php $__env->startSection('content'); ?>
    <?php $medicines = App\Models\Items::where('pharmacy_id', session('current_pharmacy_id'))->with('lastStock')->get(); ?>

    <div class="container mt-4">
        
        <div class="d-flex justify-content-between mb-3">
            <h1 class="text-primary fw-bold fs-3">Stock <i
                    class="bi <?php echo e(Auth::user()->hasRole('Owner') ? '' : 'hidden'); ?> text-secondary bi-eye"
                    id="togglePrivateData" style="cursor: pointer;"></i></h1>
            <div>
                <a class="btn btn-success" data-bs-target="#createStockModal" data-bs-toggle="modal" href="#">Add New
                    Stock</a>
                <a class="btn btn-success" data-bs-target="#createMedicineStockModal" data-bs-toggle="modal"
                    href="#">Medicine + Stock</a>
                <a class="btn btn-danger" data-bs-target="#importMedicineStockModal" data-bs-toggle="modal"
                    href="#">Import CSV</a>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'Owner')): ?>
            <div class="privateData" hidden>
                
                <div class="d-flex justify-content-between gap-2">
                    <p class="text-secondary"><strong class="fw-bold">Total Stock Value: </strong>
                        <?php echo e(number_format($availableStock)); ?> <?php echo e(session('currency') ?? 'TZS'); ?></p>
                    <p class="text-secondary"><strong class="fw-bold">Expected Total Sales: </strong>
                        <?php echo e(number_format($expectedSales)); ?> <?php echo e(session('currency') ?? 'TZS'); ?></p>
                    <p class="text-secondary"><strong class="fw-bold">Expected Total Profit: </strong>
                        <?php echo e(number_format($expectedProfit)); ?> <?php echo e(session('currency') ?? 'TZS'); ?></p>
                </div>
                <hr><br>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div aria-hidden="true" aria-labelledby="passwordModalLabel" class="modal fade" id="passwordModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="passwordModalLabel">Confirm Identity</h5>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <p>Please enter your password to view sensitive data.</p>
                        <form id="reAuthForm">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label" for="passwordInput">Password</label>
                                <input class="form-control" id="passwordInput" name="password" required type="password">
                                <div class="text-danger mt-1" id="passwordError"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        <button class="btn btn-primary" id="verifyPasswordBtn" type="submit">Verify</button>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="table-responsive">
            <table class="table-striped table-bordered table-hover small table" id="tableOfStocks">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Batch Number</th>
                        <th>Supplier</th>
                        <th>Medicine Name</th>
                        <th>Buying Price</th>
                        <th>Selling Price</th>
                        <th>Remain Quantity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>

    
    <div aria-hidden="true" aria-labelledby="createStockModalLabel" class="modal fade" id="createStockModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="createStockModalLabel">Add New Stock</h5>
                    <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        type="button"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('stock.store')); ?>" id="stockForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row mb-2">
                            <div class="col-12# col-sm-6# col-md-6 col-lg-2#">
                                <label class="form-label fw-bold">Stock batch Number</label>
                                <input class="form-control rounded shadow-sm" id="batch_number" name="batch_number"
                                    placeholder="1234" readonly type="text">
                            </div>
                            <div class="col-12# col-sm-6# col-md-6 col-lg-2#">
                                <label class="form-label fw-bold">Supplier Name</label>
                                <input class="form-control rounded shadow-sm" name="supplier" placeholder="ABC Supplier"
                                    required type="text">
                            </div>
                        </div>
                        <hr class="m-2">
                        <div id="stockFields">
                            <div class="row stock-entry align-items-end gx-2 gy-2 mb-3">
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold" for="item_id">Medicine Name</label>
                                    <select class="medicineSelect chosen form-select shadow-sm" name="item_id[]" required>
                                        <option selected value="">Select medicine...</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($medicine->id); ?>"><?php echo e($medicine->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Buying Price</label>
                                    <input class="form-control shadow-sm" min="1" name="buying_price[]" required
                                        type="number">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Selling Price</label>
                                    <input class="form-control shadow-sm" min="1" name="selling_price[]" required
                                        type="number">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Quantity</label>
                                    <input class="form-control shadow-sm" min="1" name="quantity[]" required
                                        type="number">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Low stock</label>
                                    <input class="form-control shadow-sm" min="1" name="low_stock_percentage[]"
                                        required type="number">
                                </div>
                                <div class="col-0 col-sm-0 col-md-0 col-lg-0" hidden>
                                    <label class="form-label fw-bold">In Date</label>
                                    <input class="form-control shadow-sm" name="in_date[]" required type="text"
                                        value="<?php echo e(now()); ?>">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Expire Date</label>
                                    <input class="form-control shadow-sm" name="expire_date[]" required type="date">
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="col-md-10 d-flex justify-content-between">
                                <button class="btn btn-outline-primary" id="addStockBtn" type="button">
                                    <i class="bi bi-plus-lg"></i> Add Row
                                </button>
                                <button class="btn btn-success" type="submit">
                                    <i class="bi bi-save"></i> Save
                                </button>
                            </div>
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
            <div class="modal-content">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="createMedicineStockModalLabel">Add New Stock and Medicine</h5>
                    <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        type="button"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('medicineStock.store')); ?>" id="stockForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row mb-2">
                            <div class="col-12# col-sm-6# col-md-6 col-lg-2#">
                                <label class="form-label fw-bold">Stock batch Number</label>
                                <input class="form-control rounded shadow-sm" id="batch_number_" name="batch_number"
                                    placeholder="1234" readonly type="text">
                            </div>
                            <div class="col-12# col-sm-6# col-md-6 col-lg-2#">
                                <label class="form-label fw-bold">Supplier Name</label>
                                <input class="form-control rounded shadow-sm" name="supplier" placeholder="ABC Supplier"
                                    required type="text">
                            </div>
                        </div>
                        <hr class="m-2">
                        <div id="medicineStockFields">
                            <div class="row stock-entry align-items-end gx-2 gy-2 mb-3">
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold" for="item_id">New Medicine Name</label>
                                    <input class="form-control shadow-sm" name="item_name[]" required type="text">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Buying Price</label>
                                    <input class="form-control shadow-sm" name="buying_price[]" required type="number">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Selling Price</label>
                                    <input class="form-control shadow-sm" name="selling_price[]" required type="number">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Quantity</label>
                                    <input class="form-control shadow-sm" name="quantity[]" required type="number">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Low stock</label>
                                    <input class="form-control shadow-sm" min="1" name="low_stock_percentage[]"
                                        required type="number">
                                </div>
                                <div class="col-0 col-sm-0 col-md-0 col-lg-0" hidden>
                                    <label class="form-label fw-bold">In Date</label>
                                    <input class="form-control shadow-sm" name="in_date[]" required type="text"
                                        value="<?php echo e(now()); ?>">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                                    <label class="form-label fw-bold">Expire Date</label>
                                    <input class="form-control shadow-sm" name="expire_date[]" required type="date">
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="col-md-10 d-flex justify-content-between">
                                <button class="btn btn-outline-primary" id="addMedicineStockBtn" type="button">
                                    <i class="bi bi-plus-lg"></i> Add Row
                                </button>
                                <button class="btn btn-success" type="submit">
                                    <i class="bi bi-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    
    <div aria-hidden="true" aria-labelledby="importMedicineStockModalLabel" class="modal fade"
        id="importMedicineStockModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="importMedicineStockModalLabel">Import Medicines and Stock</h5>
                    <button aria-label="Close" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        type="button"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="<?php echo e(route('importMedicineStock')); ?>" enctype="multipart/form-data" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            
                            <label class="form-label fw-bold" for="file">Select CSV File, (Colums:<small
                                    class="smallest text-danger"> item_name, buying_price, selling_price, quantity,
                                    low_stock_percentage, expire_date, supplier</small>) </label>
                            <input accept=".csv" class="form-control" name="file" required type="file">
                        </div>
                        <div class="col-12# col-sm-6# col-md-6 col-lg-2#" hidden>
                            <label class="form-label fw-bold">Stock batch Number</label>
                            <input class="form-control rounded shadow-sm" id="batch_number__" name="batch_number__"
                                placeholder="1234" readonly required type="text">
                        </div>
                        <div class="col-0 col-sm-0 col-md-0 col-lg-0" hidden>
                            <label class="form-label fw-bold">In Date</label>
                            <input class="form-control shadow-sm" name="in_date" readonly required type="text"
                                value="<?php echo e(now()); ?>">
                        </div>
                        <button class="btn btn-success" type="submit">
                            <i class="bi bi-upload"></i> Import
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // STOCKS ONLY
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Chosen for dynamically added rows
            function initializeChosen() {
                $(".chosen").each(function() {
                    let $select = $(this);
                    let $modal = $select.closest(
                        ".modal"); // Check if inside a modal
                    $select.select2({
                        width: "100%",
                        dropdownParent: $modal.length ? $modal : $(
                            "body") // Use modal if inside one
                    });

                    // Auto-focus the search input when dropdown opens
                    $select.on("select2:open", function() {
                        document.querySelector(".select2-container--open .select2-search__field")
                            .focus();
                    });

                }).on("change", function() {
                    const row = $(this).closest(".stock-entry")[0];
                    setStockFieldsData(row);
                });
            }

            // function to pass the buying_price, selling_price, and low_stock_percentage quantity to the input fields
            function setStockFieldsData(row) {
                const medicines = <?php echo json_encode($medicines, 15, 512) ?>;
                const selectedMedicineId = row.querySelector('[name="item_id[]"]').value;
                // Find the selected medicine
                const selectedMedicine = medicines.find(medicine => medicine.id == selectedMedicineId);
                if (selectedMedicine.last_stock) {
                    // set the buying_price, selling_price, and low_stock_percentage field's value
                    row.querySelector('[name="buying_price[]"]').value = selectedMedicine.last_stock.buying_price;
                    row.querySelector('[name="selling_price[]"]').value = selectedMedicine.last_stock.selling_price;
                    row.querySelector('[name="low_stock_percentage[]"]').value = selectedMedicine.last_stock
                        .low_stock_percentage;
                } else {
                    // clear the buying_price, selling_price, and low_stock_percentage field's value
                    row.querySelector('[name="buying_price[]"]').value = '';
                    row.querySelector('[name="selling_price[]"]').value = '';
                    row.querySelector('[name="low_stock_percentage[]"]').value = '';
                }
            }

            initializeChosen();

            // listen for changes in the .medicineSelect field to call for a function to set the stock fields data
            document.querySelectorAll('.stock-entry').forEach(row => {
                let itemSelect = row.querySelector('[name="item_id[]"]');
                if (itemSelect) {
                    itemSelect.addEventListener('change', function() {
                        setStockFieldsData(row);
                    });
                }
            });


            // Add a new stock entry when the button is clicked
            document.getElementById('addStockBtn').addEventListener('click', function() {

                const stockFields = document.getElementById('stockFields');

                const newStockEntry = document.createElement('div');
                newStockEntry.classList.add('row', 'mb-3', 'stock-entry', 'align-items-end', 'gx-2',
                    'gy-2');
                newStockEntry.innerHTML = `
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="form-label fw-bold">Medicine Name</label>
                    <select name="item_id[]" class="form-select medicineSelect  shadow-sm chosen" required>
                        <option selected value="">Select medicine...</option>
                        <?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($medicine->id); ?>"><?php echo e($medicine->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="form-label fw-bold">Buying Price</label>
                    <input type="number" min="1" class="form-control  shadow-sm" name="buying_price[]" required>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="form-label fw-bold">Selling Price</label>
                    <input type="number" min="1" class="form-control  shadow-sm" name="selling_price[]" required>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="form-label fw-bold">Quantity</label>
                    <input type="number" min="1" class="form-control  shadow-sm" name="quantity[]" required>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="form-label fw-bold">Low stock</label>
                    <input type="number" min="1" class="form-control  shadow-sm" name="low_stock_percentage[]" min="1" required>
                </div>
                <div class="col-0 col-sm-0 col-md-0 col-lg-0" hidden>
                    <label class="form-label fw-bold">In Date</label>
                <input type="text" class="form-control  shadow-sm" name="in_date[]" value="<?php echo e(now()); ?>"
                required>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                    <label class="form-label fw-bold">Expire Date</label>
                    <input type="date" class="form-control  shadow-sm" name="expire_date[]" required>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                    <button type="button" class="btn btn-danger btn-sm  shadow-sm remove-stock-entry">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                `;


                stockFields.appendChild(newStockEntry);

                initializeChosen();

                newStockEntry.querySelector('.remove-stock-entry').addEventListener('click', function() {
                    newStockEntry.remove();
                });

                // Add event listener to the new stock entry
                newStockEntry.querySelector('[name="item_id[]"]').addEventListener('change', function() {
                    setStockFieldsData(newStockEntry);
                });
            });
        });

        //MEDICINES + STOCKS
        document.getElementById('addMedicineStockBtn').addEventListener('click', function() {
            const medicineStockFields = document.getElementById('medicineStockFields');

            const newStockEntry = document.createElement('div');
            newStockEntry.classList.add('row', 'mb-3', 'stock-entry', 'align-items-end', 'gx-2',
                'gy-2');
            newStockEntry.innerHTML = `
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <label class="form-label fw-bold">Medicine Name</label>
                            <input type="text" class="form-control shadow-sm" name="item_name[]" required>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <label class="form-label fw-bold">Buying Price</label>
                            <input type="number" class="form-control  shadow-sm" name="buying_price[]" required>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <label class="form-label fw-bold">Selling Price</label>
                            <input type="number" class="form-control  shadow-sm" name="selling_price[]" required>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <label class="form-label fw-bold">Quantity</label>
                            <input type="number" class="form-control  shadow-sm" name="quantity[]" required>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <label class="form-label fw-bold">Low stock</label>
                            <input type="number" class="form-control  shadow-sm" name="low_stock_percentage[]" min="1" required>
                        </div>
                        <div class="col-0 col-sm-0 col-md-0 col-lg-0" hidden>
                            <label class="form-label fw-bold">In Date</label>
                        <input type="text" class="form-control  shadow-sm" name="in_date[]" value="<?php echo e(now()); ?>"
                        required>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                            <label class="form-label fw-bold">Expire Date</label>
                            <input type="date" class="form-control  shadow-sm" name="expire_date[]" required>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-2 d-flex justify-content-center">
                            <button type="button" class="btn btn-danger btn-sm  shadow-sm remove-stock-entry">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                `;

            medicineStockFields.appendChild(newStockEntry);

            $(".chosen").each(function() {
                let $select = $(this);
                let $modal = $select.closest(
                    ".modal"); // Check if inside a modal
                $select.select2({
                    width: "100%",
                    no_results_text: "No matches found!",
                    allowClear: true,
                    dropdownParent: $modal.length ? $modal : $(
                        "body") // Use modal if inside one
                });
            });

            newStockEntry.querySelector('.remove-stock-entry').addEventListener('click', function() {
                newStockEntry.remove();
            });
        });

        $(document).ready(function() {
            const today = new Date();
            const year = today.getFullYear(); // Get the full year
            const month = String(today.getMonth() + 1).padStart(2,
                '0'); // Months are zero-based, pad with leading zero if needed
            const day = String(today.getDate()).padStart(2, '0'); // Pad day with leading zero if needed
            const formattedDate = `${year}${month}${day}`; // Combine to form YYYYMMDD format
            $('#batch_number').val(formattedDate); // Use .val() to set the value of the input
            $('#batch_number_').val(formattedDate); // Use .val() to set the value of the input
            $('#batch_number__').val(formattedDate); // Use .val() to set the value of the input
        });

        $(document).ready(function() {
            $('#tableOfStocks').DataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route('stock')); ?>", // Laravel route
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'batch_number',
                        name: 'batch_number'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'medicine_name',
                        name: 'medicine_name'
                    },
                    {
                        data: 'buying_price',
                        name: 'buying_price'
                    },
                    {
                        data: 'selling_price',
                        name: 'selling_price'
                    },
                    {
                        data: 'remain_Quantity',
                        name: 'remain_Quantity'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'asc']
                ] // Default sorting by Batch Number
            });
        });

        $(document).ready(function() {
            function attachPriceValidation(modalSelector) {
                const $modal = $(modalSelector);
                const $form = $modal.find('form').first();
                const $saveBtn = $form.find('[type="submit"]').first();

                // Ondoa utendaji wa awali wa 'submit' ili kuzuia kukagua tena
                $form.off('submit.priceValidation');

                // Tumia 'input change' ili kukagua wakati mtumiaji anapoandika
                $form.on('input change', '[name="buying_price[]"], [name="selling_price[]"]', function() {
                    const $row = $(this).closest('.stock-entry');
                    const $bpInput = $row.find('[name="buying_price[]"]');
                    const $spInput = $row.find('[name="selling_price[]"]');

                    const bpVal = ($bpInput.val() || '').toString().replace(/,/g, '').trim();
                    const spVal = ($spInput.val() || '').toString().replace(/,/g, '').trim();

                    const bp = bpVal === '' ? NaN : parseFloat(bpVal);
                    const sp = spVal === '' ? NaN : parseFloat(spVal);

                    // Ondoa makosa ya awali kabla ya kukagua tena
                    $row.find('.field-error').remove();
                    $bpInput.removeClass('is-invalid');
                    $spInput.removeClass('is-invalid');
                    $modal.find('.priceValidationError').remove();

                    // Kagua ikiwa selling price ni ndogo kuliko buying price
                    if (!isNaN(bp) && !isNaN(sp) && sp < bp) {
                        // Ongeza "is-invalid" class ili kuonyesha kosa
                        $bpInput.addClass('is-invalid');
                        $spInput.addClass('is-invalid');

                        // Ongeza ujumbe wa kosa
                        if ($row.find('.field-error').length === 0) {
                            $row.append(
                                '<div class="field-error text-danger fw-bold small mt-1"> The selling price must be greater than or equal to the buying price.</div>'
                            );
                        }
                    }

                    // Kagua fomu nzima ili kuamua hali ya kitufe cha 'submit'
                    let hasErrors = false;
                    $form.find('.stock-entry').each(function() {
                        const rowBpVal = ($(this).find('[name="buying_price[]"]').val() || '')
                            .toString().replace(/,/g, '').trim();
                        const rowSpVal = ($(this).find('[name="selling_price[]"]').val() || '')
                            .toString().replace(/,/g, '').trim();
                        const rowBp = rowBpVal === '' ? NaN : parseFloat(rowBpVal);
                        const rowSp = rowSpVal === '' ? NaN : parseFloat(rowSpVal);
                        if (!isNaN(rowBp) && !isNaN(rowSp) && rowSp < rowBp) {
                            hasErrors = true;
                        }
                    });

                    // Zimisha au washa kitufe cha 'submit' kulingana na matokeo
                    if ($saveBtn && $saveBtn.length) {
                        $saveBtn.prop('disabled', hasErrors);
                    }

                    // Ongeza ujumbe mkuu wa kosa juu ya fomu ikiwa kuna makosa
                    if (hasErrors) {
                        const alertHtml =
                            '<div class="alert alert-danger priceValidationError">Please correct the lines that have been marked with errors. For you to save and continue, <span class ="text-danger fw-bold">the selling price must be greater than or equal to the buying price.</span></div>';
                        $form.prepend(alertHtml);
                    }
                });

                // Hii inaruhusu fomu kuwasilishwa ikiwa hakuna makosa
                $form.on('submit', function(e) {
                    let hasErrors = false;
                    $form.find('.stock-entry').each(function() {
                        const rowBpVal = ($(this).find('[name="buying_price[]"]').val() || '')
                            .toString().replace(/,/g, '').trim();
                        const rowSpVal = ($(this).find('[name="selling_price[]"]').val() || '')
                            .toString().replace(/,/g, '').trim();
                        const rowBp = rowBpVal === '' ? NaN : parseFloat(rowBpVal);
                        const rowSp = rowSpVal === '' ? NaN : parseFloat(rowSpVal);
                        if (!isNaN(rowBp) && !isNaN(rowSp) && rowSp < rowBp) {
                            hasErrors = true;
                        }
                    });

                    if (hasErrors) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        e.stopPropagation();
                    } else {
                        if ($saveBtn && $saveBtn.length) {
                            $saveBtn.prop('disabled', true);
                        }
                    }
                });

            }

            attachPriceValidation('#createStockModal');
            attachPriceValidation('#createMedicineStockModal');
        });

        $(document).ready(function() {
            const $toggleIcon = $('#togglePrivateData');
            const $privateData = $('.privateData');
            const $passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
            const $verifyBtn = $('#verifyPasswordBtn');
            const $passwordInput = $('#passwordInput');
            const $passwordError = $('#passwordError');

            // 1. Initial Click Handler: Show Modal instead of toggling
            $toggleIcon.on('click', function() {
                // If data is currently visible, hide it instantly
                if ($privateData.is(':visible')) {
                    $privateData.attr('hidden', true);
                    $toggleIcon.removeClass('bi-eye-slash text-danger').addClass('bi-eye text-secondary');
                } else {
                    // If data is hidden, prompt for password
                    $passwordInput.val(''); // Clear previous input
                    $passwordError.text(''); // Clear previous error
                    $passwordModal.show();
                }
            });

            // 2. Verification Button Handler
            $verifyBtn.on('click', function(e) {
                e.preventDefault();

                // Simple client-side check
                if ($passwordInput.val().length === 0) {
                    $passwordError.text('Password is required.');
                    return;
                }

                // Disable button during request
                $verifyBtn.prop('disabled', true).text('Verifying...');

                $.ajax({
                    url: '<?php echo e(route('checkPassword')); ?>', // Use the Laravel route name
                    type: 'POST',
                    data: $('#reAuthForm').serialize(),
                    success: function(response) {
                        if (response.success) {
                            // Password correct: Show data, hide modal, change icon
                            $passwordModal.hide();
                            $privateData.removeAttr('hidden');
                            $toggleIcon.removeClass('bi-eye text-secondary').addClass(
                                'bi-eye-slash text-danger');
                            // hide after 5 minutes
                            setTimeout(function() {
                                $privateData.attr('hidden', true);
                                $toggleIcon.removeClass('bi-eye-slash text-danger')
                                    .addClass('bi-eye text-secondary');
                            }, 300000);
                        } else {
                            $passwordError.text(response.message ||
                                'Verification failed. Please check your password.');
                        }
                    },
                    error: function(xhr) {
                        // Password incorrect
                        const response = xhr.responseJSON;
                        const message = response.message ||
                            'Verification failed. Please check your password.';
                        $passwordError.text(message);
                    },
                    complete: function() {
                        // Re-enable button
                        $verifyBtn.prop('disabled', false).text('Verify');
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('stock.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/stock/index.blade.php ENDPATH**/ ?>