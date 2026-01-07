<?php $__env->startSection("content"); ?>
    <?php
        use App\Models\Stock;
        use Illuminate\Support\Facades\DB;

        $medicines = Stock::select(
            DB::raw("CONCAT(item_id, '-', selling_price) as id"), // safe MySQL concat
            "item_id",
            DB::raw("SUM(remain_Quantity) as remain_Quantity"),
            "selling_price",
        )
            ->where("pharmacy_id", session("current_pharmacy_id"))
            ->where("expire_date", ">", now())
            ->where("remain_Quantity", ">", 0)
            ->groupBy("item_id", "selling_price")
            ->with("item")
            ->get();
    ?>

    <div class="mt-4# container">
        <!-- Header -->
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-primary fs-2 fw-bold">Sales Management</h2>
            <a class="btn btn-success" data-bs-target="#createSalesModal" data-bs-toggle="modal" href="#">
                <i class="bi bi-plus-lg"></i> New Sales
            </a>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            
            <div>
                <div class="form-control form-check form-switch mt-2">
                    <input <?php echo e(session("use_printer") ? "checked" : ""); ?> class="form-check-input" id="printerCheckbox"
                        type="checkbox">
                    <label class="form-check-label" for="printerCheckbox">Printer Enabled</label>
                </div>
            </div>
            <div>
                <a class="btn btn-success m-2" href="print/lastReceipt">
                    <i class="bi bi-printer"></i> Last
                </a>
                <a class="btn btn-success m-2" href="allReceipts">
                    <i class="bi bi-receipt"></i> Receipts
                </a>
            </div>
        </div>
        <hr class="mb-2">
        <!-- Sales Table -->
        <div class="table-responsive rounded-3 shadow-sm">
            <table class="table-striped small table-hover table-bordered table align-middle" id="salesTable">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Sales Name</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>

    <!-- Create Sales Modal -->
    <div aria-hidden="true" aria-labelledby="createSalesModalLabel" class="modal fade" id="createSalesModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createSalesModalLabel">Add New Sale</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route("sales.store")); ?>" id="salesForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <div id="salesFields">
                            <div class="row sale-entry align-items-center mb-3">
                                <input hidden name="stock_id[]" required type="text">
                                <div class="col-md-3">
                                    <label class="form-label" for="medicines">Medicine</label>
                                    <select class="salesChosen form-select" name="item_id[]" required>
                                        <option selected value="">Select medicine</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($medicine->id . "-" . $medicine->selling_price); ?>">
                                                <?php echo e($medicine->item->name); ?>

                                                <br><strong
                                                    class="text-danger">(<?php echo e(number_format($medicine->selling_price)); ?>Tsh)</strong>
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Price(TZS)</label>
                                    <input class="form-control" name="total_price[]" placeholder="Price" readonly required
                                        type="text" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="label[]">Quantity</label>
                                    <input class="form-control" min="1" name="quantity[]" placeholder="Quantity"
                                        required title="Only 10 has remained in stock!" type="number">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Amount</label>
                                    <input class="form-control amount" name="amount[]" placeholder="Amount" readonly
                                        type="number">
                                </div>
                                <div <?php echo e(Auth::user()->hasRole("Staff") ? "hidden" : ""); ?> class="col-md-2">
                                    <label class="form-label">Date</label>
                                    <input class="form-control date" name="date[]" required type="text"
                                        value="<?php echo e(now()); ?>">
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

    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(false): ?>
        <div aria-hidden="true" aria-labelledby="printerModalLabel" class="modal fade" id="printerModal"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="printerModalLabel">Select Printer</h5>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body text-center">
                        <form action="<?php echo e(route("printer.store")); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label" for="printer">Printer's Name</label>
                                <input class="form-control" id="printer" name="printer" placeholder="TM-20ll Receipt"
                                    required type="text">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="ip_address">IP Address</label>
                                <input class="form-control" id="ip_address" name="ip_address"
                                    placeholder="192.168.0.123" required type="text">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="computer_name">Computer's Name</label>
                                <input class="form-control" id="computer_name" name="computer_name"
                                    placeholder="DESKTOP-32" required type="text">
                            </div>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Chosen for dynamically added rows
            function initializeChosen() {
                $(document).ready(function() {
                    $(".salesChosen").each(function() {
                        let $select = $(this);
                        let $modal = $select.closest(".modal"); // Check if inside a modal

                        $select.select2({
                            width: "100%",
                            dropdownParent: $modal.length ? $modal : $(
                                "body") // Use modal if inside one
                        });

                        // Auto-focus the search input when dropdown opens
                        $select.on("select2:open", function() {
                            document.querySelector(
                                    ".select2-container--open .select2-search__field")
                                .focus();
                        });

                    }).on("select2:select select2:unselect", function() {
                        const row = $(this).closest(".sale-entry")[0];
                        tellPrice(row);
                        calculateAmount(row);
                    });
                });

            }

            // Reusable Functions
            function calculateAmount(row) {
                const price = parseFloat(row.querySelector('[name="total_price[]"]').value) || 0;
                const quantity = parseFloat(row.querySelector('[name="quantity[]"]').value) || 0;
                const amount = price * quantity;
                row.querySelector('.amount').value = amount;

                // Update total amount
                updateTotalAmount();
            }

            function tellPrice(row) {
                let medicines = <?php echo json_encode($medicines, 15, 512) ?>; // Convert medicines to a JS array
                const selectedMedicineId = row.querySelector('[name="item_id[]"]').value;

                const selectedMedicine = medicines.find(medicine => medicine.id + '-' + medicine.selling_price ==
                    selectedMedicineId);

                row.querySelector('[name="stock_id[]"]').value = `${selectedMedicine.id}`;
                // console.log(selectedMedicine.id);

                // return;
                if (selectedMedicine) {
                    // Set the total price to the medicine price (formatted with "TZS")
                    row.querySelector('[name="total_price[]"]').value = `${selectedMedicine.selling_price}`;
                    row.querySelector('[name="quantity[]"]').setAttribute('max',
                        `${selectedMedicine.remain_Quantity}`);

                    const labelElement = row.querySelector('[for="label[]"]');
                    // Clear any existing content in the labelElement
                    labelElement.innerHTML = '';
                    // Create a span element to hold the appended text
                    const appendedText = document.createElement('small');
                    // Set the text content and add the class
                    appendedText.innerHTML = 'In stock (&darr;' + selectedMedicine.remain_Quantity + ')';
                    if (selectedMedicine.remain_Quantity < selectedMedicine.low_stock_percentage) {
                        appendedText.classList.add('text-danger');
                    } else {
                        appendedText.classList.add('text-success');
                    }
                    // Append the span to the label element
                    labelElement.appendChild(appendedText);

                }
            }

            function updateTotalAmount() {
                let total = 0;
                document.querySelectorAll('.sale-entry').forEach(row => {
                    const amount = parseFloat(row.querySelector('.amount').value) || 0;
                    total += amount;
                });
                document.getElementById('totalAmount').value = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'TZS',
                }).format(total);
            }

            // function kuondoa medicines zilizochaguliwa kwenye rows nyingine
            // function updateMedicineOptions() {
            //     // pata all selected medicines
            //     let selectedMedicines = [];
            //     document.querySelectorAll('[name="item_id[]"]').forEach(select => {
            //         if (select.value) {
            //             selectedMedicines.push(select.value);
            //         }
            //     });

            //     // loop kupitia select zote na disable option zilizochaguliwa tayari
            //     document.querySelectorAll('[name="item_id[]"]').forEach(select => {
            //         let currentValue = select.value;
            //         select.querySelectorAll('option').forEach(option => {
            //             if (selectedMedicines.includes(option.value) && option.value !==
            //                 currentValue) {
            //                 // option.disabled = true;
            //                 // option.style.display = "none"; // 👈 ficha kabisa option
            //                 $(option).hide();   // ficha option kwenye select

            //             } else {
            //                 // option.disabled = false;
            //                 // option.style.display = ""; // rudisha ionekane
            //                 $(option).show();   // rudisha option ikionekane

            //             }
            //         });

            //         // refresh select2
            //         $(select).trigger('change.select2');
            //     });
            // }
            function updateMedicineOptions() {
                // pata all selected medicines
                let selectedMedicines = [];
                document.querySelectorAll('[name="item_id[]"]').forEach(select => {
                    if (select.value) {
                        selectedMedicines.push(select.value);
                    }
                });

                // loop kupitia select zote na ficha/onyesha medicines
                document.querySelectorAll('[name="item_id[]"]').forEach(select => {
                    let currentValue = select.value;

                    $(select).find('option').each(function() {
                        if (selectedMedicines.includes(this.value) && this.value !== currentValue) {
                            $(this).attr("disabled", true); // disable option
                        } else {
                            $(this).attr("disabled", false); // re-enable option
                        }
                    });

                    // force Select2 to redraw
                    // $(select).select2();
                    $(select).select2({
                        dropdownParent: $(
                            '#saleModal') // 👈 hii ndiyo inazuia kuchoropoka nyuma ya modal
                    });

                });
            }

            // Add Row Functionality
            document.getElementById('addSaleRow').addEventListener('click', function() {
                const salesFields = document.getElementById('salesFields');
                const newRow = document.createElement('div');
                newRow.classList.add('row', 'mb-3', 'sale-entry', 'align-items-center');

                newRow.innerHTML = `
                            <input type="text" name="stock_id[]" hidden required>
                            <div class="col-md-3">
                                <select name="item_id[]" data-row-id="item_id[]" class="form-select salesChosen" required>
                                    <option selected disabled value="">Select medicine</option>
                                    <?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($medicine->id); ?>-<?php echo e($medicine->selling_price); ?>">
                                                <?php echo e($medicine->item->name); ?> <br><strong
                                                class="text-danger">(<?php echo e(number_format($medicine->selling_price)); ?>Tsh)</strong>
                                            </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" VALUE="0" name="total_price[]" readonly required>
                            </div>
                            <div class="col-md-2">
                                    <label class="form-label" for="label[]"></label>
                                <input type="number" class="form-control" name="quantity[]" min="1" placeholder="Quantity" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control amount" name="amount[]" placeholder="Amount" readonly>
                            </div>
                            <div class="col-md-2" <?php echo e(Auth::user()->hasRole("Staff") ? "hidden" : ""); ?>>
                            <input type="text" class="form-control date" name="date[]" value="<?php echo e(now()); ?>" required>
                            </div>
                            <div class="col-md-1 d-flex justify-content-center">
                                <button type="button" class="btn btn-danger btn-sm remove-sale-row">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                `;


                salesFields.appendChild(newRow);

                // Initialize Select2 kwa row mpya, hii ndiyo inazuia dropdown kuonekana nyuma ya modal
                $(newRow).find('select[name="item_id[]"]').select2({
                    dropdownParent: $('#saleModal')
                });


                // Initialize Chosen for the new row
                initializeChosen();
                updateMedicineOptions(); // <-- ongeza hii

                // Add Event Listeners for the new row
                newRow.querySelector('.remove-sale-row').addEventListener('click', function() {
                    newRow.remove();
                    updateTotalAmount();
                    updateMedicineOptions(); // rudisha options zilizokuwa zimefichwa
                });

                newRow.querySelector('[name="quantity[]"]').addEventListener('input', function() {
                    calculateAmount(newRow);
                });

                newRow.querySelector('.remove-sale-row').addEventListener('click', function() {
                    newRow.remove();
                    updateTotalAmount();
                    updateMedicineOptions();
                });


                updateMedicineOptions(); // apply restrictions immediately
            });

            // Initial Setup for Existing Rows
            document.querySelectorAll('.sale-entry').forEach(row => {
                row.querySelector('[name="item_id[]"]').addEventListener('change', function() {
                    tellPrice(row);
                    calculateAmount(row);
                    updateMedicineOptions(); // <-- ongeza hii
                });

                row.querySelector('[name="quantity[]"]').addEventListener('input', function() {
                    calculateAmount(row);
                });
            });

            // Initialize Chosen on Page Load
            initializeChosen();

            // function to enable or disable use of printer
            var printerCheckbox = $('#printerCheckbox');
            printerCheckbox.on('change', function() {
                if (printerCheckbox.is(':checked')) {
                    new_status = 1;
                    current_status = 0;
                } else {
                    new_status = 0;
                    current_status = 1;
                }

                // Update printer enable status in the db using jquery ajax
                $.ajax({
                    type: "POST",
                    url: "<?php echo e(route("printer.updateStatus")); ?>",
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
                        current_status: current_status,
                        new_status: new_status,
                    },
                    success: function(response) {
                        // Handle the response from the server
                        console.log(response);
                        if (response.status == "success") {
                            if (response.message == "no configurations") {

                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        error(error);

                    }
                });

            });

        });

        // Datatables  for sales table
        $(document).ready(function() {
            $('#salesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route("sales")); ?>",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'sales_name',
                        name: 'item.name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'total_price',
                        name: 'total_price'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("sales.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/sales/index.blade.php ENDPATH**/ ?>