<?php $__env->startSection('content'); ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-2">
            <h2 class="text-primary fw-bold">Pharmacist</h2>
            <div>
                <button class="btn btn-success" data-bs-target="#addUserModal" data-bs-toggle="modal" type="button">
                    Add New Pharmacist
                </button>
                <!-- <a class="btn btn-success" href="<?php echo e(route('staff.create')); ?>">Add New staff</a> -->
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-striped table-bordered table-hover small table" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($staff->user->name); ?></td>
                            <td><?php echo e($staff->user->email); ?></td>
                            <td><?php echo e($staff->user->phone); ?></td>
                            <td><?php echo e($staff->user->role == 'Staff' ? 'Pharmacist' : 'Pharmacist'); ?></td>
                            <td><?php echo e($staff->status == 'active' ? 'Active' : 'Inactive'); ?></td>
                            <td>
                                <a class="btn btn-primary btn-sm" data-bs-target="#viewStaffModal<?php echo e($staff->id); ?>"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-eye"></i></a>
                                <div aria-hidden="true" aria-labelledby="viewStaffModalLabel<?php echo e($staff->id); ?>"
                                    class="modal fade" id="viewStaffModal<?php echo e($staff->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="viewStaffModalLabel<?php echo e($staff->id); ?>">
                                                    Pharmacist's Details</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display Pharmacy Information -->
                                                <div class="mb-3">
                                                    <strong>Name:</strong> <?php echo e($staff->user->name); ?>

                                                </div>
                                                <div class="mb-3">
                                                    <strong>Email:</strong>
                                                    <?php echo e($staff->user->email ?? 'No eamil available'); ?>

                                                </div>
                                                <div class="mb-3">
                                                    <strong>Phone:</strong>
                                                    <?php echo e($staff->user->phone ?? 'No phone# available'); ?>

                                                </div>
                                                <div class="mb-3">
                                                    <strong>Role:</strong>
                                                    <?php echo e($staff->user->role ? 'Pharmacist' : 'Pharmacist'); ?>

                                                </div>
                                                <div class="mb-3">
                                                    <strong>Created At:</strong>
                                                    <?php echo e($staff->user->created_at->format('d M, Y')); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a class="btn btn-success btn-sm" data-bs-target="#editStaffModal<?php echo e($staff->id); ?>"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-pencil"></i></a>
                                <div aria-hidden="true" aria-labelledby="editStaffModalLabel<?php echo e($staff->id); ?>"
                                    class="modal fade" id="editStaffModal<?php echo e($staff->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="editStaffModalLabel<?php echo e($staff->id); ?>">Edit
                                                    Pharmacist</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Edit Pharmacist Form -->
                                                <form action="<?php echo e(route('staff.update', $staff->user->id)); ?>"
                                                    id="editStaffForm<?php echo e($staff->id); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?> <!-- Using PUT to indicate an update -->

                                                    <input hidden id="" name="id" type="number"
                                                        value="<?php echo e($staff->user_id); ?>">

                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="name<?php echo e($staff->id); ?>">Pharmacist's
                                                            Name</label>
                                                        <input class="form-control" id="name<?php echo e($staff->id); ?>"
                                                            name="name" required type="text"
                                                            value="<?php echo e($staff->user->name); ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" for="name<?php echo e($staff->id); ?>">Pharmacist
                                                            Phone</label>
                                                        <input class="form-control" id="name<?php echo e($staff->id); ?>"
                                                            name="phone" required type="text"
                                                            value="<?php echo e($staff->user->phone); ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" for="name<?php echo e($staff->id); ?>">Pharmacist
                                                            Email</label>
                                                        <input class="form-control" id="name<?php echo e($staff->id); ?>"
                                                            name="email" required type="text"
                                                            value="<?php echo e($staff->user->email); ?>">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="role<?php echo e($staff->id); ?>">Role</label>
                                                        <select class="form-select" id="role<?php echo e($staff->id); ?>"
                                                            name="role" required>
                                                            <option <?php echo e($staff->user->role == 'staff' ? 'selected' : ''); ?>

                                                                value="staff">Pharmacist</option>
                                                            
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <input name="pharmacy_id" type="hidden"
                                                            value="<?php echo e(session('current_pharmacy_id')); ?>">
                                                    </div>
                                                    <button class="btn btn-primary" type="submit">Update
                                                        Pharmacist</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="<?php echo e(route('staff.destroy', $staff->user_id)); ?>" method="POST"
                                    style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($staff->status == 'active'): ?>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to deactivate?')"
                                            type="submit"><i class="bi bi-x-circle"></i>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($staff->status == 'inactive'): ?>
                                        <button class="btn btn-success btn-sm"
                                            onclick="return confirm('Are you sure you want to activate?')"
                                            type="submit"><i class="bi bi-check-circle"></i>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add User Modal -->
    <div aria-hidden="true" aria-labelledby="addUserModalLabel" class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addUserModalLabel">Add New Pharmacist</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('staff.store')); ?>" id="addUserForm" method="POST">
                        <?php echo csrf_field(); ?>
                        <input class="form-control" hidden id="pharmacy_id" name="pharmacy_id" type="number"
                            value="<?php echo e(session('current_pharmacy_id')); ?>">
                        <input class="form-control" hidden id="user_id" name="user_id" type="number" value="0">
                        <input class="form-control" hidden id="password" name="password" type="password"
                            value="0">
                        <div class="mb-3">
                            <label class="form-label" for="name">Pharmacist Name</label>
                            <input class="form-control" id="name" name="name" required type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Pharmacist Email</label>
                            <input class="form-control" id="email" name="email" required type="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone">Pharmacist Phone</label>
                            <input class="form-control" id="phone" name="phone" required type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="role">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option disabled selected value="">Select Role</option>
                                
                                <option value="staff">Pharmacist</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">Add Pharmacist</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('staff.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/staff/index.blade.php ENDPATH**/ ?>