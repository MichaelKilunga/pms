<?php $__env->startSection("content"); ?>
    <div class="container">
        <div class="d-flex justify-content-between mb-3 mt-2">
            <h4 class="text-primary fs-2 fw-bold">Stock Transfer</h4>
            <div>
                <!-- Trigger Button -->
                <a class="btn btn-success" data-bs-target="#addStockTransfersModal" data-bs-toggle="modal" href="#">
                    Transfer Stock Now!
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table-bordered table-striped small table" id="stockTransfer">
                <thead class="table-primary">
                    <tr>
                        <th>SN</th>
                        <th>Medicine</th>
                        <th>Quantity</th>
                        <th>To Pharmacy</th>
                        <th>TIN Number</th>
                        <th>Notes</th>
                        <th>Transfer Date</th>
                        <th>Transferred By</th>
                        <th>Posted On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($transfer->stock->item->name); ?></td>
                            <td><?php echo e($transfer->quantity); ?></td>
                            <td>
                                <?php echo e($transfer->to_pharmacy_id ? $transfer->toPharmacy->name : $transfer->to_pharmacy_name); ?>

                            </td>
                            <td><?php echo e($transfer->to_pharmacy_tin); ?></td>
                            <td><?php echo e($transfer->notes); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($transfer->transfer_date)->format("Y-m-d")); ?></td>
                            <td><?php echo e($transfer->transferredBy->name); ?></td>
                            <td><?php echo e($transfer->created_at); ?></td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transfer->status == "completed"): ?>
                                    <span class="text-success">Completed</span>
                                <?php else: ?>
                                    <span class="text-danger">Pending</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transfer->status == "completed"): ?>
                                    <div class="d-flex">
                                        <form action="<?php echo e(route("stockTransfers.destroy", $transfer->id)); ?>" class="me-2"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field("DELETE"); ?>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to restore this stock transfer, you will not be able to see your transfer and transfered stock will return to original stock ?')"
                                                type="submit"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        </form>
                                        <form action="<?php echo e(route("stockTransfers.confirm", $transfer->id)); ?>" method="GET">
                                            <?php echo csrf_field(); ?>
                                            <button class="btn btn-primary btn-sm" disabled
                                                onclick="return confirm('Are you sure you want to confirm this stock transfer ?')"
                                                type="submit"><i class="bi bi-check-circle"></i></button>
                                        </form>
                                        <form action="<?php echo e(route("stockTransfers.print", $transfer->id)); ?>" method="GET">
                                            <?php echo csrf_field(); ?>
                                            <button class="btn btn btn-outline-dark btn-sm mx-2"
                                                onclick="return confirm('Are you sure you want to print this stock transfer ?')"
                                                type="submit"><i class="bi bi-printer"></i></button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex">
                                        <form action="<?php echo e(route("stockTransfers.destroy", $transfer->id)); ?>" class="me-2"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field("DELETE"); ?>
                                            <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to restore this stock transfer, you will not be able to see your transfer and transfered stock will return to original stock ?')"
                                                type="submit"><i class="bi bi-arrow-counterclockwise"></i></button>
                                        </form>
                                        <form action="<?php echo e(route("stockTransfers.confirm", $transfer->id)); ?>" method="GET">
                                            <?php echo csrf_field(); ?>
                                            <button class="btn btn-primary btn-sm"
                                                onclick="return confirm('Are you sure you want to confirm this stock transfer ?')"
                                                type="submit"><i class="bi bi-check-circle"></i></button>
                                        </form>
                                        <form action="<?php echo e(route("stockTransfers.print", $transfer->id)); ?>" method="GET">
                                            <?php echo csrf_field(); ?>
                                            <button class="btn btn btn-outline-dark btn-sm mx-2"
                                                onclick="return confirm('Confirm first to be able to print.') && false"
                                                type="submit"><i class="bi bi-printer"></i></button>
                                        </form>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <?php echo e($transfers->links()); ?>

        </div>
    </div>

    <!-- Modal -->
    <div aria-hidden="true" aria-labelledby="addStockTransfersModalLabel" class="modal fade" id="addStockTransfersModal"
        tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 overflow-hidden border-0 shadow-lg">
                <div class="row g-0" bg-white ">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title fw-bold text-white" id="addStockTransfersModalLabel">Transfer Stock</h5>
                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo e(route("stockTransfers.store")); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <!-- To Pharmacy -->
                                    <div class="mb-3">
                                        <label class="form-label">To Pharmacy (in system)</label>
                                        <select class="form-select" name="to_pharmacy_id" required>
                                            <option selected value="">-- Select an Option --</option>
                                            <option value="0">Pharmacy is Not in the system </option>
                                             <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pharmacies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pharmacy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($pharmacy->id); ?>"><?php echo e($pharmacy->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <!-- External Pharmacy Name field to be shown if option selected has value="0" -->
                <div class="external-pharmacy-name mb-3 hidden">
                    <label class="form-label" for="to_pharmacy_name">External Pharmacy Name</label>
                    <input class="form-control" name="to_pharmacy_name" placeholder="Only if not in system" type="text">
                </div>
                <!-- TIN Number -->
                <div class="mb-3">
                    <label class="form-label" for="tin_number">TIN Number</label>
                    <input class="form-control" name="tin_number" placeholder="Only if external" type="text">
                </div>

                <!-- Medicine Selection -->
                <div id="medicine-group-container">
                    <div class="medicine-group mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label" for="stock_id">Select Medicine</label>
                                <select class="select2 form-select" name="stock_id[]" required>
                                    <option value="">-- Select --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option data-remain-quantity="<?php echo e($stock->remain_Quantity); ?>"
                                            value="<?php echo e($stock->id); ?>">
                                            <?php echo e($stock->item->name); ?> (Remaining:
                                            <?php echo e($stock->remain_Quantity); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="quantity">Quantity to Transfer</label>
                                <input class="form-control" min="1" name="quantity[]" required type="number">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-danger remove-medicine-group" type="button">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success mb-3" id="add-medicine-group" type="button">Add Another
                    Medicine</button>

                <!-- Transfer Date -->
                <div class="mb-3 hidden">
                    <label class="form-label" for="transfer_date">Transfer Date</label>
                    <input class="form-control" name="transfer_date" readonly required type="date"
                        value="<?php echo e(\Carbon\Carbon::now()->format("Y-m-d")); ?>">

                </div>

                <!-- Notes -->
                <div class="mb-3">
                    <label class="form-label" for="notes">Notes</label>
                    <textarea class="form-control" name="notes" rows="2"></textarea>
                </div>
                <!-- Submit Button -->
                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">Transfer</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#stockTransfer').DataTable({
                paging: true,
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    zeroRecords: "No records found",
                    info: "Showing page _PAGE_ of _PAGES_",
                    infoEmpty: "No records available",
                    infoFiltered: "(filtered from _MAX_ total records)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    },
                    search: "Search:",
                }
            });

            // External Pharmacy Name field to be shown if option selected has value="0" 
            $('select[name="to_pharmacy_id"]').change(function() {
                if ($(this).val() == '0') {
                    $('.external-pharmacy-name').removeClass('hidden');
                    // set as required
                } else {
                    $('.external-pharmacy-name').addClass('hidden');
                }
            });

            // Function to initialize Select2
            initializeSelect2();

            function initializeSelect2() {
                // Initialize Select2 for the medicine selection
                $(".select2").each(function() {
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
                    // catch remaining quantity and selected value
                    let selectedOption = $(this).find("option:selected");
                    let remainingQuantity = selectedOption.data("remain-quantity");
                    // set the remain quantity to the input field of quantity
                    $(this).closest(".medicine-group").find("input[name='quantity[]']").attr(
                        "max", remainingQuantity);
                });
            }

            // Initialize Select2 for the existing select elements
            var $addMedicineBtn = $("#add-medicine-group");
            var $container = $("#medicine-group-container");

            $addMedicineBtn.on("click", function() {
                var $newGroup = `<div class="medicine-group mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="stock_id" class="form-label">Select Medicine</label>
                                                <select name="stock_id[]" class="form-select select2" required>
                                                    <option value="">-- Select --</option>
                                                    <?php $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option data-remain-quantity="<?php echo e($stock->remain_Quantity); ?>"
                                                            value="<?php echo e($stock->id); ?>">
                                                            <?php echo e($stock->item->name); ?> (Remaining:
                                                            <?php echo e($stock->remain_Quantity); ?>)
                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="quantity" class="form-label">Quantity to Transfer</label>
                                                <input type="number" class="form-control" name="quantity[]" required
                                                    min="1">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button"
                                                    class="btn btn-danger remove-medicine-group">Remove</button>
                                            </div>
                                        </div>
                                    </div>`;

                $container.append($newGroup);
                initializeSelect2(); // Reinitialize Select2 for the new group
            });

            $container.on("click", ".remove-medicine-group", function() {
                var $groups = $container.find(".medicine-group");
                if ($groups.length > 1) {
                    $(this).closest(".medicine-group").remove();
                } else {
                    alert('At least one medicine entry is required.');
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("stockTransfers.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/stockTransfers/index.blade.php ENDPATH**/ ?>