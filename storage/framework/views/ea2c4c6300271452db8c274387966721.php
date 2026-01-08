<?php $__env->startSection("content"); ?>
    <div class="container">
        <div class="card mt-5 shadow-sm">
            <div class="card-header bg-primary d-flex justify-content-between text-white">
                <h3 class="mb-0">All Medicines</h3>
                <div>
                    <a class="btn btn-success m-1" href="<?php echo e(route("medicines.import-form")); ?>">Import Medicines</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($medicines->isEmpty()): ?>
                <div class="alert alert-info">No medicines found in the database.</div>
            <?php else: ?>
                <table class="table-striped table-bordered small table" id="Table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Brand Name</th>
                            <th>Generic Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e(Str::limit($medicine->brand_name, 15) ?? "N/A"); ?></td>
                                <td><?php echo e(Str::limit($medicine->generic_name, 10) ?? "N/A"); ?></td>
                                <td><?php echo e($medicine->category ?? "N/A"); ?></td>
                                
                                <td><?php echo e(Str::limit($medicine->description, 20) ?? "N/A"); ?></td>
                                <td><?php echo e(ucfirst($medicine->status ?? "unknown")); ?></td>
                                <td class="">
                                    <a class="btn btn-sm btn-success"
                                        href="<?php echo e(route("allMedicines.edit", $medicine->id)); ?>"><i
                                            class="bi bi-pencil"></i></a>
                                    <form action="<?php echo e(route("allMedicines.destroy", $medicine->id)); ?>" method="POST"
                                        style="display:inline-block;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field("DELETE"); ?>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this medicine?')"
                                            type="submit"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("superAdmin.medicines.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/superAdmin/medicines/index.blade.php ENDPATH**/ ?>