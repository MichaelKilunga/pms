<?php $__env->startSection('content'); ?>
    <div class="container mt-4">
        
        <div class="row d-flex justify-content-between m-2">
            <div class="text-primary text-left fs-4 col-md-6">
                <h2>Receipts</h2>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?php echo e(route('sales')); ?>" class="btn btn-primary">Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table id="Table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Receipt No</th>
                            <th>Amount(TZS)</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                
                                <td><?php echo e($loop->iteration); ?></td>
                                
                                <td><?php echo e(number_format($receipt->total_amount, 0)); ?></td>
                                
                                <td><?php echo e(date('d-m-Y', strtotime($receipt->date))); ?></td>
                                
                                <td>
                                    <a href="<?php echo e(route('printReceipt', ['date' => $receipt->date])); ?>"
                                        class="btn btn-primary"><i class="bi bi-printer"></i> Print</a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('sales.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/sales/receipts.blade.php ENDPATH**/ ?>