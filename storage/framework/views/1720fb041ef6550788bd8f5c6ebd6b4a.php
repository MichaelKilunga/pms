<?php $__env->startSection("content"); ?>

    <div class="container my-4">

        
        <div class="row g-4">

            
            <div class="col-md-4">
                <div class="card rounded-3 border-0 shadow">
                    <div class="card-body">

                        <h4 class="text-primary fw-bold mb-4 text-center">
                            <i class="fas fa-bolt"></i> Current Plan
                        </h4>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contracts->count() > 0): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contract->is_current_contract): ?>
                                    <div class="bg-light mb-3 rounded border p-3 shadow-sm">
                                        <h5 class="fw-bold text-primary">
                                            <i class="fas fa-layer-group"></i> <?php echo e($contract->package->name); ?>

                                        </h5>
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!session("agent")): ?>
                                        <p class="mb-1">
                                            <strong>Amount Paid:</strong>
                                            <span class="text-success fw-bold">
                                                TZS
                                                <?php echo e(number_format((\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30) * $contract->package->price)); ?>

                                            </span>
                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <p class="mb-1"><strong>Package Price:</strong> TZS
                                        <?php echo e(number_format($contract->package->price)); ?> / <?php echo e($contract->package->duration); ?>

                                        days</p>

                                    <p class="mb-1">
                                        <strong>Duration:</strong>
                                        <?php echo e(\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date))); ?>

                                        days
                                        (<?php echo e(\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30); ?>

                                        month)
                                    </p>

                                    <p class="mb-1"><strong>Start Date:</strong> <?php echo e($contract->start_date); ?></p>
                                    <p class="mb-1"><strong>End Date:</strong> <?php echo e($contract->end_date); ?></p>

                                    <p class="mb-1">
                                        <strong>Time Remaining:</strong>
                                        <span class="text-danger fw-bold" id="countdown"></span>
                                    </p>

                                    <p class="mb-1">
                                        <strong>Status:</strong>
                                        <span class="badge bg-info"><?php echo e($contract->status); ?></span>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contract->status == "graced"): ?>
                                            <small class="text-warning">
                                                (<?php echo e(\Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans()); ?>)
                                            </small>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>

                                    <p>
                                        <strong>Payment:</strong>
                                        <span class="badge bg-success"><?php echo e($contract->payment_status); ?></span>
                                    </p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted text-center">No Active Plan</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <hr class="my-4">

                        
                        <h5 class="text-primary fw-bold mb-3 text-center">
                            <i class="fas fa-sync-alt"></i> Active Plans (Not Current)
                        </h5>

                        <div class="table-responsive">
                            <table class="table-hover small table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Plan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contract->is_current_contract == 0 && $contract->payment_status == "payed" && $contract->status != "inactive"): ?>
                                            <tr>
                                                <td><?php echo e($contract->package->name); ?></td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm rounded-pill shadow"
                                                        href="<?php echo e(route("contracts.users.activate", ["contract_id" => $contract->id, "owner_id" => Auth::user()->id])); ?>">
                                                        <i class="fas fa-check-circle"></i> Activate
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            
            <div class="col-md-8">
                <div class="card rounded-3 border-0 shadow">
                    <div class="card-body">

                        <h4 class="text-primary fw-bold mb-4 text-center">
                            <i class="fas fa-history"></i> Payment History
                        </h4>

                        <div class="table-responsive">
                            <table class="table-striped table-hover small table" id="Table">
                                <thead class="table-light fw-bold">
                                    <tr>
                                        <th>#</th>
                                        <th>Plan</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Duration</th>
                                        <th>Price</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contract->payment_status != "pending"): ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration); ?></td>
                                                <td><?php echo e($contract->package->name); ?></td>
                                                <td><?php echo e($contract->start_date); ?></td>
                                                <td><?php echo e($contract->end_date); ?></td>

                                                <td>
                                                    <?php echo e(\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date))); ?>

                                                    days
                                                    (<?php echo e(\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30); ?>

                                                    month)
                                                </td>

                                                <td class="fw-bold text-success">
                                                    <?php echo e(number_format(
                                                        (\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30) *
                                                            $contract->package->price,
                                                    )); ?>

                                                </td>

                                                <td>
                                                    <span><?php echo e($contract->payment_status); ?></span>
                                                </td>

                                                <td>
                                                    <span><?php echo e($contract->status); ?></span>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contract->is_current_contract): ?>
                                                        <span class="badge bg-success">Current</span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!session("agent")): ?>
            <div class="mt-5">
                <h3 class="text-primary fw-bold mb-4 text-center">
                    <i class="fas fa-gift"></i> Subscription Options
                </h3>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pricingData["mode"] == "dynamic"): ?>
                    <div class="alert alert-info text-center">
                        <h4 class="alert-heading"><i class="fas fa-calculator"></i> Item-Based Pricing Active</h4>
                        <p>Your subscription is calculated based on your inventory size:
                            <strong><?php echo e(number_format($pricingData["details"]["total_items"])); ?> items</strong>.
                        </p>
                        <hr>
                        <p class="mb-0">Base Rate: TZS <?php echo e(number_format($pricingData["details"]["rate"])); ?> x
                            <?php echo e($pricingData["details"]["multiplier"]); ?> (Tier) = <strong>TZS
                                <?php echo e(number_format($pricingData["amount"])); ?> / month</strong></p>
                    </div>
                <?php elseif($pricingData["mode"] == "profit_share"): ?>
                    <div class="alert alert-success text-center">
                        <h4 class="alert-heading"><i class="fas fa-chart-line"></i> Profit Share Pricing Active</h4>
                        <p>Your subscription is based on <strong><?php echo e($pricingData["details"]["percentage"]); ?>%</strong> of
                            your last 30 days profit.</p>
                        <hr>
                        <p class="mb-0">Estimated Monthly Profit: TZS
                            <?php echo e(number_format($pricingData["details"]["monthly_profit"])); ?> = <strong>TZS
                                <?php echo e(number_format($pricingData["amount"])); ?> / month</strong></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="card border-0 shadow">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-secondary m-0">Select Plan</h5>
                            <div class="d-flex align-items-center">
                                <label class="fw-bold me-2 text-nowrap" for="billingCycle">Billing Cycle:</label>
                                <select class="form-select-sm form-select shadow-sm" id="billingCycle" style="width: auto;">
                                    <option selected value="1">1 Month (Monthly)</option>
                                    <option value="3">3 Months (Quarterly)</option>
                                    <option value="6">6 Months (Semi-Annual)</option>
                                    <option value="12">1 Year (Annual)</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table-hover small table align-middle" id="Table2">
                                <thead class="table-light">
                                    <tr>
                                        <th>Duration Plan</th>
                                        <th>Estimated Price</th>
                                        <th>Duration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                        // Base values for JS
                                        $isStandard = $pricingData["mode"] == "standard";
                                        $baseAmount = $isStandard ? 0 : $pricingData["amount"]; // For dynamic/profit
                                        $agentMarkup = $isStandard ? 0 : $pricingData["agent_markup"];
                                    ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $activeContracts = Auth::user()->contracts->where("is_current_contract", 1);
                                            $hasAnyContract = Auth::user()->contracts->count();
                                            // Standard price fallback
                                            $pkgPrice = $package->price;
                                        ?>

                                        <tr class="package-row <?php echo e($hasAnyContract > 0 && $package->id == 1 ? "d-none" : ""); ?>"
                                            data-agent-markup="<?php echo e($agentMarkup); ?>" data-base-amount="<?php echo e($baseAmount); ?>"
                                            data-is-standard="<?php echo e($isStandard ? "true" : "false"); ?>"
                                            data-package-id="<?php echo e($package->id); ?>"
                                            data-standard-price="<?php echo e($pkgPrice); ?>">

                                            <td class="fw-bold text-primary"><?php echo e($package->name); ?></td>

                                            <td class="price-cell">
                                                <!-- Content filled/updated by JS -->
                                                <span class="price-display fw-bold text-dark"></span>
                                                <div class="agent-fee-display text-muted small fst-italic"></div>
                                            </td>

                                            <td class="duration-display"><?php echo e($package->duration); ?> days</td>

                                            <td>
                                                <?php
                                                    $btnClass = "btn btn-primary btn-sm rounded-pill shadow action-btn";
                                                    $btnIcon = "fas fa-check-circle";
                                                    $btnText = "Subscribe";
                                                    $route = "contracts.users.subscribe";

                                                    if ($activeContracts->count() > 0) {
                                                        if ($activeContracts->first()->package->id == $package->id) {
                                                            if ($activeContracts->first()->end_date < now()) {
                                                                $btnClass =
                                                                    "btn btn-danger btn-sm rounded-pill shadow action-btn";
                                                                $btnIcon = "fas fa-sync-alt";
                                                                $btnText = "Renew";
                                                                $route = "contracts.users.renew";
                                                            } else {
                                                                $btnText = "Current"; // Special case handled differently usually
                                                            }
                                                        } else {
                                                            $btnIcon = "fas fa-arrow-up";
                                                            $btnText = "Upgrade";
                                                            $route = "contracts.users.upgrade";
                                                        }
                                                    }

                                                    // Base URL without params, JS will construct full URL
                                                    $baseUrl = route($route, [
                                                        "package_id" => $package->id,
                                                        "owner_id" => Auth::user()->id,
                                                    ]);
                                                ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($btnText === "Current"): ?>
                                                    <span class="badge bg-success">Current</span>
                                                <?php else: ?>
                                                    <a class="<?php echo e($btnClass); ?>" data-base-url="<?php echo e($baseUrl); ?>"
                                                        href="<?php echo e($baseUrl); ?>" onclick="return confirmAction(this)">
                                                        <i class="<?php echo e($btnIcon); ?>"></i> <?php echo e($btnText); ?>

                                                    </a>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>

                            </table>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const billingCycleSelect = document.getElementById('billingCycle');
                                const rows = document.querySelectorAll('.package-row');

                                function updatePricing() {
                                    const months = parseInt(billingCycleSelect.value);
                                    const days = months * 30; // approx

                                    rows.forEach(row => {
                                        const isStandard = row.dataset.isStandard === 'true';
                                        let finalPrice = 0;
                                        let agentFeeTotal = 0;

                                        if (isStandard) {
                                            const standardPrice = parseFloat(row.dataset.standardPrice);
                                            finalPrice = standardPrice * months;
                                        } else {
                                            const baseAmount = parseFloat(row.dataset.baseAmount);
                                            const agentMarkup = parseFloat(row.dataset.agentMarkup);

                                            finalPrice = (baseAmount * months) + (agentMarkup * months);
                                            agentFeeTotal = agentMarkup * months;
                                        }

                                        // Update Price Display
                                        const priceDisplay = row.querySelector('.price-display');
                                        priceDisplay.textContent = 'TZS ' + new Intl.NumberFormat().format(finalPrice);

                                        // Update Agent Fee Display
                                        const agentFeeDisplay = row.querySelector('.agent-fee-display');
                                        if (!isStandard && agentFeeTotal > 0) {
                                            agentFeeDisplay.textContent = '(Includes Agent Fee: ' + new Intl.NumberFormat()
                                                .format(agentFeeTotal) + ')';
                                        } else {
                                            agentFeeDisplay.textContent = '';
                                        }

                                        // Update Duration Display
                                        const durationDisplay = row.querySelector('.duration-display');
                                        // durationDisplay.textContent = days + ' days (' + months + ' month' + (months > 1 ? 's' : '') + ')';
                                        // Keep standard package duration or show calculated? User sees "Billing Cycle", so maybe simpler:
                                        durationDisplay.textContent = months + ' Month' + (months > 1 ? 's' : '');


                                        // Update Action Buttons
                                        const btn = row.querySelector('a.action-btn');
                                        if (btn) {
                                            const baseUrl = btn.dataset.baseUrl;
                                            // Append months parameter
                                            // Check if baseUrl already has query params (it does: ?package_id=...&owner_id=...)
                                            // So append using &
                                            btn.href = baseUrl + '&months=' + months;
                                        }
                                    });
                                }

                                // Initial call
                                updatePricing();

                                // Event listener
                                billingCycleSelect.addEventListener('change', updatePricing);
                            });

                            function confirmAction(link) {
                                return confirm('Are you sure you want to proceed with this subscription plan?');
                            }
                        </script>

                    </div>
                </div>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session("agent")): ?>
            <?php $agent = session('agentData'); ?>

            <div class="mt-5">
                <h3 class="text-primary fw-bold mb-4 text-center">
                    <i class="fas fa-user-tie"></i> Your Agent Details
                </h3>

                <div class="card border-0 shadow">
                    <div class="card-body">
                        <table class="table-bordered table-striped small table">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo e($agent->name); ?></td>
                                    <td><?php echo e($agent->email); ?></td>
                                    <td><?php echo e($agent->phone); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    
    <script>
        var countDownDate = new Date("<?php echo e($current_contract_end_date); ?>").getTime();

        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;

            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("countdown").innerHTML =
                days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "EXPIRED";
            }
        }, 1000);
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make("contracts.app", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DEVELOPMENT\pms\resources\views/contracts/users/index.blade.php ENDPATH**/ ?>