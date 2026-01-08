<?php $__env->startSection("content"); ?>
    <div class="container mt-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->unreadNotifications->count() == 0 && Auth::user()->notifications->count() == 0): ?>
            <div class="rounded-4 w-100 bg-light bg-gradient p-5 text-center shadow-lg">
                <div class="mb-3">
                    <i class="bi bi-bell-slash text-primary display-4 bell-shake"></i>
                </div>
                <h4 class="fw-bold text-primary mb-2">No Notifications</h4>
                <p class="text-muted small mb-0">You’re fully up to date!</p>
            </div>
        <?php else: ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold text-primary">Your Notifications</h4>
                <?php if(Auth::user()->unreadNotifications->count() > 0): ?>
                    <a class="btn btn-sm btn-outline-success rounded-pill px-3" href="<?php echo e(route("notifications.readAll")); ?>">
                        <i class="bi bi-check-all me-1"></i> Mark all Read
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="list-group list-group-flush rounded-3 overflow-hidden shadow-sm">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = Auth::user()->notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-start <?php echo e(!$notification->read_at ? "bg-primary-subtle" : ""); ?> p-3">
                        <div class="me-auto ms-2">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="fw-bold <?php echo e(!$notification->read_at ? "text-primary" : "text-dark"); ?> mb-1">
                                    <?php echo e($notification->data["title"] ?? ($notification->data["type"] ?? "Notification")); ?>

                                </h6>
                                <small class="text-muted"
                                    style="font-size: 0.75rem;"><?php echo e($notification->created_at->diffForHumans()); ?></small>
                            </div>
                            <p class="small text-muted mb-1">
                                <?php echo e($notification->data["body"] ?? ($notification->data["message"] ?? "")); ?></p>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($notification->data["action_url"]) || isset($notification->data["action"])): ?>
                                <a class="btn btn-sm btn-link text-decoration-none small p-0"
                                    href="<?php echo e($notification->data["action_url"] ?? ($notification->data["action"] ?? "#")); ?>">
                                    View Details <i class="bi bi-arrow-right"></i>
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$notification->read_at): ?>
                            <form action="<?php echo e(route("notifications.read", $notification->id)); ?>" class="ms-3"
                                method="POST">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm btn-light text-primary rounded-circle shadow-sm"
                                    title="Mark as Read">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted ms-3"><i class="bi bi-check2-all"></i></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("notifications.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/notifications/index.blade.php ENDPATH**/ ?>