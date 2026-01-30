<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-4">
            <h2 class="h4 text-center text-primary mt-3">Quick Actions</h2>
            <div class="list-group shadow-sm mx-2">
                <button class="list-group-item list-group-item-action align-items-center" data-bs-toggle="modal"
                    data-bs-target="#addPharmacyModal">
                    <i class="bi bi-plus-circle me-2"></i> Add Pharmacy
                </button>
                <a href="<?php echo e(route('agent.packages', ['action' => 'index'])); ?>"
                    class="list-group-item list-group-item-action align-items-center">
                    <i class="bi bi-box-seam me-2"></i> Subscribe Package
                </a>
                <button class="list-group-item list-group-item-action align-items-center" data-bs-toggle="modal"
                    data-bs-target="#unreadMessagesModal">
                    <i class="bi bi-chat-dots me-2"></i> Reported Cases
                </button>
                <button class="list-group-item list-group-item-action align-items-center" data-bs-toggle="modal"
                    data-bs-target="#createConversationModal">
                    <i class="bi bi-exclamation-triangle me-2"></i> Report Case
                </button>
            </div>
        </div>
        

        
        <div class="col-md-8">
            <h2 class="h2 mt-2 text-center text-primary">Summary</h2>
            <div class="text-light mx-2">
                <div class="col-md-12 row gap-3 justify-content-center">
                    <div class="bg-primary col-3 rounded shadow-md p-2 text-center text-wrapper">
                        <h2 class="font-light small mb-2">All Pharmacies</h2>
                        <p class="text-white-700"><?php echo e($totalPharmacies); ?></p>
                    </div>
                    <div class="bg-secondary col-3  rounded shadow-md p-2 text-center text-wrapper">
                        <h2 class="font-semibold mb-2">Packages</h2>
                        <p class="text-white-700"><?php echo e($totalPackages); ?></p>
                    </div>
                    <div class="bg-danger col-3  rounded shadow-md p-2 text-center text-wrapper">
                        <h2 class="font-light small mb-2">Active Pharmacies</h2>
                        <p class="text-white-700"><?php echo e($activePharmacies); ?></p>
                    </div>
                    <div class="bg-success col-3  rounded shadow-md p-2 text-center text-wrapper">
                        <h2 class="font-semibold mb-2">New Messages</h2>
                        <p class="text-white-700"><?php echo e($totalMessages); ?></p>
                    </div>
                    <div class="bg-warning  col-3 text-dark rounded shadow-md p-2 text-center text-wrap">
                        <h2 class="font-semibold mb-2">Client's Cases</h2>
                        <p class="text-gray-700"><?php echo e($totalCases); ?></p>
                    </div>
                    <div class="bg-white col-3  text-dark rounded shadow-md p-2 text-center text-wrap">
                        <h2 class="font-light small  mb-2">Inactive Pharmacies</h2>
                        <p class="text-gray-700"><?php echo e($inactivePharmacies); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    
    <div class="row">
        <div class="col-md-6">
            <h2 class="h2 mt-2 text-center text-primary">Pharmacies Vs Income</h2>
            <div class="text-light mx-2 p-2">
                <canvas id="bar" width="400" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="h2 mt-2 text-center text-primary">Pharmacies Vs Income</h2>
            <div class="text-light mx-2 p-2">
                <canvas id="line" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Modal to create a new pharmacy -->
    <div class="modal fade" id="addPharmacyModal" tabindex="-1" aria-labelledby="addPharmacyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title  text-primary" id="addPharmacyModalLabel">Add a new pharmacy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('agent.pharmacies.store', ['action' => 'create'])); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php if (isset($component)) { $__componentOriginalb24df6adf99a77ed35057e476f61e153 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb24df6adf99a77ed35057e476f61e153 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.validation-errors','data' => ['class' => 'mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb24df6adf99a77ed35057e476f61e153)): ?>
