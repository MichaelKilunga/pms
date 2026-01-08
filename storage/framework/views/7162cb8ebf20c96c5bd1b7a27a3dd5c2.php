

<?php $__env->startSection('content'); ?>
    <div class="container container-fluid mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Edit Pharmacy</h3>
                <a href="<?php echo e(route('superadmin.pharmacies')); ?>" class="btn btn-secondary">Back to List</a>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('superadmin.pharmacies.update', $pharmacy->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Pharmacy Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?php echo e(old('name', $pharmacy->name)); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <textarea class="form-control" id="location" name="location" rows="3"><?php echo e(old('location', $pharmacy->location)); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="owner_id" class="form-label">Owner</label>
                        <select class="form-select" id="owner_id" name="owner_id" required>
                            <option value="" disabled>Select Owner</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $owners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $owner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($owner->id); ?>"
                                    <?php echo e($pharmacy->owner_id == $owner->id ? 'selected' : ''); ?>>
                                    <?php echo e($owner->name); ?> (<?php echo e($owner->email); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Update Pharmacy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('superAdmin.users.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/superAdmin/pharmacies/edit.blade.php ENDPATH**/ ?>