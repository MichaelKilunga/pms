<?php $__env->startSection("content"); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Edit User: <?php echo e($user->name); ?></h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route("superadmin.users.update", $user->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field("PUT"); ?>

                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input class="form-control" id="name" name="name" required type="text"
                                    value="<?php echo e(old("name", $user->name)); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" id="email" name="email" required type="email"
                                    value="<?php echo e(old("email", $user->email)); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="role">Role</label>
                                <select class="form-select" id="role" name="role">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php echo e($user->hasRole($role->name) ? "selected" : ""); ?>

                                            value="<?php echo e($role->name); ?>">
                                            <?php echo e($role->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-primary" for="pricing_mode">Pricing Strategy</label>
                                <select class="form-select" id="pricing_mode" name="pricing_mode">
                                    <option <?php echo e($user->pricing_mode === null ? "selected" : ""); ?> value="">Default (Use
                                        System Global)</option>
                                    <option <?php echo e($user->pricing_mode === "standard" ? "selected" : ""); ?> value="standard">
                                        Standard (Pre-defined Packages)</option>
                                    <option <?php echo e($user->pricing_mode === "dynamic" ? "selected" : ""); ?> value="dynamic">
                                        Dynamic (Item Based Formula)</option>
                                    <option <?php echo e($user->pricing_mode === "profit_share" ? "selected" : ""); ?>

                                        value="profit_share">Profit Share (Percentage of Item Profit)</option>
                                </select>
                                <div class="form-text text-muted">Override the global pricing strategy for this specific
                                    user.</div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a class="btn btn-secondary me-2" href="<?php echo e(route("superadmin.users")); ?>">Cancel</a>
                                <button class="btn btn-primary" type="submit">Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/superAdmin/users/edit.blade.php ENDPATH**/ ?>