

<?php $__env->startSection('content'); ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">User Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">ID:</div>
                            <div class="col-md-8"><?php echo e($user->id); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Name:</div>
                            <div class="col-md-8"><?php echo e($user->name); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Email:</div>
                            <div class="col-md-8"><?php echo e($user->email); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Role:</div>
                            <div class="col-md-8">
                                <span class="badge bg-secondary"><?php echo e($user->role); ?></span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Phone:</div>
                            <div class="col-md-8"><?php echo e($user->phone ?? 'N/A'); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Pricing Mode:</div>
                            <div class="col-md-8"><?php echo e($user->pricing_mode ?? 'N/A'); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Created At:</div>
                            <div class="col-md-8"><?php echo e($user->created_at->format('d M Y, h:i A')); ?></div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('superadmin.users')); ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <a href="<?php echo e(route('superadmin.users.edit', $user->id)); ?>" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit User
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('superAdmin.users.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/superAdmin/users/show.blade.php ENDPATH**/ ?>