<?php $attributes = $__attributesOriginalb24df6adf99a77ed35057e476f61e153; ?>
<?php unset($__attributesOriginalb24df6adf99a77ed35057e476f61e153); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb24df6adf99a77ed35057e476f61e153)): ?>
<?php $component = $__componentOriginalb24df6adf99a77ed35057e476f61e153; ?>
<?php unset($__componentOriginalb24df6adf99a77ed35057e476f61e153); ?>
<?php endif; ?>
                        <div class="row">
                            <div class="col-6">
                                <h2 class="h5 text-primary">Pharmacy Details</h2>
                                <div class="mb-3">
                                    <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'pharmacy_name','value' => 'Name','class' => 'form-label']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'pharmacy_name','value' => 'Name','class' => 'form-label']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $attributes = $__attributesOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__attributesOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $component = $__componentOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__componentOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'text','class' => 'form-control rounded','id' => 'pharmacy_name','name' => 'pharmacy_name','placeholder' => 'Pill Pharmacy','value' => old('pharmacy_name'),'required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','class' => 'form-control rounded','id' => 'pharmacy_name','name' => 'pharmacy_name','placeholder' => 'Pill Pharmacy','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('pharmacy_name')),'required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'location','class' => 'form-label','value' => 'Location']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'location','class' => 'form-label','value' => 'Location']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $attributes = $__attributesOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__attributesOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $component = $__componentOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__componentOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['type' => 'text','value' => old('location'),'class' => 'form-control rounded','id' => 'location','name' => 'location','placeholder' => 'Morogoro']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'text','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('location')),'class' => 'form-control rounded','id' => 'location','name' => 'location','placeholder' => 'Morogoro']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'status','class' => 'form-label','value' => 'Status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'status','class' => 'form-label','value' => 'Status']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $attributes = $__attributesOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__attributesOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $component = $__componentOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__componentOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
                                    <select class="form-select rounded" id="status" name="status" required>
                                        <option <?php echo e(old('status') == 'active' ? 'selected' : ''); ?> value="active">Active
                                        </option>
                                        <option <?php echo e(old('status') == 'inactive' ? 'selected' : ''); ?> value="inactive">
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <h2 class="h5 text-primary">Owner Details</h2>
                                <div>
                                    <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'name','value' => ''.e(__('Name')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'name','value' => ''.e(__('Name')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $attributes = $__attributesOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__attributesOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $component = $__componentOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__componentOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'name','class' => 'block mt-1 w-full','type' => 'text','name' => 'name','value' => old('name'),'required' => true,'autofocus' => true,'autocomplete' => 'name','placeholder' => 'Pill Point']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'name','class' => 'block mt-1 w-full','type' => 'text','name' => 'name','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('name')),'required' => true,'autofocus' => true,'autocomplete' => 'name','placeholder' => 'Pill Point']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                                </div>

                                <div class="mt-4">
                                    <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'email','value' => ''.e(__('Email')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'email','value' => ''.e(__('Email')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $attributes = $__attributesOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__attributesOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $component = $__componentOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__componentOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'email','class' => 'block mt-1 w-full','type' => 'email','name' => 'email','value' => old('email'),'required' => true,'autocomplete' => 'username','placeholder' => 'info@pillpoint.com']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'email','class' => 'block mt-1 w-full','type' => 'email','name' => 'email','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('email')),'required' => true,'autocomplete' => 'username','placeholder' => 'info@pillpoint.com']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                                </div>

                                <div class="mt-4">
                                    <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['for' => 'phone_number','value' => ''.e(__('Phone Number')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'phone_number','value' => ''.e(__('Phone Number')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $attributes = $__attributesOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__attributesOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald8ba2b4c22a13c55321e34443c386276)): ?>
<?php $component = $__componentOriginald8ba2b4c22a13c55321e34443c386276; ?>
<?php unset($__componentOriginald8ba2b4c22a13c55321e34443c386276); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input','data' => ['id' => 'phone_number','class' => 'block mt-1 w-full','type' => 'tel','name' => 'phone_number','value' => old('phone_number'),'required' => true,'placeholder' => '0742177328','autocomplete' => 'phone_number']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'phone_number','class' => 'block mt-1 w-full','type' => 'tel','name' => 'phone_number','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('phone_number')),'required' => true,'placeholder' => '0742177328','autocomplete' => 'phone_number']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $attributes = $__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__attributesOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1)): ?>
<?php $component = $__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1; ?>
<?php unset($__componentOriginalc2fcfa88dc54fee60e0757a7e0572df1); ?>
<?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-between">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Unread Messages Modal -->
    <div class="modal fade" id="unreadMessagesModal" tabindex="-1" aria-labelledby="unreadMessagesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="unreadMessagesModalLabel">
                        <i class="bi bi-envelope-open"></i> Unread Messages
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Bar -->
                    <div class="mb-3">
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Search conversations...">
                    </div>
                    <!-- Messages List -->
                    <div id="messagesContainer">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-primary" role="status" id="loadingSpinner">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Conversation Modal -->
    <div class="modal  fade" id="createConversationModal" tabindex="-1" aria-labelledby="createConversationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createConversationModalLabel">
                        <i class="bi bi-people me-2"></i> Create New Conversation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createConversationForm">
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="conversationTitle" class="form-label">Title</label>
                            <input type="text" class="rounded form-control" id="conversationTitle" name="title"
                                required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="conversationDescription" class="form-label">Description</label>
                            <textarea class="form-control summernote" id="conversationDescription" name="description" rows="3"></textarea>
                        </div>

                        <!-- Select Recipients -->
                        <div class="mb-3">
                            <label for="conversationRecipients" class="form-label fw-bold">
                                <i class="bi bi-people-fill text-primary"></i> Select Recipients
                            </label>

                            <!-- Search Input -->
                            <input type="text" class="form-control mb-2" id="searchRecipients"
                                placeholder="Search recipients...">

                            <!-- Checkbox List Container -->
                            <div id="recipientsList" class="border rounded p-2"
                                style="max-height: 100px; overflow-y: auto;">
                                <p class="text-muted text-center">Loading recipients...</p>
                            </div>

                            <small class="text-muted">Select users to add them to the conversation.</small>
                        </div>


                        <!-- Submit Button -->
                        <button type="submit" id="createConversationButton" class="btn btn-primary w-100">
                            <i class="bi bi-send"></i> Create Conversation
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="reportCaseModal" tabindex="-1" aria-labelledby="reportCaseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="reportCaseModalLabel"><i class="bi bi-exclamation-triangle me-2"></i>
                        Report a Concern</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                
                <div class="modal-body">
                    <form id="reportCaseForm" action="<?php echo e(route('agent.cases', ['action' => 'index'])); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php if (isset($component)) { $__componentOriginalb24df6adf99a77ed35057e476f61e153 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb24df6adf99a77ed35057e476f61e153 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.validation-errors','data' => ['class' => 'mb-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb24df6adf99a77ed35057e476f61e153)): ?>
