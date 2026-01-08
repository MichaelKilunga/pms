<?php $__env->startSection("content"); ?>
    <div class="container mt-4">
        <div class="table-reponsive container">
            <h1 class="m-2 text-center">Manage Users</h1>
            <hr class="my-2">
            <div class="table-responsive">
                <table class="table-striped small table" id="Table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created at</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td><?php echo e($user->created_at); ?></td>
                                <td><?php echo e($user->role); ?></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->id != $user->id): ?>
                                        <a class="btn btn-warning" href="<?php echo e(route("superadmin.users.edit", $user->id)); ?>"><i
                                                class="bi bi-pencil"></i></a>
                                        <a class="btn btn-success" href="<?php echo e(route("superadmin.users.show", $user->id)); ?>"><i
                                                class="bi bi-eye"></i></a>
                                        <form action="<?php echo e(route("superadmin.users.delete", $user->id)); ?>" method="POST"
                                            style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field("DELETE"); ?>
                                            <button class="btn btn-danger"
                                                onclick='return confirm("Do you want to delete?")' type="submit"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                        <a class="btn btn-info"
                                            href="<?php echo e(route("superAdmin.users.notifications", $user->id)); ?>"
                                            title="Notification Settings">
                                            <i class="bi bi-bell"></i>
                                        </a>
                                    <?php else: ?>
                                        <p class="text-primary text-center">You!</p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("superAdmin.users.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/superAdmin/users/index.blade.php ENDPATH**/ ?>