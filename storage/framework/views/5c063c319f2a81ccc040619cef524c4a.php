

<?php $__env->startSection('content'); ?>
    <div class="container container-fluid mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Pharmacy Details</h3>
                <a href="<?php echo e(route('superadmin.pharmacies')); ?>" class="btn btn-secondary">Back to List</a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="fw-bold">ID:</label>
                    <p><?php echo e($pharmacy->id); ?></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Name:</label>
                    <p><?php echo e($pharmacy->name); ?></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Location:</label>
                    <p><?php echo e($pharmacy->location ?? 'N/A'); ?></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Owner:</label>
                    <p><?php echo e($pharmacy->owner->name ?? 'Unknown'); ?> (<?php echo e($pharmacy->owner->email ?? 'N/A'); ?>)</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Status:</label>
                    <p>
                        <span class="badge bg-<?php echo e($pharmacy->status == 'active' ? 'success' : 'secondary'); ?>">
                            <?php echo e(ucfirst($pharmacy->status)); ?>

                        </span>
                    </p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Created At:</label>
                    <p><?php echo e($pharmacy->created_at->format('Y-m-d H:i:s')); ?></p>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?php echo e(route('superadmin.pharmacies.edit', $pharmacy->id)); ?>" class="btn btn-warning">Edit Pharmacy</a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('superAdmin.users.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/superAdmin/pharmacies/show.blade.php ENDPATH**/ ?>