<?php $attributes = $__attributesOriginalb24df6adf99a77ed35057e476f61e153; ?>
<?php unset($__attributesOriginalb24df6adf99a77ed35057e476f61e153); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb24df6adf99a77ed35057e476f61e153)): ?>
<?php $component = $__componentOriginalb24df6adf99a77ed35057e476f61e153; ?>
<?php unset($__componentOriginalb24df6adf99a77ed35057e476f61e153); ?>
<?php endif; ?>

                        
                        <div class="mb-3">
                            <label for="subject" class="form-label fw-bold">Subject <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded" id="subject" name="subject"
                                placeholder="Enter a short title for the issue" required>
                        </div>

                        
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control rounded" id="description" name="description" rows="4"
                                placeholder="Describe the issue in detail..." required></textarea>
                        </div>

                        
                        <div class="mb-3">
                            <label for="urgency" class="form-label fw-bold">Urgency Level <span
                                    class="text-danger">*</span></label>
                            <select class="form-select rounded" id="urgency" name="urgency" required>
                                <option value="low">Low - Minor Issue</option>
                                <option value="medium">Medium - Needs Attention</option>
                                <option value="high">High - Critical Problem</option>
                            </select>
                        </div>

                        
                        <div class="mb-3">
                            <label for="attachment" class="form-label fw-bold">Attach a Screenshot (Optional)</label>
                            <input type="file" class="form-control rounded" id="attachment" name="attachment"
                                accept="image/*">
                        </div>

                        
                        <div class="progress mt-3 d-none" id="uploadProgress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                style="width: 0%;" id="progressBar">Uploading...</div>
                        </div>

                        
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger"><i class="bi bi-send me-1"></i> Submit
                                Report</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    
    <script>
        document.getElementById("reportCaseForm").addEventListener("submit", function(event) {
            let fileInput = document.getElementById("attachment");
            let progressBar = document.getElementById("uploadProgress");
            let progressFill = document.getElementById("progressBar");

            if (fileInput.files.length > 0) {
                event.preventDefault();
                progressBar.classList.remove("d-none");

                let progress = 0;
                let interval = setInterval(() => {
                    progress += 10;
                    progressFill.style.width = progress + "%";

                    if (progress >= 100) {
                        clearInterval(interval);
                        event.target.submit();
                    }
                }, 300);
            }
        });
    </script>

    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const messagesContainer = document.getElementById("messagesContainer");
            const loadingSpinner = document.getElementById("loadingSpinner");
            const searchInput = document.getElementById("searchInput");
            let currentExpandedConversation = null;
            let currentReplyMessageId = null;

            function fetchUnreadMessages() {

                // loged user id
                var AuthUserId = "<?php echo e(Auth::user()->id); ?>";

                fetch("<?php echo e(route('agent.messages', ['action' => 'unread'])); ?>")
                    .then(response => response.json())
                    .then(data => {
                        loadingSpinner.style.display = "none";

                        if (data.length === 0) {
                            messagesContainer.innerHTML =
                                `<div class="alert alert-info text-center">No unread messages</div>`;
                            return;
                        }

                        // Catch error if exists
                        if (data.error) {
                            console.log(data.error);
                            throw new Error(data.error);
                        }

                        // Filter conversations based on search input
                        const searchQuery = searchInput.value.toLowerCase();
                        const filteredConversations = data.filter(conversation =>
                            conversation.title.toLowerCase().includes(searchQuery) ||
                            (conversation.description && conversation.description.toLowerCase().includes(
                                searchQuery))
                        );

                        messagesContainer.innerHTML = filteredConversations.map(conversation => `
                            <div class="card mb-3 shadow">
                                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="bi bi-chat-dots"></i> ${conversation.title}
                                    </h6>
                                    <button class="btn btn-sm btn-light toggle-btn" data-id="${conversation.id}">
                                        ${currentExpandedConversation === conversation.id ? 'Collapse' : 'Expand'}
                                    </button>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted">${conversation.description || 'No description available'}</p>

                                    <div id="messages-${conversation.id}" class="messages-container p-2 border rounded bg-light"
                                        style="max-height: 300px; overflow-y: auto; display: ${currentExpandedConversation === conversation.id ? 'block' : 'none'};">
                                        ${conversation.messages.map(message => `
                                                <div class="d-flex ${message.sender.id === AuthUserId ? 'justify-content-end' : 'justify-content-start'} mb-2">
                                                    <div class="p-2 rounded ${message.sender.id === AuthUserId ? 'bg-primary text-white' : 'bg-white border'}" style="max-width: 75%;">
                                                        <h6 class="mb-1 text-secondary">
                                                            <i class="bi bi-person-circle"></i> ${message.sender.name}
                                                        </h6>
                                                        <p class="mb-1">${message.content}</p>
                                                        <div class="d-flex justify-content-between">
                                                            <small class="text-muted">
                                                                <i class="bi bi-clock"></i> ${new Date(message.created_at).toLocaleString()}
                                                            </small>
                                                            <div>
                                                                <button class="btn btn-sm btn-outline-primary reply-btn" data-sender-name="${message.sender.name}" data-message-id="${message.id}" data-id="${conversation.id}">
                                                                    <i class="bi bi-arrow-repeat"></i> Reply
                                                                </button>
                                                                <button hidden class="btn btn-sm btn-outline-success mark-read" data-id="${message.id}">
                                                                    <i class="bi bi-check-circle"></i> Read
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            `).join('')}
                                    </div>

                                    <div id="reply-section-${conversation.id}" class="mt-3 d-none">
                                        <textarea class="form-control summernote" id="newMessage-${conversation.id}" placeholder="Write a comment..."></textarea>
                                        <div class="d-flex justify-content-end mt-2">
                                            <button class="btn btn-sm btn-primary send-btn" data-id="${conversation.id}">
                                                <i class="bi bi-send"></i> Send
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join("");


                        // Event listeners for "Mark as Read" buttons
                        document.querySelectorAll(".mark-read").forEach(button => {
                            button.addEventListener("click", function() {
                                markAsRead(this.getAttribute("data-id"));
                            });
                        });

                        // Event listener for expand/collapse buttons
                        document.querySelectorAll(".toggle-btn").forEach(button => {
                            button.addEventListener("click", function() {
                                const conversationId = this.getAttribute("data-id");
                                const messagesDiv = document.getElementById(
                                    `messages-${conversationId}`);
                                const replySection = document.getElementById(
                                    `reply-section-${conversationId}`);

                                if (currentExpandedConversation === conversationId) {
                                    messagesDiv.style.display = "none";
                                    replySection.style.display = "none";
                                    this.textContent = "Expand";
                                    currentExpandedConversation = null;
                                } else {
                                    // Collapse any open conversation
                                    document.querySelectorAll(".messages-container").forEach(
                                        div => div.style.display = "none");
                                    document.querySelectorAll(".toggle-btn").forEach(btn => btn
                                        .textContent = "Expand");

                                    // Expand the selected one
                                    messagesDiv.style.display = "block";
                                    this.textContent = "Collapse";
                                    currentExpandedConversation = conversationId;

                                    // Show reply section
                                    replySection.style.display = "block";
                                }
                            });
                        });

                        // Event listener for Reply buttons
                        document.querySelectorAll(".reply-btn").forEach(button => {
                            button.addEventListener("click", function() {
                                const conversationId = this.getAttribute("data-id");
                                currentReplyMessageId = $(this).data("message-id");
                                const currentReplyMessageSenderName = $(this).data(
                                    "sender-name");
                                const replySection = document.getElementById(
                                    `reply-section-${conversationId}`);
                                // append a button to create a new sms instead if is not there yet
                                if ($("#new-sms").length === 0) {
                                    $(replySection).append(`<button class="btn btn-sm btn-danger" id="new-sms" data-id="${conversationId}">
                                    <i class="bi bi-arrow-repeat"></i>new</button>`);
                                }
                                // event listener for new sms instead button
                                document.getElementById("new-sms").addEventListener("click",
                                    function() {
                                        const conversationId = this.getAttribute("data-id");
                                        // alert(conversationId);
                                        const replySectionInputField =
                                            `#newMessage-${conversationId}`;
                                        const replySection = document.getElementById(
                                            `reply-section-${conversationId}`);

                                        $(replySectionInputField).val("").focus();

                                        // Remove aria-hidden before interacting with the input
                                        if (replySection) {
                                            replySection.removeAttribute("aria-hidden");
                                        }

                                        currentReplyMessageId = null;
                                        currentReplyMessageSenderName = null;
                                    });

                                // append a reply name to the begining of the reply section
                                const replySectionInputField = `#newMessage-${conversationId}`;
                                $(replySectionInputField).val(
                                    `@${currentReplyMessageSenderName} - `
                                ).css({
                                    "background-color": "#e0f7fa", // Light blue background
                                    "font-weight": "bold"
                                }).focus();
                                replySection.style.display = "block";
                            });
                        });

                        // Event listener for Send buttons
                        document.querySelectorAll(".send-btn").forEach(button => {
                            button.addEventListener("click", function() {
                                const conversationId = this.getAttribute("data-id");
                                const messageContent = document.getElementById(
                                    `newMessage-${conversationId}`).value;

                                if (messageContent.trim() === "") {
                                    alert("Please enter a message.");
                                    return;
                                }
                                // check if  message is reply
                                if (currentReplyMessageId === null) {
                                    sendNewMessage(conversationId, messageContent);
                                }
                                if (currentReplyMessageId !== null) {
                                    sendReply(conversationId, currentReplyMessageId,
                                        messageContent);
                                }
                            });
                        });

                    })
                    .catch(error => {
                        console.error("Error fetching messages:", error);
                        messagesContainer.innerHTML =
                            `<div class="alert alert-danger text-center">Failed to load messages</div>`;
                    });
            }

            function markAsRead(id) {
                fetch(`<?php echo e(route('agent.messages', ['action' => 'markRead'])); ?>/${id}`, {
                        method: "POST"
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`button[data-id="${id}"]`).closest(".message").remove();
                            if (document.querySelectorAll(".message").length === 0) {
                                messagesContainer.innerHTML =
                                    `<div class="alert alert-info text-center">No unread messages</div>`;
                            }
                        }
                        if (data.error) {
                            console.log(data.error);
                            throw new Error(data.error);
                        }
                    })
                    .catch(error => console.error("Error marking message as read:", error));
            }

            // Function to send the reply
            function sendReply(conversationId, parentMessageId, messageContent) {
                fetch("<?php echo e(route('agent.messages', ['action' => 'sendReply'])); ?>", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            conversationId: conversationId,
                            parentMessageId: parentMessageId,
                            message: messageContent
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Reply sent successfully!");
                            document.getElementById(`newMessage-${conversationId}`).value =
                                ""; // Clear input
                            fetchUnreadMessages(); // Reload messages
                        } else {
                            alert("Failed to send reply: " + data.error);
                        }
                    })
                    .catch(error => console.error("Error sending reply:", error));
            }

            // Function to send a new message
            function sendNewMessage(conversationId, messageContent) {
                fetch("<?php echo e(route('agent.messages', ['action' => 'sendMessage'])); ?>", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            conversation_id: conversationId,
                            content: messageContent
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            document.getElementById(`newMessage-${conversationId}`).value =
                                ""; // Clear input
                            fetchUnreadMessages(); // Reload messages
                        } else {
                            alert("Failed to send comment: " + data.error);
                        }
                    })
                    .catch(error => console.error("Error sending comment:", error));
            }

            // Search filter event listener
            searchInput.addEventListener("input", fetchUnreadMessages);

            // Fetch messages when the modal is shown
            document.getElementById("unreadMessagesModal").addEventListener("show.bs.modal",
                fetchUnreadMessages);
        });
    </script>

    
    <script>
        $(document).ready(function() {
            // Fetch and Populate Recipients Dynamically
            // Open modal and fetch recipients
            $("#createConversationModal").on("show.bs.modal", function() {
                const recipientsList = $("#recipientsList");
                recipientsList.html('<p class="text-muted text-center">Loading recipients...</p>');

                $.ajax({
                    url: "<?php echo e(route('agent.messages', ['action' => 'getRecipients'])); ?>",
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        // Populate checkboxes
                        recipientsList.html(
                            data.map(user => `
                        <div class="form-check">
                            <input class="form-check-input recipient-checkbox" type="checkbox" 
                                   name="recipients[]" value="${user.id}" id="recipient-${user.id}">
                            <label class="form-check-label" for="recipient-${user.id}">
                                ${user.name}
                            </label>
                        </div>
                    `).join("")
                        );
                    },
                    error: function() {
                        recipientsList.html(
                            '<p class="text-danger text-center">Failed to load users</p>');
                    }
                });
            });

            // Live search functionality
            $("#searchRecipients").on("keyup", function() {
                const searchText = $(this).val().toLowerCase();
                $(".recipient-checkbox").each(function() {
                    const label = $(this).next("label").text().toLowerCase();
                    $(this).closest(".form-check").toggle(label.includes(searchText));
                });
            });

            //  Handle Form Submission for Creating Conversations
            $("#createConversationForm").on("submit", function(event) {
                event.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: "<?php echo e(route('agent.messages', ['action' => 'createConversation'])); ?>", // Update with actual route
                    type: "POST",
                    data: formData,
                    processData: false, // Prevent jQuery from processing data
                    contentType: false, // Prevent jQuery from setting content type
                    headers: {
                        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                    },
                    success: function(data) {
                        if (data.success) {
                            alert("Conversation created successfully!");
                            $("#createConversationForm")[0].reset(); // Reset form
                            // refresh the page
                            window.location.reload();
                            $("#recipientsList").html(""); // Clear recipients list

                            // Close modal
                            $("#createConversationModal").modal("hide");
                        } else {
                            alert("Failed to create conversation: " + data.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        // refresh the page
                        window.location.reload();
                        console.error("Error creating conversation:", error);
                        alert("An error occurred. Please try again.");
                    }
                });
            });
        });
    </script>

    
    <script>
        $(document).ready(function() {
            // Sample data for pharmacies and incomes
            var ctxbar = $('#bar')[0].getContext('2d');
            var ctxline = $('#line')[0].getContext('2d');

            data = [2200, 2900, 4000, 6000, 3000];
            labels = ['June', 'July', 'Aug', 'Sept', 'Nov'];

            var bar = new Chart(ctxbar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Transactions',
                        data: data,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });

            // convert above to line graph
            var line = new Chart(ctxline, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Transactions',
                        data: data,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('agent.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/agent/index.blade.php ENDPATH**/ ?>