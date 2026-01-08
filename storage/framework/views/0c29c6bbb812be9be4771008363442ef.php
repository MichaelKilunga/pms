<?php $__env->startSection('content'); ?>
    <div class="table-responsive container">
        <h2 class="text-primary fw-bold h2 mb-2">Audit Logs</h2>
        <table class="table-bordered table-striped small table" id="Table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>Old Values</th>
                    <th>New Values</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $audits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $audit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($audit->created_at); ?></td>
                        <td><?php echo e(\App\Models\User::find($audit->user_id)?->name ?? 'System'); ?></td>
                        <td><?php echo e($audit->event); ?></td>
                        <td><?php echo e(json_encode($audit->old_values)); ?></td>
                        <td><?php echo e(json_encode($audit->new_values)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>

        <div class="mt-4">
            <?php echo e($audits->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('audits.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/audits/index.blade.php ENDPATH**/ ?>