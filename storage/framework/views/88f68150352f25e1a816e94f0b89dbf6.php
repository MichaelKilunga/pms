<?php $__env->startSection("content"); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">Global Notification Settings</h3>
                </div>
                <form action="<?php echo e(route("superAdmin.notifications.update")); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session("success")): ?>
                            <div class="alert alert-success"><?php echo e(session("success")); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="row">
                            <!-- WhatsApp Configuration -->
                            <div class="col-md-6">
                                <h4 class="text-primary mb-4">WhatsApp Configuration (Meta Cloud API)</h4>

                                <div class="form-group mb-3">
                                    <div class="custom-control custom-switch">
                                        <input
                                            <?php echo e(filter_var($settings["whatsapp_enabled"] ?? false, FILTER_VALIDATE_BOOLEAN) ? "checked" : ""); ?>

                                            class="custom-control-input" id="whatsapp_enabled" name="whatsapp_enabled"
                                            type="checkbox" value="true">
                                        <label class="custom-control-label" for="whatsapp_enabled">Enable WhatsApp
                                            Integration</label>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="whatsapp_phone_number_id">Phone Number ID</label>
                                    <input class="form-control" name="whatsapp_phone_number_id"
                                        placeholder="Enter Phone Number ID" type="text"
                                        value="<?php echo e($settings["whatsapp_phone_number_id"] ?? ""); ?>">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="whatsapp_business_account_id">Business Account ID</label>
                                    <input class="form-control" name="whatsapp_business_account_id"
                                        placeholder="Enter Business Account ID" type="text"
                                        value="<?php echo e($settings["whatsapp_business_account_id"] ?? ""); ?>">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="whatsapp_access_token">Access Token</label>
                                    <input class="form-control" name="whatsapp_access_token"
                                        placeholder="Enter Permanent Access Token" type="password"
                                        value="<?php echo e($settings["whatsapp_access_token"] ?? ""); ?>">
                                </div>
                            </div>

                            <!-- SMS Configuration -->
                            <div class="col-md-6">
                                <h4 class="text-primary mb-4">SMS Configuration (Skypush)</h4>

                                <div class="form-group mb-3">
                                    <div class="custom-control custom-switch">
                                        <input
                                            <?php echo e(filter_var($settings["sms_enabled"] ?? false, FILTER_VALIDATE_BOOLEAN) ? "checked" : ""); ?>

                                            class="custom-control-input" id="sms_enabled" name="sms_enabled" type="checkbox"
                                            value="true">
                                        <label class="custom-control-label" for="sms_enabled">Enable SMS Integration</label>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="sms_sender_id">Sender ID</label>
                                    <input class="form-control" name="sms_sender_id" placeholder="e.g. PILPOINTONE"
                                        type="text" value="<?php echo e($settings["sms_sender_id"] ?? ""); ?>">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="sms_api_key">API Key</label>
                                    <input class="form-control" name="sms_api_key" placeholder="Enter Skypush API Key"
                                        type="password" value="<?php echo e($settings["sms_api_key"] ?? ""); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/superAdmin/settings/notification.blade.php ENDPATH**/ ?>