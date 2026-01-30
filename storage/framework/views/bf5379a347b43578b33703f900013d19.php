<?php $__env->startSection("content"); ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Stock</h2>
            <div>
                
                
                <a class="btn btn-success" data-bs-target="#addPharmacyModal" data-bs-toggle="modal"
                    href="<?php echo e(route("pharmacies.create")); ?>">Add New Pharmacy</a>
                
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-striped table-bordered table-hover small table" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pharmacy Name</th>
                        <th>Pharmacy Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pharmacies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pharmacy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($pharmacy->name); ?></td>
                            <td><?php echo e($pharmacy->location); ?></td>
                            <!-- <td class="d-flex justify-content-between"> -->
                            <td>
                                <a class="btn btn-primary btn-sm" href="#"><i class="bi bi-eye"
                                        data-bs-target="#viewPharmacyModal<?php echo e($pharmacy->id); ?>"
                                        data-bs-toggle="modal"></i></a>
                                <div aria-hidden="true" aria-labelledby="viewPharmacyModalLabel<?php echo e($pharmacy->id); ?>"
                                    class="modal fade" id="viewPharmacyModal<?php echo e($pharmacy->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewPharmacyModalLabel<?php echo e($pharmacy->id); ?>">
                                                    Pharmacy Details</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display Pharmacy Information -->
                                                <div class="mb-3">
                                                    <strong>Name:</strong> <?php echo e($pharmacy->name); ?>

                                                </div>
                                                <div class="mb-3">
                                                    <strong>Location:</strong>
                                                    <?php echo e($pharmacy->location ?? "No description available"); ?>

                                                </div>
                                                <div class="mb-3">
                                                    <strong>Created At:</strong>
                                                    <?php echo e($pharmacy->created_at->format("d M, Y")); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- edit pharnacy modal -->
                                <a class="btn btn-success btn-sm" data-bs-target="#editPharmacyModal<?php echo e($pharmacy->id); ?>"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-pencil"></i></a>
                                <div aria-hidden="true" aria-labelledby="editPharmacyModalLabel<?php echo e($pharmacy->id); ?>"
                                    class="modal fade" id="editPharmacyModal<?php echo e($pharmacy->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editPharmacyModalLabel<?php echo e($pharmacy->id); ?>">Edit
                                                    Pharmacy</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Edit Pharmacy Form -->
                                                <form action="<?php echo e(route("pharmacies.update", $pharmacy->id)); ?>"
                                                    id="editPharmacyForm<?php echo e($pharmacy->id); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field("PUT"); ?> <!-- Using PUT to indicate an update -->

                                                    <input hidden id="" name="id" type="number"
                                                        value="<?php echo e($pharmacy->id); ?>">

                                                    <div class="mb-3">
                                                        <label class="form-label" for="name<?php echo e($pharmacy->id); ?>">Pharmacy
                                                            Name</label>
                                                        <input class="form-control" id="name<?php echo e($pharmacy->id); ?>"
                                                            name="name" required type="text"
                                                            value="<?php echo e($pharmacy->name); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="location<?php echo e($pharmacy->id); ?>">Pharmacy Location</label>
                                                        <textarea class="form-control" id="location<?php echo e($pharmacy->id); ?>" name="location"><?php echo e($pharmacy->location); ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input name="pharmacy_id" type="hidden"
                                                            value="<?php echo e(session("current_pharmacy_id")); ?>">
                                                    </div>
                                                    <button class="btn btn-primary" type="submit">Update Pharmacy</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="<?php echo e(route("pharmacies.destroy", $pharmacy->id)); ?>" method="POST"
                                    style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field("DELETE"); ?>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Do you want to delete this pharmacy?')" type="submit"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div aria-hidden="true" aria-labelledby="addPharmacyModalLabel" class="modal fade" id="addPharmacyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPharmacyModalLabel">Add New Pharmacy</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <!-- Pharmacy Form -->
                    <form action="<?php echo e(route("pharmacies.store")); ?>" id="pharmacyForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label" for="name">Pharmacy Name</label>
                            <input class="form-control" id="name" name="name" required type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="location">Pharmacy Location</label>
                            <textarea class="form-control" id="location" name="location"></textarea>
                        </div>
                        <div class="mb-3">
                            <input name="pharmacy_id" type="hidden" value="<?php echo e(session("current_pharmacy_id")); ?>">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Save Pharmacy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
<?php $__env->stopSection(); ?>

<?php echo $__env->make("pharmacies.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/pharmacies/index.blade.php ENDPATH**/ ?>