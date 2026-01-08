<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="card mt-5 shadow-sm">

            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <h3 class="mb-0">Import Medicines</h3>
                <a class="btn btn-secondary" href="<?php echo e(route('allMedicines.all')); ?>"
                    :active="request() - > routeIs('allMedicines.all')">
                    <?php echo e(__('Back')); ?>

                </a>
            </div>
            <div class="card-body">
                <form id="forAdminImport" action="<?php echo e(route('medicines.import')); ?>" method="POST"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File, <strong class="text-danger"> 8 Columns:
                                brand_name(*<small>this is required</small>),</strong><small class="text-success">
                                generic_name,
                                description, status, category, class, dosage_form, strength, manufacturer,
                                manufacturing_country </small></label>
                        <input type="file" name="file" id="file" class="form-control bg-warning" required>
                        <small class="form-text text-muted">Supported formats: CSV, Excel (.xlsx)</small>
                    </div>
                    <span class="text-success progress-bar" id="progressBar"></span>
                    <button type="submit" id="forAdminImport" class="btn btn-primary w-100">Import Medicines</button>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('superAdmin.medicines.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/superAdmin/medicines/import.blade.php ENDPATH**/ ?>