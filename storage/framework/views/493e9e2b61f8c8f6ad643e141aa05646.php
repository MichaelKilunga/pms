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
                <?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="<?php echo e($contract->payment_notified ? 'table-info' : ''); ?>">
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($contract->owner->name); ?></td>
                        <td>
                            <?php echo e($contract->package->name); ?>

                            <?php if($contract->is_current_contract): ?>
                                <span class="badge bg-success">Current</span>
                            <?php endif; ?>
                        </td>
                        
                        <td><?php echo e(\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date))); ?>

                            days
                            (<?php echo e(\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30); ?>

                            Month)</td>
                        <td><?php echo e(number_format($contract->amount)); ?>

                        </td>
                        <td>
                             <span class="badge bg-secondary"><?php echo e($contract->status); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($contract->payment_status == 'payed' ? 'success' : 'warning'); ?>">
                                <?php echo e($contract->payment_status); ?>

                            </span>
                            <?php if($contract->payment_notified): ?>
                                <span class="badge bg-info text-white" title="Owner notified payment"><i class="bi bi-bell-fill"></i> Notified</span>
                            <?php endif; ?>
                        </td>
                        
                        <?php if($contract->status == "active"): ?>
                            <?php if(\Carbon\Carbon::parse($contract->end_date) < now()): ?>
                                <td class="text-danger">Expired
                                    <?php echo e(\Carbon\Carbon::parse($contract->end_date)->diffForHumans()); ?>

                                </td>
                            <?php else: ?>
                                <td><?php echo e(\Carbon\Carbon::parse($contract->end_date)->diffForHumans()); ?></td>
                            <?php endif; ?>
                        <?php elseif($contract->status == "inactive"): ?>
                            <td>Not started</td>
                        <?php elseif($contract->status == "graced"): ?>
                            <?php if(\Carbon\Carbon::parse($contract->grace_end_date) < now()): ?>
                                <td class="text-danger">Grace Period Expired
                                    <?php echo e(\Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans()); ?>

                                </td>
                            <?php else: ?>
                                <td>In Grace Period
                                    <?php echo e(\Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans()); ?></td>
                            <?php endif; ?>
                        <?php else: ?>
                           <td>-</td>
                        <?php endif; ?>

                        <td>
                            
                            <a class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#viewModal<?php echo e($contract->id); ?>"
                                href="#" title="View Details"><i class="bi bi-eye"></i></a>
                            
                            <a class="btn btn-sm btn-outline-warning" href="<?php echo e(route('contracts.admin.edit', $contract->id)); ?>" title="Edit Contract">
                                <i class="bi bi-pencil"></i>
                            </a>
                            
                            
                            <?php if($contract->payment_status == 'payed' && (!$contract->is_current_contract || $contract->status == 'inactive') && \Carbon\Carbon::parse($contract->end_date)->isFuture()): ?>
                                <a class="btn btn-sm btn-info" href="<?php echo e(route('contracts.admin.initiate', $contract->id)); ?>" title="Initiate / Activate Contract" onclick="return confirm('Initiate and Activate this contract?')">
                                    <i class="bi bi-play-fill text-white"></i>
                                </a>
                            <?php endif; ?>

                            
                            <a class="btn btn-sm btn-success <?php echo e($contract->payment_status == "payed" ? "disabled" : ""); ?>"
                                href="<?php echo e(route("contracts.admin.confirm", $contract->id)); ?>"
                                onclick="return confirm('Do you want to confirm payment?')" title="Confirm Payment"><i
                                    class="bi bi-cash-coin"></i></a>
                            
                            
                            <a class="btn btn-sm btn-warning <?php echo e(\Carbon\Carbon::parse($contract->end_date) < now() && !$contract->grace_end_date ? "" : "disabled"); ?>"
                                href="javascript:void(0);"
                                onclick="let daysToAdd = prompt('How many days do you want to add?'); 
                                     if (daysToAdd !== null && !isNaN(daysToAdd)) {
                                         window.location.href = '<?php echo e(route("contracts.admin.grace", $contract->id)); ?>' + '?days=' + daysToAdd;
                                     } else {
                                         alert('Please enter a valid number of days.');
                                     }" title="Add Grace Period">
                                <i class="bi bi-clock"></i>
                            </a>

                            
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
                                            <p><strong>Pricing Strategy:</strong> <?php echo e($contract->pricing_strategy); ?></p>
                                            <p><strong>Amount:</strong> TZS <?php echo e(number_format($contract->amount)); ?></p>
                                            <p><strong>Status:</strong> <?php echo e($contract->status); ?></p>
                                            <p><strong>Payment Status:</strong> <?php echo e($contract->payment_status); ?></p>
                                            <p><strong>Payment Notified:</strong> <?php echo e($contract->payment_notified ? 'Yes' : 'No'); ?></p>
                                            <p><strong>Is Current:</strong> <?php echo e($contract->is_current_contract ? 'Yes' : 'No'); ?></p>
                                            <p><strong>Start Date:</strong> <?php echo e($contract->start_date); ?></p>
                                            <p><strong>End Date:</strong> <?php echo e($contract->end_date); ?></p>
                                            <?php if($contract->grace_end_date): ?>
                                                <p><strong>Grace Period:</strong>
                                                    <?php echo e(\Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans()); ?></p>
                                            <?php endif; ?>
                                            <p><strong>Created At:</strong> <?php echo e($contract->created_at); ?></p>
                                            
                                            <hr>
                                            <h6>Details:</h6>
                                            <pre><?php echo e(json_encode($contract->details, JSON_PRETTY_PRINT)); ?></pre>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal"
                                                type="button">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("contracts.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /media/michaelkilunga/C/SKYLINK/pms/resources/views/contracts/admin/index.blade.php ENDPATH**/ ?>