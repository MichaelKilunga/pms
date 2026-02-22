<?php $__env->startSection("content"); ?>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-danger fs-2 fw-bold">Sales returns management</h2>
        </div>
        <hr>

        <hr class="mb-2">

        <!-- Sales Table -->
        <div class="table-responsive rounded-3 shadow-sm">
            <table class="table-striped table-hover table-bordered small table align-middle" id="Table">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Sales Name</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Sales Date</th>
                        <th>Reasons</th>
                        <th>Posted by</th>
                        <th>Approved By</th>
                        <th>Date</th>
                        <th>Status</th>
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasrole', "Owner")): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $returns): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($returns->sale->item->name); ?></td>
                            <td><?php echo e($returns->quantity); ?></td>
                            <td><?php echo e($returns->refund_amount); ?></td>
                            <td><?php echo e($returns->date); ?></td>
                            <td><?php echo e($returns->reason ?? "NILL"); ?></td>
                            <td><?php echo e($returns->staff->name); ?></td>
                            <td><?php echo e($returns->approvedBy ? $returns->approvedBy->name : "Not approved"); ?></td>
                            <td><?php echo e($returns->created_at); ?></td>
                            <td>
                                <?php if($returns->return_status == "pending"): ?>
                                    <span class="text-dark fw-bold"><?php echo e($returns->return_status); ?></span>
                                <?php elseif($returns->return_status == "approved"): ?>
                                    <span class="text-success fw-bold"><?php echo e($returns->return_status); ?></span>
                                <?php else: ?>
                                    <span class="text-danger fw-bold"><?php echo e($returns->return_status); ?></span>
                                <?php endif; ?>
                            </td>
                            <?php if (\Illuminate\Support\Facades\Blade::check('hasrole', "Owner")): ?>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <form action="<?php echo e(route("salesReturns.update")); ?>" class="d-inline" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input name="return_id" type="hidden" value="<?php echo e($returns->id); ?>">
                                            <input name="return_status" type="hidden" value="approved">
                                            <button class="btn btn-success btn-sm d-flex me-2"
                                                onclick="return confirm('Are you sure to approve?')" type="submit">
                                                <i class="bi bi-check"></i> Approve
                                            </button>
                                        </form>

                                        <form action="<?php echo e(route("salesReturns.update")); ?>" method="POST"
                                            style="margin-left: 5px;">
                                            <?php echo csrf_field(); ?>
                                            <input name="return_id" type="hidden" value="<?php echo e($returns->id); ?>">
                                            <input name="return_status" type="hidden" value="rejected">
                                            <button <?php echo e($returns->return_status == "rejected" ? "disabled" : ""); ?>

                                                class="btn btn-danger btn-sm d-flex"
                                                onclick="return confirm('Are you sure to reject?')" type="submit">
                                                <i class="bi bi-x"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            <?php endif; ?>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("sales.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /media/michaelkilunga/C/SKYLINK/pms/resources/views/sales_returns/index.blade.php ENDPATH**/ ?>