<?php $__env->startSection("content"); ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">Notification Preferences: <?php echo e($user->name); ?></h3>
                    <div class="card-toolbar">
                        <a class="btn btn-sm btn-secondary" href="<?php echo e(route("superadmin.users")); ?>">
                            <i class="bi bi-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>
                <form action="<?php echo e(route("superAdmin.users.notifications.update", $user->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session("success")): ?>
                            <div class="alert alert-success"><?php echo e(session("success")); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <p class="text-muted mb-4">Control which notification channels are enabled for this user. If a
                            channel is disabled here, the user will not receive notifications via that channel regardless of
                            system-wide settings.</p>

                        <div class="form-group mb-3">
                            <div class="custom-control custom-switch">
                                <input <?php echo e($user->wantsNotificationChannel("whatsapp") ? "checked" : ""); ?>

                                    class="custom-control-input" id="whatsapp" name="whatsapp" type="checkbox"
                                    value="true">
                                <label class="custom-control-label" for="whatsapp">Enable WhatsApp Notifications</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="custom-control custom-switch">
                                <input <?php echo e($user->wantsNotificationChannel("sms") ? "checked" : ""); ?>

                                    class="custom-control-input" id="sms" name="sms" type="checkbox"
                                    value="true">
                                <label class="custom-control-label" for="sms">Enable SMS Notifications</label>
                            </div>
                        </div>

                        
                        <div class="form-group mb-3">
                            <div class="custom-control custom-switch">
                                <input <?php echo e($user->wantsNotificationChannel("mail") ? "checked" : ""); ?>

                                    class="custom-control-input" id="mail" name="mail" type="checkbox"
                                    value="true">
                                <label class="custom-control-label" for="mail">Enable Email Notifications</label>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="submit">Update Preferences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/superAdmin/users/notifications.blade.php ENDPATH**/ ?>