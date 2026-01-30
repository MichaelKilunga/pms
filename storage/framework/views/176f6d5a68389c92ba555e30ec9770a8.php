<?php $__env->startSection('content'); ?>
    <div class="container-fluid px-4 py-4">

        <div class="row align-items-center mb-4">
            <div class="col">
                <h1 class="h3 text-primary fw-bold mb-0">Subscription & Pricing Management</h1>
                <p class="text-muted small">Manage global pricing strategies and subscription packages.</p>
            </div>
            <div class="col-auto">
                <a class="btn btn-primary rounded-pill shadow-sm" href="<?php echo e(route('packages.create')); ?>">
                    <i class="bi bi-plus-lg me-1"></i> New Package
                </a>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-fill border-bottom-0 mb-4" id="pricingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button aria-controls="settings" aria-selected="true" class="nav-link active fw-bold rounded-top-3"
                    data-bs-target="#settings" data-bs-toggle="tab" id="settings-tab" role="tab" type="button">
                    <i class="bi bi-sliders me-2"></i> Global Configuration
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button aria-controls="packages" aria-selected="false" class="nav-link fw-bold rounded-top-3"
                    data-bs-target="#packages" data-bs-toggle="tab" id="packages-tab" role="tab" type="button">
                    <i class="bi bi-box-seam me-2"></i> Package Definitions
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pricingTabsContent">

            <!-- Tab 1: Global Settings -->
            <div aria-labelledby="settings-tab" class="tab-pane fade show active" id="settings" role="tabpanel">
                <div class="card rounded-4 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="<?php echo e(route('packages.settings.update')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="row g-4">
                                <!-- Pricing Mode Selection -->
                                <div class="col-md-12">
                                    <h5 class="fw-bold text-dark mb-3">Default Pricing Strategy</h5>
                                    <div class="row">
                                        <?php $currentMode = $settings['pricing_mode']->value ?? 'standard'; ?>

                                        <div class="col-md-4">
                                            <div class="form-check custom-card-radio">
                                                <input <?php echo e($currentMode == 'standard' ? 'checked' : ''); ?>

                                                    class="form-check-input" id="modeStandard" name="pricing_mode"
                                                    type="radio" value="standard">
                                                <label
                                                    class="form-check-label card h-100 <?php echo e($currentMode == 'standard' ? 'border-primary bg-light' : ''); ?> border-2 p-3"
                                                    for="modeStandard">
                                                    <div class="fw-bold"><i class="bi bi-distribute-vertical me-2"></i>
                                                        Standard Packages</div>
                                                    <small class="text-muted mt-2">Fixed price packages (e.g., Bronze,
                                                        Silver, Gold). Best for simple, tiered offerings.</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-check custom-card-radio">
                                                <input <?php echo e($currentMode == 'dynamic' ? 'checked' : ''); ?>

                                                    class="form-check-input" id="modeDynamic" name="pricing_mode"
                                                    type="radio" value="dynamic">
                                                <label
                                                    class="form-check-label card h-100 <?php echo e($currentMode == 'dynamic' ? 'border-primary bg-light' : ''); ?> border-2 p-3"
                                                    for="modeDynamic">
                                                    <div class="fw-bold"><i class="bi bi-calculator me-2"></i> Dynamic
                                                        (Item-Based)</div>
                                                    <small class="text-muted mt-2">Price scales with inventory size (e.g.,
                                                        items / 500 * Rate).</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-check custom-card-radio">
                                                <input <?php echo e($currentMode == 'profit_share' ? 'checked' : ''); ?>

                                                    class="form-check-input" id="modeProfit" name="pricing_mode"
                                                    type="radio" value="profit_share">
                                                <label
                                                    class="form-check-label card h-100 <?php echo e($currentMode == 'profit_share' ? 'border-primary bg-light' : ''); ?> border-2 p-3"
                                                    for="modeProfit">
                                                    <div class="fw-bold"><i class="bi bi-graph-up-arrow me-2"></i> Profit
                                                        Share</div>
                                                    <small class="text-muted mt-2">Charge a percentage of monthly realized
                                                        profit.</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <!-- Configuration Inputs -->
                                <div class="col-md-6">
                                    <h5 class="fw-bold text-dark mb-3">Dynamic Pricing Parameters</h5>
                                    <div class="card bg-light border-0 p-3">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-bold small text-uppercase text-muted">Rate per
                                                    Item</label>
                                                <div class="input-group">
                                                    <span class="input-group-text border-end-0 bg-white">TZS</span>
                                                    <input class="form-control border-start-0" name="dynamic_rate_per_item"
                                                        type="number"
                                                        value="<?php echo e($settings['dynamic_rate_per_item']->value ?? 100); ?>">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-bold small text-uppercase text-muted">Rate per
                                                    Staff</label>
                                                <div class="input-group">
                                                    <span class="input-group-text border-end-0 bg-white">TZS</span>
                                                    <input class="form-control border-start-0"
                                                        name="dynamic_rate_per_staff" type="number"
                                                        value="<?php echo e($settings['dynamic_rate_per_staff']->value ?? 5000); ?>">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-bold small text-uppercase text-muted">Rate per
                                                    Branch</label>
                                                <div class="input-group">
                                                    <span class="input-group-text border-end-0 bg-white">TZS</span>
                                                    <input class="form-control border-start-0"
                                                        name="dynamic_rate_per_branch" type="number"
                                                        value="<?php echo e($settings['dynamic_rate_per_branch']->value ?? 20000); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="fw-bold text-dark mb-3">Profit Share Parameters</h5>
                                    <div class="card bg-light border-0 p-3">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-uppercase text-muted">Profit Share
                                                Percentage</label>
                                            <div class="input-group">
                                                <input class="form-control border-end-0" name="profit_share_percentage"
                                                    step="0.01" type="number"
                                                    value="<?php echo e($settings['profit_share_percentage']->value ?? 25); ?>">
                                                <span class="input-group-text border-start-0 bg-white">%</span>
                                            </div>
                                            <div class="form-text">Percentage of profit to charge (e.g., 25%).</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 text-end">
                                    <button class="btn btn-success rounded-pill fw-bold px-5 shadow" type="submit">
                                        <i class="bi bi-check-circle me-2"></i> Save Configuration
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Packages List -->
            <div aria-labelledby="packages-tab" class="tab-pane fade" id="packages" role="tabpanel">
                <div class="card rounded-4 border-0 shadow-sm">
                    <div class="card-body overflow-hidden p-0">
                        <div class="table-responsive">
                            <table class="table-hover small mb-0 table align-middle" id="Table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-uppercase small fw-bold text-muted py-3 ps-4">Name</th>
                                        <th class="text-uppercase small fw-bold text-muted py-3">Details</th>
                                        <th class="text-uppercase small fw-bold text-muted py-3">Duration</th>
                                        <th class="text-uppercase small fw-bold text-muted py-3">Status</th>
                                        <th class="text-uppercase small fw-bold text-muted py-3 pe-4 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-bold text-dark"><?php echo e($package->name); ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-primary">TZS
                                                        <?php echo e(number_format($package->price)); ?></span>
                                                    <small class="text-muted">Standard Price</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-info text-dark rounded-pill px-3"><?php echo e($package->duration); ?>

                                                    Days</span>
                                            </td>
                                            <td>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($package->status): ?>
                                                    <span
                                                        class="badge bg-success-subtle text-success border-success-subtle rounded-pill border">Active</span>
                                                <?php else: ?>
                                                    <span
                                                        class="badge bg-danger-subtle text-danger border-danger-subtle rounded-pill border">Inactive</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="btn-group">
                                                    <a class="btn btn-sm btn-outline-secondary rounded-start-pill"
                                                        href="<?php echo e(route('packages.edit', $package->id)); ?>" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a class="btn btn-sm btn-outline-secondary"
                                                        href="<?php echo e(route('packages.show', $package->id)); ?>" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('packages.destroy', $package->id)); ?>"
                                                        class="d-inline" method="POST"
                                                        onsubmit="return confirm('Are you sure?');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button class="btn btn-sm btn-outline-danger rounded-end-pill"
                                                            title="Delete" type="submit">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-card-radio input:checked+label {
            border-color: var(--bs-primary) !important;
            background-color: var(--bs-primary-bg-subtle) !important;
        }

        .custom-card-radio input {
            display: none;
        }

        .custom-card-radio label {
            cursor: pointer;
            transition: all 0.2s;
        }

        .custom-card-radio label:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('packages.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/packages/index.blade.php ENDPATH**/ ?>