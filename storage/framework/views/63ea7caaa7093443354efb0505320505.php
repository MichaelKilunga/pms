<?php $__env->startSection('content'); ?>
    <div class="container">

        <div class="d-flex justify-content-between mt-4">
            <h1 class="h5 text-primary">Manage Pharmacies</h1>
            <a class="btn btn-success m-1" data-bs-target="#addPharmacyModal" data-bs-toggle="modal" href="#">Add
                Pharmacy</a>
        </div>

        <div class="table-responsive mt-4">
            <table class="table-striped table" id="Table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Owner</th>
                        <th>Package</th>
                        <th>Remain</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pharmacies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pharmacy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($pharmacy->name); ?></td>
                            <td><?php echo e($pharmacy->location); ?></td>
                            <td><?php echo e($pharmacy->owner->name); ?></td>
                            <td><?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->name : 'No Package'); ?>

                            </td>
                            <td><small class="text-danger smaller countdown"
                                    id="countdown<?php echo e($pharmacy->id); ?>"><?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->end_date : 'No package'); ?></small>
                            </td>
                            <td><?php echo e($pharmacy->status); ?></td>
                            <td>
                                <a class="btn btn-primary btn-sm" data-bs-target="#viewPharmacy<?php echo e($pharmacy->id); ?>"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-eye"></i></a>
                                
                                <div aria-hidden="true" aria-labelledby="viewPharmacy<?php echo e($pharmacy->id); ?>Label"
                                    class="modal fade" id="viewPharmacy<?php echo e($pharmacy->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-primary"
                                                    id="viewPharmacy<?php echo e($pharmacy->id); ?>Label"><?php echo e($pharmacy->name); ?></h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-6 border-right border-danger">
                                                        <h2 class="h5 text-primary">Pharmacy Details</h2>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Name</h5>
                                                            <p class="text-secondary"><?php echo e($pharmacy->name); ?></p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Location</h5>
                                                            <p class="text-secondary"><?php echo e($pharmacy->location); ?></p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Status</h5>
                                                            <p class="text-secondary"><?php echo e($pharmacy->status); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <h2 class="h5 text-primary">Owner Details</h2>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Name</h5>
                                                            <p class="text-secondary"><?php echo e($pharmacy->owner->name); ?></p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Email</h5>
                                                            <p class="text-secondary"><?php echo e($pharmacy->owner->email); ?></p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Phone</h5>
                                                            <p class="text-secondary"><?php echo e($pharmacy->owner->phone); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row border-top border-danger">
                                                    <h2 class="h5 text-primary mt-3">Package Details</h2>
                                                    <div class="d-flex">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pharmacy->owner->ownerCurrentContract): ?>
                                                            <div class="row">
                                                                <div class="mb-3">
                                                                    <h5 class="text-primary">Name</h5>
                                                                    <p class="text-secondary">
                                                                        <?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->name : 'No Package'); ?>

                                                                    </p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <h5 class="text-primary">Duration</h5>
                                                                    <p class="text-secondary">
                                                                        <?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->duration : 'No Package'); ?>

                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="mb-3">
                                                                    <h5 class="text-primary">Price</h5>
                                                                    <p class="text-secondary">
                                                                        <?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->price : 'No Package'); ?>

                                                                    </p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <h5 class="text-primary">Start Date</h5>
                                                                    <p class="text-secondary">
                                                                        <?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->start_date : 'No Package'); ?>

                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <h5 class="text-primary">End Date</h5>
                                                                <p class="text-secondary">
                                                                    <?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->end_date : 'No Package'); ?>

                                                                </p>
                                                            </div>
                                                        <?php else: ?>
                                                            <p class="text-secondary">No Package</p>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal"
                                                    type="button">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a class="btn btn-secondary btn-sm" data-bs-target="#editPharmacy<?php echo e($pharmacy->id); ?>"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-pencil"></i></a>
                                
                                <div aria-hidden="true" aria-labelledby="editPharmacy<?php echo e($pharmacy->id); ?>Label"
                                    class="modal fade" id="editPharmacy<?php echo e($pharmacy->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-primary"
                                                    id="editPharmacy<?php echo e($pharmacy->id); ?>Label">Edit Pharmacy</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form
                                                    action="<?php echo e(route('agent.pharmacies.update', ['id' => $pharmacy->id, 'action' => 'update'])); ?>"
                                                    method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['class' => 'form-label','for' => 'pharmacy_name','value' => 'Name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'form-label','for' => 'pharmacy_name','value' => 'Name']); ?>
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
                                                                <x-input :value="old("pharmacy_name") ??
                                                                    $pharmacy->name" class="form-control rounded"
                                                                    id="pharmacy_name" name="pharmacy_name"
                                                                    placeholder="Pill Pharmacy" required type="text" />
                                                            </div>
                                                            <div class="mb-3">
                                                                <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['class' => 'form-label','for' => 'location','value' => 'Location']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'form-label','for' => 'location','value' => 'Location']); ?>
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
                                                                <x-input :value="old("location") ?? $pharmacy->location"
                                                                    class="form-control rounded"
                                                                    id="location" name="location" placeholder="Morogoro"
                                                                    type="text" />
                                                            </div>
                                                            <div class="mb-3">
                                                                <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['class' => 'form-label','for' => 'status','value' => 'Status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'form-label','for' => 'status','value' => 'Status']); ?>
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
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['class' => 'form-label','for' => 'agent_extra_charge','value' => 'Agent Extra Charge (Top-up)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'form-label','for' => 'agent_extra_charge','value' => 'Agent Extra Charge (Top-up)']); ?>
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
                                                                <x-input :value="old("agent_extra_charge") ??
                                                                    $pharmacy->agent_extra_charge"
                                                                    class="form-control rounded"
                                                                    id="agent_extra_charge" name="agent_extra_charge"
                                                                    placeholder="0.00" step="0.01" type="number" />
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <h2 class="h5 text-primary">Owner Details</h2>
                                                            <div class="mb-3">
                                                                <h5 class="text-primary">Name</h5>
                                                                <p class="text-secondary"><?php echo e($pharmacy->owner->name); ?></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <h5 class="text-primary">Email</h5>
                                                                <p class="text-secondary"><?php echo e($pharmacy->owner->email); ?>

                                                                </p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <h5 class="text-primary">Phone</h5>
                                                                <p class="text-secondary"><?php echo e($pharmacy->owner->phone); ?>

                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row border-top border-danger">
                                                        <h2 class="h5 text-primary mt-3">Package Details</h2>
                                                        <div class="d-flex">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pharmacy->owner->ownerCurrentContract): ?>
                                                                <div class="row">
                                                                    <div class="mb-3">
                                                                        <h5 class="text-primary">Name</h5>
                                                                        <p class="text-secondary">
                                                                            <?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->name : 'No Package'); ?>

                                                                        </p>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <h5 class="text-primary">Duration</h5>
                                                                        <p class="text-secondary">
                                                                            <?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->duration : 'No Package'); ?>

                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="mb-3">
                                                                        <h5 class="text-primary">Price</h5>
                                                                        <p class="text-secondary">
                                                                            <?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->price : 'No Package'); ?>

                                                                        </p>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <h5 class="text-primary">Start Date</h5>
                                                                        <p class="text-secondary">
                                                                            <?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->start_date : 'No Package'); ?>

                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <h5 class="text-primary">End Date</h5>
                                                                    <p class="text-secondary">
                                                                        <?php echo e($pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->end_date : 'No Package'); ?>

                                                                    </p>
                                                                </div>
                                                            <?php else: ?>
                                                                <p class="text-secondary">No Package</p>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal"
                                                            type="button">Close</button>
                                                        <button class="btn btn-primary" type="submit">Save
                                                            changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form
                                    action="<?php echo e(route('agent.pharmacies.destroy', ['id' => $pharmacy->id, 'action' => 'delete'])); ?>"
                                    method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button  class="btn btn-danger btn-sm" type="submit"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal to create a new pharmacy -->
    <div aria-hidden="true" aria-labelledby="addPharmacyModalLabel" class="modal fade" id="addPharmacyModal"
        tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="addPharmacyModalLabel">Add a new pharmacy</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['class' => 'form-label','for' => 'pharmacy_name','value' => 'Name']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'form-label','for' => 'pharmacy_name','value' => 'Name']); ?>
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
                                    <input :value="old("pharmacy_name")" class="form-control rounded"
                                        id="pharmacy_name" name="pharmacy_name" placeholder="Pill Pharmacy" required
                                        type="text" />
                                </div>
                                <div class="mb-3">
                                    <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['class' => 'form-label','for' => 'location','value' => 'Location']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'form-label','for' => 'location','value' => 'Location']); ?>
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
                                    <input :value="old("location")" class="form-control rounded" id="location"
                                        name="location" placeholder="Morogoro" type="text" />
                                </div>
                                <div class="mb-3">
                                    <?php if (isset($component)) { $__componentOriginald8ba2b4c22a13c55321e34443c386276 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald8ba2b4c22a13c55321e34443c386276 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.label','data' => ['class' => 'form-label','for' => 'status','value' => 'Status']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'form-label','for' => 'status','value' => 'Status']); ?>
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
                                    <input :value="old("name")" autocomplete="name" autofocus
                                        class="form-control rounded mt-1 block w-full" id="name" name="name"
                                        placeholder="Pill Point" required type="text" />
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
                                    <input :value="old("email")" autocomplete="username"
                                        class="form-control rounded mt-1 block w-full" id="email" name="email"
                                        placeholder="info@pillpoint.com" required type="email" />
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
                                    <input :value="old("phone_number")" autocomplete="phone_number"
                                        class="form-control rounded mt-1 block w-full" id="phone_number"
                                        name="phone_number" placeholder="0742177328" required type="tel" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-between">
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            console.log('Countdown script running.');

            $('.countdown').each(function() {
                var countdownElement = $(this);
                var endDateString = countdownElement.text().trim();

                if (endDateString === 'No package') {
                    countdownElement.text('No package');
                    return;
                }

                var countDownDate = new Date(endDateString).getTime();

                if (isNaN(countDownDate)) {
                    console.error('Invalid date:', endDateString);
                    countdownElement.text('Invalid date');
                    return;
                }

                var interval = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = countDownDate - now;

                    if (distance < 0) {
                        clearInterval(interval);
                        countdownElement.text('EXPIRED').addClass('text-danger fw-bold');
                        return;
                    }

                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    countdownElement.text(`${days}d ${hours}h ${minutes}m ${seconds}s`);
                }, 1000);
            });
        });
    </script>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <script>
            $(document).ready(function() {
                $('#addPharmacyModal').modal('show');
            });
        </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('agent.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/agent/pharmacies.blade.php ENDPATH**/ ?>