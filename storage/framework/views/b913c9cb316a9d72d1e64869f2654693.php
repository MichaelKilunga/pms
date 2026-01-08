;

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Expense Categories</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                + Add Category
            </button>
        </div>

        
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="Table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($category->name); ?></td>
                                    <td><?php echo e($category->description ?? '-'); ?></td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->is_active == '1'): ?>
                                        <td><span class="badge bg-success">Active</span></td>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->is_active == '0'): ?>
                                        <td><span class="badge bg-danger">Inactive</span></td>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <td><?php echo e($category->created_at->format('Y-m-d')); ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm view-category"
                                            data-category='<?php echo json_encode($category, 15, 512) ?>' data-bs-toggle="modal"
                                            data-bs-target="#viewCategoryModal<?php echo e($category->id); ?>">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button class="btn btn-warning btn-sm edit-category"
                                            data-category='<?php echo json_encode($category, 15, 512) ?>' data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal<?php echo e($category->id); ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>


                                        
                                        <div class="modal fade" id="viewCategoryModal<?php echo e($category->id); ?>" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-md modal-dialog-scrollable">
                                                <div class="modal-content shadow-lg rounded-3">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-tags me-2"></i> Category Details
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <div class="list-group list-group-flush">
                                                                    <div class="list-group-item">
                                                                        <strong>Name:</strong> <span
                                                                            id="view-name"><?php echo e($category->name); ?></span>
                                                                    </div>
                                                                    <div class="list-group-item">
                                                                        <strong>Description:</strong> <span
                                                                            id="view-description"><?php echo e($category->description ?? '-'); ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="list-group list-group-flush">
                                                                    <div class="list-group-item">
                                                                        <strong>Status:</strong>
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->is_active == '1'): ?>
                                                                            <span class="badge bg-success">Active</span>
                                                                        <?php else: ?>
                                                                            <span class="badge bg-secondary">Inactive</span>
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    </div>
                                                                    <div class="list-group-item">
                                                                        <strong>Created At:</strong> <span
                                                                            id="view-created-at"><?php echo e($category->created_at->format('Y-m-d')); ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i> Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        
                                        <div class="modal fade" id="editCategoryModal<?php echo e($category->id); ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <form method="POST" action="<?php echo e(route('category.update', $category->id)); ?>"
                                                    class="modal-content" id="editCategoryForm<?php echo e($category->id); ?>">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Expense Category</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="name">Name</label>
                                                            <input type="text" name="name" id="edit-name"
                                                                class="form-control" required
                                                                value="<?php echo e($category->name); ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Description</label>
                                                            <textarea name="description" id="edit-description" class="form-control"><?php echo e($category->description); ?></textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Category Status</label>
                                                            <select name="is_active" class="form-select" required>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->is_active == '1'): ?>
                                                                    <option selected value="1" selected>Active</option>
                                                                    <option value="0">Inactive</option>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->is_active == '0'): ?>
                                                                    <option value="1" selected>Active</option>
                                                                    <option selected value="0">Inactive</option>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-warning">Update</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <form action="<?php echo e(route('category.destroy', $category->id)); ?>" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this category?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>
                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="<?php echo e(route('category.store')); ?>" method="POST" class="modal-content">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Add Expense Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Expenses name"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Enter description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Category Status</label>
                        <select name="is_active" class="form-select" required>
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(function() {
            // View category
            $('.view-category').on('click', function() {
                var category = $(this).data('category'); // already parsed by jQuery
                $('#view-name').text(category.name);
                $('#view-description').text(category.description ?? '-');
                $('#view-status').text(category.is_active ? category.is_active : 'N/A');
                $('#view-created-at').text(category.created_at ?? '-');
            });

            // Edit category
            $('.edit-category').on('click', function() {
                var category = $(this).data('category');
                $('#editCategoryForm').attr('action', '/expense-categories/' + category.id);
                $('#edit-name').val(category.name);
                $('#edit-description').val(category.description);
            });


            // Datatable
            $('#categoriesTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('expenses.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/expenses/category.blade.php ENDPATH**/ ?>