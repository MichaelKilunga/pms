<?php $__env->startSection("content"); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">System Configuration</h5>
                    </div>
                    <div class="card-body">
                        <?php if(session("success")): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo e(session("success")); ?>

                                <button aria-label="Close" class="btn-close" data-bs-dismiss="alert" type="button"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route("admin.settings.system.update")); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="mb-4">
                                <h6 class="text-primary border-bottom pb-2">Pricing Module</h6>

                                <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-3">
                                        <label class="form-label text-capitalize" for="<?php echo e($setting->key); ?>">
                                            <?php echo e(str_replace("_", " ", $setting->key)); ?>

                                        </label>

                                        <?php if($setting->key === "pricing_mode"): ?>
                                            <select class="form-select" id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>">
                                                <option <?php echo e($setting->value === "standard" ? "selected" : ""); ?>

                                                    value="standard">Standard (Package Based)</option>
                                                <option <?php echo e($setting->value === "dynamic" ? "selected" : ""); ?>

                                                    value="dynamic">Dynamic (Item Based)</option>
                                            </select>
                                        <?php elseif($setting->key === "profit_share_percentage"): ?>
                                            <div class="input-group">
                                                <input class="form-control" id="<?php echo e($setting->key); ?>"
                                                    name="<?php echo e($setting->key); ?>" step="0.01" type="number"
                                                    value="<?php echo e($setting->value); ?>">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        <?php else: ?>
                                            <input class="form-control" id="<?php echo e($setting->key); ?>"
                                                name="<?php echo e($setting->key); ?>" type="text" value="<?php echo e($setting->value); ?>">
                                        <?php endif; ?>
                                        <?php if($setting->description): ?>
                                            <div class="form-text text-muted"><?php echo e($setting->description); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="mb-5">
                                <h6 class="text-primary border-bottom pb-2">Resource Upgrade Rates (Per Month)</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label small fw-bold">Rate Per Branch</label>
                                        <div class="input-group">
                                            <span class="input-group-text">TZS</span>
                                            <input class="form-control" name="dynamic_rate_per_branch" type="number" 
                                                value="<?php echo e($settings['dynamic_rate_per_branch']['value'] ?? 20000); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label small fw-bold">Rate Per Staff</label>
                                        <div class="input-group">
                                            <span class="input-group-text">TZS</span>
                                            <input class="form-control" name="dynamic_rate_per_staff" type="number" 
                                                value="<?php echo e($settings['dynamic_rate_per_staff']['value'] ?? 5000); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label small fw-bold">Rate Per Item/Medicine</label>
                                        <div class="input-group">
                                            <span class="input-group-text">TZS</span>
                                            <input class="form-control" name="dynamic_rate_per_item" type="number" 
                                                value="<?php echo e($settings['dynamic_rate_per_item']['value'] ?? 100); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5">
                                <h6 class="text-primary border-bottom pb-2">Feature Add-on Rates (Monthly)</h6>
                                <div class="row">
                                    <?php
                                        $featureKeys = [
                                            'upgrade_rate_whatsapp' => 'WhatsApp Alerts',
                                            'upgrade_rate_sms' => 'SMS Alerts',
                                            'upgrade_rate_reports' => 'Advanced Reports',
                                            'upgrade_rate_stock_management' => 'Stock Management',
                                            'upgrade_rate_stock_transfer' => 'Stock Transfer',
                                            'upgrade_rate_staff_management' => 'Staff Management',
                                            'upgrade_rate_receipts' => 'Receipts Generation',
                                            'upgrade_rate_analytics' => 'Business Analytics',
                                        ];
                                    ?>
                                    <?php $__currentLoopData = $featureKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small fw-bold"><?php echo e($label); ?></label>
                                            <div class="input-group">
                                                <span class="input-group-text">TZS</span>
                                                <input class="form-control" name="<?php echo e($key); ?>" type="number" 
                                                    value="<?php echo e($settings[$key]['value'] ?? 0); ?>">
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-primary border-bottom pb-2">WhatsApp Configuration (Meta Cloud API)</h6>

                                <div class="mb-3">
                                    <label class="form-label" for="whatsapp_enabled">Enable WhatsApp Integration</label>
                                    <select class="form-select" id="whatsapp_enabled" name="whatsapp_enabled">
                                        <option
                                            <?php echo e(($settings["whatsapp_enabled"]["value"] ?? "false") === "false" ? "selected" : ""); ?>

                                            value="false">Disabled</option>
                                        <option
                                            <?php echo e(($settings["whatsapp_enabled"]["value"] ?? "false") === "true" ? "selected" : ""); ?>

                                            value="true">Enabled</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="whatsapp_phone_number_id">Phone Number ID</label>
                                    <input class="form-control" id="whatsapp_phone_number_id"
                                        name="whatsapp_phone_number_id" type="text"
                                        value="<?php echo e($settings["whatsapp_phone_number_id"]["value"] ?? ""); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="whatsapp_business_account_id">Business Account ID</label>
                                    <input class="form-control" id="whatsapp_business_account_id"
                                        name="whatsapp_business_account_id" type="text"
                                        value="<?php echo e($settings["whatsapp_business_account_id"]["value"] ?? ""); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="whatsapp_access_token">Access Token</label>
                                    <input class="form-control" id="whatsapp_access_token" name="whatsapp_access_token"
                                        type="password" value="<?php echo e($settings["whatsapp_access_token"]["value"] ?? ""); ?>">
                                    <div class="form-text">Permanent user access token or system user token recommended.
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button class="btn btn-primary px-5 rounded-pill shadow-sm" type="submit">
                                        <i class="fas fa-save me-2"></i> Save Changes
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /media/michaelkilunga/C/SKYLINK/pms/resources/views/admin/settings/system.blade.php ENDPATH**/ ?>