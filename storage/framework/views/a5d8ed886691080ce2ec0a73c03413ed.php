

<?php $__env->startSection('content'); ?>
    <div class="container-fluid px-4 py-4">
        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 text-primary fw-bold mb-0">Invoices & Billing</h1>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'superadmin'): ?>
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-primary text-white h-100">
                        <div class="card-body">
                            <h6 class="text-white-50 text-uppercase small fw-bold">Total Revenue</h6>
                            <h3 class="mb-0 fw-bold">TZS <?php echo e(number_format($totalRevenue ?? 0)); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-warning text-dark h-100">
                        <div class="card-body">
                            <h6 class="text-dark-50 text-uppercase small fw-bold">Pending Payments</h6>
                            <h3 class="mb-0 fw-bold">TZS <?php echo e(number_format($pendingAmount ?? 0)); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-danger text-white h-100">
                        <div class="card-body">
                            <h6 class="text-white-50 text-uppercase small fw-bold">Total Due Amount</h6>
                            <h3 class="mb-0 fw-bold">TZS <?php echo e(number_format($totalDue ?? 0)); ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-info text-dark h-100">
                        <div class="card-body">
                            <h6 class="text-dark-50 text-uppercase small fw-bold">Next Due Date</h6>
                            <h3 class="mb-0 fw-bold">
                                <?php echo e($nextDueDate ? \Carbon\Carbon::parse($nextDueDate)->format('d M Y') : 'N/A'); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="card rounded-4 border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Invoice #</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Date</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Amount</th>
                                <th class="py-3 text-uppercase small fw-bold text-muted">Status</th>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'superadmin'): ?>
                                    <th class="py-3 text-uppercase small fw-bold text-muted">Client</th>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <th class="pe-4 py-3 text-end text-uppercase small fw-bold text-muted">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-4 fw-bold">INV-<?php echo e(str_pad($invoice->id, 6, '0', STR_PAD_LEFT)); ?></td>
                                    <td><?php echo e($invoice->created_at->format('d M Y')); ?></td>
                                    <td class="fw-bold">TZS <?php echo e(number_format($invoice->amount)); ?></td>
                                    <td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->status === 'paid'): ?>
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Paid</span>
                                        <?php elseif($invoice->status === 'overdue'): ?>
                                            <span
                                                class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Overdue</span>
                                        <?php else: ?>
                                            <span
                                                class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">Unpaid</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'superadmin'): ?>
                                        <td><?php echo e($invoice->user->name ?? 'Unknown'); ?></td>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <td class="pe-4 text-end">
                                        <a href="<?php echo e(route('invoices.show', $invoice->id)); ?>"
                                            class="btn btn-sm btn-outline-primary rounded-pill">
                                            <i class="bi bi-eye me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="<?php echo e(Auth::user()->role === 'superadmin' ? 6 : 5); ?>"
                                        class="text-center py-5 text-muted">
                                        <i class="bi bi-receipt display-6 mb-3 d-block opacity-25"></i>
                                        No invoices found.
                                    </td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/invoices/index.blade.php ENDPATH**/ ?>