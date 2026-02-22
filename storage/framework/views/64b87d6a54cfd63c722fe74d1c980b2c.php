<?php $__env->startSection("content"); ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="m-2">Medicines</h2>
            <div class="d-flex justify-content-between">
                <div>
                    <a class="btn btn-success m-1" data-bs-target="#createMedicineModal" data-bs-toggle="modal"
                        href="#">Add
                        New
                        Medicine</a>
                </div>
                <div>
                    <a class="btn btn-danger m-1" href="import">Import from online</a>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-striped table-bordered table-hover small table" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            
                            <td><?php echo e($medicine->name); ?></td>
                            <td><?php echo e($medicine->category->name); ?></td>
                            
                            <td>
                                <a class="btn btn-primary btn-sm" data-bs-target="#viewMedicineModal<?php echo e($medicine->id); ?>"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-eye"></i></a>
                                <div aria-hidden="true" aria-labelledby="viewMedicineModalLabel<?php echo e($medicine->id); ?>"
                                    class="modal fade" id="viewMedicineModal<?php echo e($medicine->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewMedicineModalLabel<?php echo e($medicine->id); ?>">
                                                    Medicine Details</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div><strong>Name:</strong> <?php echo e($medicine->name); ?></div>
                                                <div><strong>Category:</strong> <?php echo e($medicine->category->name); ?></div>
                                                <div><strong>Pharmacy:</strong> <?php echo e($medicine->pharmacy->name); ?></div>
                                                <div><strong>Created At:</strong>
                                                    <?php echo e($medicine->created_at->format("d M, Y")); ?></div>
                                                <div><strong>Updated At:</strong>
                                                    <?php echo e($medicine->updated_at->format("d M, Y")); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a class="btn btn-success btn-sm" data-bs-target="#editMedicineModal<?php echo e($medicine->id); ?>"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-pencil"></i></a>
                                <div aria-hidden="true" aria-labelledby="editMedicineModalLabel<?php echo e($medicine->id); ?>"
                                    class="modal fade" id="editMedicineModal<?php echo e($medicine->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editMedicineModalLabel<?php echo e($medicine->id); ?>">Edit
                                                    Medicine</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="<?php echo e(route("medicines.update", $medicine->id)); ?>"
                                                    method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field("PUT"); ?>
                                                    <input hidden id="" name="id" type="number"
                                                        value="<?php echo e($medicine->id); ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="name">Medicine Name</label>
                                                        <input class="form-control" name="name" required type="text"
                                                            value="<?php echo e($medicine->name); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="category">Category</label>
                                                        <select class="chosen form-select" name="category_id" required>
                                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option
                                                                    <?php echo e($medicine->category_id == $category->id ? "selected" : ""); ?>

                                                                    value="<?php echo e($category->id); ?>">
                                                                    <?php echo e($category->name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="pharmacy">Pharmacy:
                                                            <?php echo e($medicine->pharmacy->name); ?></label>
                                                        <input hidden name="pharmacy_id" type="number"
                                                            value="<?php echo e($medicine->pharmacy_id); ?>">
                                                    </div>
                                                    <button class="btn btn-success" type="submit">Update Medicine</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                
                                <?php
                                    //select * from stocks where item_id = $medicine->id
                                    $hasStock = \App\Models\Stock::where("item_id", $medicine->id)->exists();
                                    // dd($hasStock);
                                ?>
                                <?php if($hasStock): ?>
                                    <button class="btn btn-danger btn-sm" disabled><i class="bi bi-trash"></i></button>
                                <?php else: ?>
                                    
                                    <form action="<?php echo e(route("medicines.destroy", $medicine->id)); ?>" method="POST"
                                        style="display:inline;">
                                        
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field("DELETE"); ?>
                                        <input class="hidden" type="number" value="">
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Do you want to delete this medicine?')"
                                            type="submit"><i class="bi bi-trash"></i></button>
                                    </form>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Medicine Modal -->
    <div aria-hidden="true" aria-labelledby="createMedicineModalLabel" class="modal fade" id="createMedicineModal"
        tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMedicineModalLabel">Add New Medicine</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route("medicines.store")); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label" for="name">Medicine Name</label>
                            <input class="form-control" id="name" name="name" required type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-select" name="category_id" required>
                                <option selected value="">--Select category--</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="pharmacy">Pharmacy: <?php echo e(session("pharmacy_name")); ?></label>
                            <input hidden name="pharmacy_id" type="number"
                                value="<?php echo e(session("current_pharmacy_id")); ?>">
                        </div>
                        <button class="btn btn-success mb-2" type="submit">Save Medicine</button>
                </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("medicines.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /media/michaelkilunga/C/SKYLINK/pms/resources/views/medicines/index.blade.php ENDPATH**/ ?>