<?php $__env->startSection("content"); ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Category</h2>
            <div>
                <a class="btn btn-success" data-bs-target="#addCategoryModal" data-bs-toggle="modal"
                    href="<?php echo e(route("category.create")); ?>">Add New Category</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-striped table-bordered table-hover small table" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Category Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($category->name); ?></td>
                            <td><?php echo e($category->description); ?></td>
                            <td>
                                <a class="btn btn-primary btn-sm" data-bs-target="#viewCategoryModal<?php echo e($category->id); ?>"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-eye"></i></a>

                                <div aria-hidden="true" aria-labelledby="viewCategoryModalLabel<?php echo e($category->id); ?>"
                                    class="modal fade" id="viewCategoryModal<?php echo e($category->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewCategoryModalLabel<?php echo e($category->id); ?>">
                                                    Category Details</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display Category Information -->
                                                <div class="mb-3">
                                                    <strong>Name:</strong> <?php echo e($category->name); ?>

                                                </div>
                                                <div class="mb-3">
                                                    <strong>Description:</strong>
                                                    <?php echo e($category->description ?? "No description available"); ?>

                                                </div>
                                                <div class="mb-3">
                                                    <strong>Pharmacy:</strong> <?php echo e($category->pharmacy->name); ?>

                                                    <!-- Assuming you have a pharmacy relationship -->
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Created At:</strong>
                                                    <?php echo e($category->created_at->format("d M, Y")); ?>

                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a class="btn btn-success disabled btn-sm"
                                    data-bs-target="#editCategoryModal<?php echo e($category->id); ?>" data-bs-toggle="modal"
                                    href="#"><i class="bi bi-pencil"></i></a>
                                <!-- Edit Category Modal -->
                                <div aria-hidden="true" aria-labelledby="editCategoryModalLabel<?php echo e($category->id); ?>"
                                    class="modal fade" id="editCategoryModal<?php echo e($category->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCategoryModalLabel<?php echo e($category->id); ?>">Edit
                                                    Category</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Edit Category Form -->
                                                <form action="<?php echo e(route("category.update", $category->id)); ?>"
                                                    id="editCategoryForm<?php echo e($category->id); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field("PUT"); ?> <!-- Using PUT to indicate an update -->

                                                    <input hidden id="" name="id" type="number"
                                                        value="<?php echo e($category->id); ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="name<?php echo e($category->id); ?>">Category
                                                            Name</label>
                                                        <input class="form-control" id="name<?php echo e($category->id); ?>"
                                                            name="name" required type="text"
                                                            value="<?php echo e($category->name); ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="description<?php echo e($category->id); ?>">Description</label>
                                                        <textarea class="form-control" id="description<?php echo e($category->id); ?>" name="description"><?php echo e($category->description); ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input name="pharmacy_id" type="hidden"
                                                            value="<?php echo e(session("current_pharmacy_id")); ?>">
                                                    </div>
                                                    <button class="btn btn-primary" type="submit">Update Category</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="<?php echo e(route("category.destroy", $category->id)); ?>" method="POST"
                                    style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field("DELETE"); ?>
                                    <button class="btn btn-danger btn-sm" disabled
                                        onclick="return confirm('Are you sure you want to delete this category?')"
                                        type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div aria-hidden="true" aria-labelledby="addCategoryModalLabel" class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <!-- Category Form -->
                    <form action="<?php echo e(route("category.store")); ?>" id="categoryForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="form-label" for="name">Category Name</label>
                            <input class="form-control" id="name" name="name" required type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <input name="pharmacy_id" type="hidden" value="<?php echo e(session("current_pharmacy_id")); ?>">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("categories.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/categories/index.blade.php ENDPATH**/ ?>