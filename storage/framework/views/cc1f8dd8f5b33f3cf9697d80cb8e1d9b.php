<?php $__env->startSection("content"); ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-2">
            <h1>All Contracts</h1>
            <a class="btn btn-primary" href="<?php echo e(route("contracts.admin.create")); ?>">Create Contract</a>
        </div>

        <table class="table-striped small mt-4 table" id="Table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Owner</th>
                    <th>Package</th>
                    <th>Package Duration</th>
                    <th>Package Amount</th>
                    <th>Status</th>
                    <th>Payment Status</th>
                    <th>Time remained</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($contract->owner->name); ?></td>
                        <td><?php echo e($contract->package->name); ?></td>
                        
                        <td><?php echo e(\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date))); ?>

                            days
                            (<?php echo e(\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30); ?>

                            Month)</td>
                        <td><?php echo e(number_format((\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30) * $contract->package->price)); ?>

                        </td>
                        <td><?php echo e($contract->status); ?></td>
                        <td><?php echo e($contract->payment_status); ?></td>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contract->status == "active"): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(\Carbon\Carbon::parse($contract->end_date) < now()): ?>
                                <td class="text-danger">Expired
                                    <?php echo e(\Carbon\Carbon::parse($contract->end_date)->diffForHumans()); ?>

                                </td>
                            <?php else: ?>
                                <td><?php echo e(\Carbon\Carbon::parse($contract->end_date)->diffForHumans()); ?></td>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php elseif($contract->status == "inactive"): ?>
                            <td>Not started</td>
                        <?php elseif($contract->status == "graced"): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(\Carbon\Carbon::parse($contract->grace_end_date) < now()): ?>
                                <td class="text-danger">Grace Period Expired
                                    <?php echo e(\Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans()); ?>

                                </td>
                            <?php else: ?>
                                <td>In Grace Period
                                    <?php echo e(\Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans()); ?></td>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <td>
                            
                            <a class="btn btn-primary" data-bs-target="#viewModal<?php echo e($contract->id); ?>" data-bs-toggle="modal"
                                href="#"><i class="bi bi-eye"></i></a>
                            
                            <div aria-hidden="true" aria-labelledby="viewModalLabel" class="modal fade"
                                id="viewModal<?php echo e($contract->id); ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModalLabel">View Contract</h5>
                                            <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                type="button"></button>
                                        </div>
                                        <div class="modal-body text-left">
                                            <p><strong>Owner:</strong> <?php echo e($contract->owner->name); ?></p>
                                            <p><strong>Package:</strong> <?php echo e($contract->package->name); ?></p>
                                            <p><strong>Status:</strong> <?php echo e($contract->status); ?></p>
                                            <p><strong>Payment Status:</strong> <?php echo e($contract->payment_status); ?></p>
                                            <p><strong>Start Date:</strong> <?php echo e($contract->start_date); ?></p>
                                            <p><strong>End Date:</strong> <?php echo e($contract->end_date); ?></p>
                                            <p><strong>Grace Period:</strong>
                                                <?php echo e(\Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans()); ?></p>
                                            <p><strong>Created At:</strong> <?php echo e($contract->created_at); ?></p>
                                            <p><strong>Updated At:</strong> <?php echo e($contract->updated_at); ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal"
                                                type="button">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <a class="btn btn-success <?php echo e($contract->payment_status == "payed" ? "disabled" : ""); ?>"
                                href="<?php echo e(route("contracts.admin.confirm", $contract->id)); ?>"
                                onclick="return confirm('Do you want to confirm payment?')"><i
                                    class="bi bi-cash-coin"></i></a>
                            
                            <a class="btn btn-warning <?php echo e(\Carbon\Carbon::parse($contract->end_date) < now() && !$contract->grace_end_date ? "" : "disabled"); ?>"
                                href="javascript:void(0);"
                                onclick="let daysToAdd = prompt('How many days do you want to add?'); 
                                     if (daysToAdd !== null && !isNaN(daysToAdd)) {
                                         window.location.href = '<?php echo e(route("contracts.admin.grace", $contract->id)); ?>' + '?days=' + daysToAdd;
                                     } else {
                                         alert('Please enter a valid number of days.');
                                     }">
                                <i class="bi bi-clock"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("contracts.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/contracts/admin/index.blade.php ENDPATH**/ ?>