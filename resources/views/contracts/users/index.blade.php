@extends('contracts.app')

@section('content')

    <div class="container my-4">

        {{-- ========================= CURRENT PLAN ========================= --}}
        <div class="row g-4">

            {{-- CURRENT PLAN CARD --}}
            <div class="col-md-4">
                <div class="card rounded-3 border-0 shadow">
                    <div class="card-body">

                        <!-- Billing Calculator Modal -->
                        <div class="modal fade" id="billingCalculatorModal" tabindex="-1"
                            aria-labelledby="billingCalculatorModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title fw-bold" id="billingCalculatorModalLabel"><i
                                                class="fas fa-calculator me-2"></i> Estimate Your Bill</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">

                                        @php
                                            // Fetch settings directly to ensure availability
                                            $sysSettings = \App\Models\SystemSetting::pluck('value', 'key');
                                            $rateItem = $sysSettings['dynamic_rate_per_item'] ?? 100;
                                            $rateStaff = $sysSettings['dynamic_rate_per_staff'] ?? 5000;
                                            $rateBranch = $sysSettings['dynamic_rate_per_branch'] ?? 20000;
                                            $profitPct = $sysSettings['profit_share_percentage'] ?? 5;
                                            $activeMode = $sysSettings['pricing_mode'] ?? 'standard';

                                            // Determine if user is inactive (no current active contract)
                                            $isInactive = $contracts->where('is_current_contract', true)->isEmpty();
                                        @endphp

                                        <form action="{{ route('contracts.users.generate_bill') }}" method="POST"
                                            id="billingForm">
                                            @csrf
                                            <input type="hidden" name="pricing_mode" value="{{ $activeMode }}">

                                            {{-- DYNAMIC --}}
                                            @if ($activeMode == 'dynamic')
                                                <div id="modal-dynamic-container">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold small text-uppercase">Items
                                                                (Stock)</label>
                                                            <input type="number" class="form-control" id="modalItems"
                                                                name="items_count" placeholder="0"
                                                                value="{{ $pricingData['details']['items_count'] ?? 0 }}">
                                                            <small class="text-muted">Rate: TZS
                                                                {{ number_format($rateItem) }}/item</small>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label
                                                                class="form-label fw-bold small text-uppercase">Staff</label>
                                                            <input type="number" class="form-control" id="modalStaff"
                                                                name="staff_count" placeholder="0"
                                                                value="{{ $pricingData['details']['staff_count'] ?? 0 }}">
                                                            <small class="text-muted">Rate: TZS
                                                                {{ number_format($rateStaff) }}/staff</small>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label
                                                                class="form-label fw-bold small text-uppercase">Branches</label>
                                                            <input type="number" class="form-control" id="modalBranches"
                                                                name="branches_count" placeholder="0"
                                                                value="{{ $pricingData['details']['branches_count'] ?? 0 }}">
                                                            <small class="text-muted">Rate: TZS
                                                                {{ number_format($rateBranch) }}/branch</small>
                                                        </div>

                                                        <div class="col-12 mt-3">
                                                            <label class="fw-bold mb-2 small text-uppercase">Add-ons</label>
                                                            <div class="d-flex gap-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="has_whatsapp" id="modalWhatsapp">
                                                                    <label class="form-check-label"
                                                                        for="modalWhatsapp">WhatsApp
                                                                        Alerts (TZS 5,000)</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="has_sms" id="modalSms">
                                                                    <label class="form-check-label" for="modalSms">SMS
                                                                        Alerts
                                                                        (TZS 10,000)</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- PROFIT SHARE --}}
                                            @elseif($activeMode == 'profit_share')
                                                <div id="modal-profit-container">
                                                    <div class="row justify-content-center">
                                                        <div class="col-md-8 text-center">
                                                            <label class="form-label fw-bold small text-uppercase">Estimated
                                                                Monthly Profit</label>
                                                            <div class="input-group input-group-lg mb-2">
                                                                <span class="input-group-text bg-light">TZS</span>
                                                                <input type="number" class="form-control fw-bold"
                                                                    name="profit_amount" id="modalProfitInput"
                                                                    placeholder="Enter Amount"
                                                                    value="{{ $pricingData['details']['last_30_days_profit'] ?? 0 }}">
                                                            </div>
                                                            <small class="text-muted">Platform Fee: <span
                                                                    class="fw-bold text-dark">{{ $profitPct }}%</span> of
                                                                profit</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center py-4">
                                                    <h5 class="text-muted">Standard Pricing Active</h5>
                                                    <p>Your pricing is fixed based on the selected package.</p>
                                                </div>
                                            @endif

                                            <div class="row g-3 mt-2">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold small text-uppercase">Billing Cycle (Months)</label>
                                                    <select class="form-select" name="months" id="modalMonths">
                                                        <option value="1">1 Month</option>
                                                        <option value="3">3 Months</option>
                                                        <option value="6">6 Months</option>
                                                        <option value="12">12 Months (1 Year)</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mt-4 p-4 bg-light rounded-3 text-center">
                                                <span class="d-block text-muted small text-uppercase fw-bold">Estimated
                                                    Total
                                                    Cost</span>
                                                <h2 class="fw-bold text-primary mb-0">TZS <span
                                                        id="modalTotalDisplay">0</span>
                                                </h2>
                                            </div>

                                            <div class="modal-footer border-">
                                                {{-- generate bill --}}
                                                <button type="submit" class="btn btn-primary rounded-pill px-4"
                                                    id="btnGenerateBill">Generate
                                                    bill</button>
                                                <button type="button" class="btn btn-secondary rounded-pill px-4"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>

                                            <div class="px-4 pb-3 text-center">
                                                <small class="text-danger fw-bold d-none" id="billMismatchWarning">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> Values must match
                                                    system
                                                    records to generate a bill.
                                                </small>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upgrade Modal -->
                        <div class="modal fade" id="upgradeAddonsModal" tabindex="-1"
                            aria-labelledby="upgradeAddonsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title fw-bold" id="upgradeAddonsModalLabel"><i
                                                class="fas fa-rocket me-2"></i> Upgrade Your Plan</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        @php
                                            $currentContract = $contracts->where('is_current_contract', true)->first();
                                            $details = $currentContract->details ?? [];
                                            $rates = $pricingData['upgrade_rates'];
                                        @endphp
                                        <form action="{{ route('contracts.users.request_upgrade') }}" method="POST">
                                            @csrf
                                            <p class="text-muted small mb-4">Select items to add to your current active contract. Prices are calculated for the duration you select.</p>
                                            
                                            <div class="mb-4">
                                                <label class="form-label fw-bold small text-uppercase text-info">1. Select Duration</label>
                                                <select class="form-select form-select-lg border-info" name="months" id="upgradeMonths">
                                                    <option value="1">1 Month</option>
                                                    <option value="3">3 Months</option>
                                                    <option value="6">6 Months</option>
                                                    <option value="12">1 Year</option>
                                                </select>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <label class="form-label fw-bold small text-uppercase text-info">2. Increase Limits</label>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold">Extra Branches</label>
                                                        <input type="number" name="extra_pharmacies" class="form-control upgrade-count" value="0" min="0" data-rate="{{ $rates['resources']['pharmacy'] }}">
                                                        <small class="text-muted">TZS {{ number_format($rates['resources']['pharmacy']) }} / branch / mo</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold">Extra Staff</label>
                                                        <input type="number" name="extra_pharmacists" class="form-control upgrade-count" value="0" min="0" data-rate="{{ $rates['resources']['staff'] }}">
                                                        <small class="text-muted">TZS {{ number_format($rates['resources']['staff']) }} / staff / mo</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold">Extra Medicines/Items</label>
                                                        <input type="number" name="extra_medicines" class="form-control upgrade-count" value="0" min="0" data-rate="{{ $rates['resources']['item'] }}">
                                                        <small class="text-muted">TZS {{ number_format($rates['resources']['item']) }} / item / mo</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <label class="form-label fw-bold small text-uppercase text-info">3. Add Features</label>
                                                    <div class="upgrade-features-list" style="max-height: 250px; overflow-y: auto;">
                                                        @php
                                                            $features = [
                                                                'has_whatsapp' => ['name' => 'WhatsApp Alerts', 'key' => 'has_whatsapp'],
                                                                'has_sms' => ['name' => 'SMS Alerts', 'key' => 'has_sms'],
                                                                'has_reports' => ['name' => 'Advanced Reports', 'key' => 'has_reports'],
                                                                'stock_management' => ['name' => 'Stock Management', 'key' => 'stock_management'],
                                                                'stock_transfer' => ['name' => 'Stock Transfer', 'key' => 'stock_transfer'],
                                                                'staff_management' => ['name' => 'Staff Management', 'key' => 'staff_management'],
                                                                'receipts' => ['name' => 'Receipts Generation', 'key' => 'receipts'],
                                                                'analytics' => ['name' => 'Business Analytics', 'key' => 'analytics'],
                                                            ];
                                                        @endphp
                                                        @foreach($features as $fKey => $f)
                                                            @php $price = $rates['features'][$f['key']] ?? 0; @endphp
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input upgrade-feature-check" type="checkbox" name="{{ $fKey }}" id="up_{{ $fKey }}" 
                                                                    data-rate="{{ $price }}"
                                                                    {{ ($details[$fKey] ?? false) ? 'disabled checked' : '' }}>
                                                                <label class="form-check-label small" for="up_{{ $fKey }}">
                                                                    {{ $f['name'] }} ({{ number_format($price) }}/mo)
                                                                    @if($details[$fKey] ?? false) <span class="badge bg-success ms-1">Active</span> @endif
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="bg-light p-3 rounded-3 mt-2 border border-info text-center">
                                                <span class="text-muted small text-uppercase fw-bold">Estimated Upgrade Cost</span>
                                                <h3 class="fw-bold text-info mb-0">TZS <span id="upgradeTotalDisplay">0</span></h3>
                                            </div>

                                            <div class="mt-4">
                                                <button type="submit" class="btn btn-info w-100 text-white fw-bold py-2 rounded-pill shadow-sm">
                                                    <i class="fas fa-paper-plane me-2"></i> Submit Upgrade Request
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Script for Upgrade Modal Calculation --}}
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const upgradeMonths = document.getElementById('upgradeMonths');
                                const upgradeCounts = document.querySelectorAll('.upgrade-count');
                                const upgradeFeatures = document.querySelectorAll('.upgrade-feature-check');
                                const upgradeTotalDisplay = document.getElementById('upgradeTotalDisplay');

                                function calculateUpgrade() {
                                    let total = 0;
                                    const months = parseInt(upgradeMonths.value);

                                    upgradeCounts.forEach(input => {
                                        const rate = parseFloat(input.dataset.rate);
                                        const val = parseInt(input.value) || 0;
                                        total += (val * rate * months);
                                    });

                                    upgradeFeatures.forEach(check => {
                                        if (check.checked && !check.disabled) {
                                            const rate = parseFloat(check.dataset.rate);
                                            total += (rate * months);
                                        }
                                    });

                                    upgradeTotalDisplay.textContent = new Intl.NumberFormat().format(total);
                                }

                                upgradeMonths.addEventListener('change', calculateUpgrade);
                                upgradeCounts.forEach(el => el.addEventListener('input', calculateUpgrade));
                                upgradeFeatures.forEach(el => el.addEventListener('change', calculateUpgrade));
                                
                                calculateUpgrade();
                            });
                        </script>

                        {{-- Hidden Settings for Modal Script --}}
                        <div id="modal-calc-settings" data-rate-item="{{ $rateItem }}"
                            data-rate-staff="{{ $rateStaff }}" data-rate-branch="{{ $rateBranch }}"
                            data-rate-profit="{{ $profitPct }}" data-active-mode="{{ $activeMode }}"
                            data-is-inactive="{{ $isInactive ? '1' : '0' }}">
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const mSettings = document.getElementById('modal-calc-settings').dataset;
                                const mRateItem = parseFloat(mSettings.rateItem);
                                const mRateStaff = parseFloat(mSettings.rateStaff);
                                const mRateBranch = parseFloat(mSettings.rateBranch);
                                const mRateProfit = parseFloat(mSettings.rateProfit);
                                const activeMode = mSettings.activeMode;
                                const isInactive = mSettings.isInactive === '1';

                                const display = document.getElementById('modalTotalDisplay');
                                const btnGenerate = document.getElementById('btnGenerateBill');
                                const warningMsg = document.getElementById('billMismatchWarning');

                                // Store defaults
                                let defaults = {};

                                function initDefaults() {
                                    if (activeMode === 'dynamic') {
                                        defaults = {
                                            items: document.getElementById('modalItems').value,
                                            staff: document.getElementById('modalStaff').value,
                                            branches: document.getElementById('modalBranches').value,
                                            whatsapp: document.getElementById('modalWhatsapp').checked,
                                            sms: document.getElementById('modalSms').checked
                                        };
                                    } else if (activeMode === 'profit_share') {
                                        defaults = {
                                            profit: document.getElementById('modalProfitInput').value
                                        };
                                    }
                                }

                                initDefaults();

                                function checkDefaults() {
                                    // If user is inactive, allow them to generate bill regardless of inputs
                                    if (isInactive) {
                                        btnGenerate.disabled = false;
                                        warningMsg.classList.add('d-none');
                                        btnGenerate.classList.add('btn-primary');
                                        btnGenerate.classList.remove('btn-secondary');
                                        return;
                                    }

                                    let isMatch = true;

                                    if (activeMode === 'dynamic') {
                                        const currItems = document.getElementById('modalItems').value;
                                        const currStaff = document.getElementById('modalStaff').value;
                                        const currBranches = document.getElementById('modalBranches').value;

                                        if (currItems !== defaults.items ||
                                            currStaff !== defaults.staff ||
                                            currBranches !== defaults.branches) {
                                            isMatch = false;
                                        }

                                    } else if (activeMode === 'profit_share') {
                                        const currProfit = document.getElementById('modalProfitInput').value;
                                        if (currProfit !== defaults.profit) {
                                            isMatch = false;
                                        }
                                    }

                                    btnGenerate.disabled = !isMatch;
                                    if (!isMatch) {
                                        warningMsg.classList.remove('d-none');
                                        btnGenerate.classList.add('btn-secondary');
                                        btnGenerate.classList.remove('btn-primary');
                                    } else {
                                        warningMsg.classList.add('d-none');
                                        btnGenerate.classList.add('btn-primary');
                                        btnGenerate.classList.remove('btn-secondary');
                                    }
                                }

                                function calculateModal() {
                                    let total = 0;

                                    checkDefaults();

                                    if (activeMode === 'dynamic') {
                                        const items = parseFloat(document.getElementById('modalItems').value) || 0;
                                        const staff = parseFloat(document.getElementById('modalStaff').value) || 0;
                                        const branches = parseFloat(document.getElementById('modalBranches').value) || 0;

                                        total = (items * mRateItem) + (staff * mRateStaff) + (branches * mRateBranch);

                                        if (document.getElementById('modalWhatsapp').checked) total += 5000;
                                        if (document.getElementById('modalSms').checked) total += 10000;

                                    } else if (activeMode === 'profit_share') {
                                        const profit = parseFloat(document.getElementById('modalProfitInput').value) || 0;
                                        total = profit * (mRateProfit / 100);
                                    }

                                    const months = parseInt(document.getElementById('modalMonths').value) || 1;
                                    total = total * months;

                                    display.textContent = new Intl.NumberFormat().format(total);
                                }

                                // Initial Calculation on Load (to show current usage price)
                                calculateModal();

                                // Listeners based on mode
                                ['modalMonths'].forEach(id => {
                                    const el = document.getElementById(id);
                                    if (el) el.addEventListener('change', calculateModal);
                                });

                                if (activeMode === 'dynamic') {
                                    ['modalItems', 'modalStaff', 'modalBranches', 'modalWhatsapp', 'modalSms'].forEach(id => {
                                        const el = document.getElementById(id);
                                        if (el) {
                                            el.addEventListener('input', calculateModal);
                                            el.addEventListener('change', calculateModal);
                                        }
                                    });
                                } else if (activeMode === 'profit_share') {
                                    const el = document.getElementById('modalProfitInput');
                                    if (el) {
                                        el.addEventListener('input', calculateModal);
                                        el.addEventListener('change', calculateModal);
                                    }
                                }

                                // Form submission allowed regardless of active status
                                // const billingForm = document.getElementById('billingForm');
                                // if (billingForm) {
                                //    billingForm.addEventListener('submit', function(e) {
                                // Block removed per user request
                                //    });
                                // }
                            });
                        </script>

                        <h4 class="text-primary fw-bold mb-4 text-center">
                            <i class="fas fa-bolt"></i> Current Plan
                        </h4>

                        @if ($contracts->count() > 0)
                            @foreach ($contracts as $contract)
                                @if ($contract->is_current_contract)
                                    <div class="bg-light mb-3 rounded border p-3 shadow-sm">
                                        <h5 class="fw-bold text-primary">
                                            <i class="fas fa-layer-group"></i> {{ $contract->package->name }}
                                        </h5>
                                    </div>

                                    @if (!session('agent'))
                                        <p class="mb-1">
                                            <strong>Amount Paid:</strong>
                                            <span class="text-success fw-bold">
                                                TZS
                                                {{ number_format((\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30) * $contract->package->price) }}
                                            </span>
                                        </p>
                                    @endif

                                    <p class="mb-1"><strong>Package Price:</strong> TZS
                                        {{ number_format($contract->package->price) }} /
                                        {{ $contract->package->duration }}
                                        days</p>

                                    <p class="mb-1">
                                        <strong>Duration:</strong>
                                        {{ \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) }}
                                        days
                                        ({{ \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30 }}
                                        month)
                                    </p>

                                    <p class="mb-1"><strong>Start Date:</strong> {{ $contract->start_date }}</p>
                                    <p class="mb-1"><strong>End Date:</strong> {{ $contract->end_date }}</p>

                                    <p class="mb-1">
                                        <strong>Time Remaining:</strong>
                                        <span class="text-danger fw-bold" id="countdown"></span>
                                    </p>

                                    <p class="mb-1">
                                        <strong>Status:</strong>
                                        <span class="badge bg-info">{{ $contract->status }}</span>

                                        @if ($contract->status == 'graced')
                                            <small class="text-warning">
                                                ({{ \Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans() }})
                                            </small>
                                        @endif
                                    </p>

                                    <p>
                                        <strong>Payment:</strong>
                                        <span class="badge bg-success">{{ $contract->payment_status }}</span>
                                    </p>

                                    <div class="mt-4 border-top pt-3">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-list-check me-1"></i> What's Included?</h6>
                                        @php
                                            $pkg = $contract->package;
                                            $det = $contract->details ?? [];
                                            $extraBranches = $det['extra_pharmacies'] ?? 0;
                                            $extraStaff = $det['extra_pharmacists'] ?? 0;
                                            $extraItems = $det['extra_medicines'] ?? 0;
                                        @endphp
                                        
                                        <ul class="list-unstyled small mb-0">
                                            <li class="mb-2">
                                                <i class="fas fa-hospital text-info me-2"></i> 
                                                <strong>{{ ($pkg->number_of_pharmacies ?? 0) + $extraBranches }}</strong> Pharmacy Branches
                                                @if($extraBranches > 0) <span class="badge bg-success shadow-sm ms-1">+{{ $extraBranches }} Upgraded</span> @endif
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-users text-info me-2"></i> 
                                                <strong>{{ ($pkg->number_of_pharmacists ?? 0) + $extraStaff }}</strong> Staff Members
                                                @if($extraStaff > 0) <span class="badge bg-success shadow-sm ms-1">+{{ $extraStaff }} Upgraded</span> @endif
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-pills text-info me-2"></i> 
                                                <strong>{{ ($pkg->number_of_medicines ?? 0) + $extraItems }}</strong> Medicine Items
                                                @if($extraItems > 0) <span class="badge bg-success shadow-sm ms-1">+{{ $extraItems }} Upgraded</span> @endif
                                            </li>
                                            
                                            @php
                                                $features = [
                                                    'stock_management' => 'Stock Management',
                                                    'stock_transfer' => 'Stock Transfer',
                                                    'staff_management' => 'Staff Management',
                                                    'receipts' => 'Receipts Generation',
                                                    'analytics' => 'Business Analytics',
                                                    'has_whatsapp' => 'WhatsApp Alerts',
                                                    'has_sms' => 'SMS Alerts',
                                                    'has_reports' => 'Advanced Reports',
                                                ];
                                            @endphp
                                            
                                            @foreach($features as $key => $label)
                                                @php 
                                                    $inBase = $pkg->$key ?? false;
                                                    // Map feature names to details keys if they differ
                                                    $detKey = $key;
                                                    if($key == 'has_reports') $detKey = 'has_reports'; // already same
                                                    
                                                    $inUpgrade = $det[$detKey] ?? false;
                                                    $active = $inBase || $inUpgrade;
                                                @endphp
                                                @if($active)
                                                    <li class="mb-2">
                                                        <i class="fas fa-check-circle text-success me-2"></i> 
                                                        {{ $label }}
                                                        @if(!$inBase && $inUpgrade) <span class="badge bg-info shadow-sm ms-1">Add-on</span> @endif
                                                    </li>
                                                @else
                                                    <li class="mb-2 text-muted">
                                                        <i class="fas fa-times-circle me-2 opacity-50"></i> 
                                                        <span class="opacity-75">{{ $label }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-outline-info btn-sm w-100 rounded-pill fw-bold" 
                                            data-bs-toggle="modal" data-bs-target="#upgradeAddonsModal">
                                            <i class="fas fa-plus-circle me-1"></i> Add Extras (WhatsApp/SMS)
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-rocket fa-4x text-primary opacity-25"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-3">Welcome to PILLPOINT!</h4>
                                <p class="text-muted mb-4">You don't have an active subscription yet. Choose an option below to get started:</p>
                                
                                <div class="d-grid gap-3 col-lg-10 mx-auto">
                                    <a href="{{ route('contracts.users.start_trial') }}" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm">
                                        <i class="fas fa-play-circle me-2"></i> Start 14-Day Free Trial
                                    </a>
                                    
                                    <div class="position-relative my-2">
                                        <hr>
                                        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small fw-bold">OR</span>
                                    </div>
                                    
                                    <button type="button" class="btn btn-outline-primary rounded-pill py-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#billingCalculatorModal">
                                        <i class="fas fa-calculator me-2"></i> Generate Custom Bill
                                    </button>
                                </div>
                                
                                <p class="mt-4 small text-muted">
                                    <i class="fas fa-info-circle me-1"></i> 
                                    Need help? Contact our support team or your assigned agent.
                                </p>
                            </div>
                        @endif

                        <hr class="my-4">

                        {{-- ========================= ACTIVE BUT NOT CURRENT ========================= --}}
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
                                    @foreach ($contracts as $contract)
                                        @if ($contract->is_current_contract == 0 && $contract->payment_status == 'payed' && $contract->status != 'inactive')
                                            <tr>
                                                <td>{{ $contract->package->name }}</td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm rounded-pill shadow"
                                                        href="{{ route('contracts.users.activate', ['contract_id' => $contract->id, 'owner_id' => Auth::user()->id]) }}">
                                                        <i class="fas fa-check-circle"></i> Activate
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ========================= PAYMENT HISTORY ========================= --}}
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
                                    @foreach ($contracts as $contract)
                                        @if ($contract->payment_status != 'pending')
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $contract->package->name }}</td>
                                                <td>{{ $contract->start_date }}</td>
                                                <td>{{ $contract->end_date }}</td>

                                                <td>
                                                    {{ \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) }}
                                                    days
                                                    ({{ \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30 }}
                                                    month)
                                                </td>

                                                <td class="fw-bold text-success">
                                                    {{ number_format(
                                                        (\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30) *
                                                            $contract->package->price,
                                                    ) }}
                                                </td>

                                                <td>
                                                    <span>{{ $contract->payment_status }}</span>
                                                </td>

                                                <td>
                                                    <span>{{ $contract->status }}</span>
                                                    @if ($contract->is_current_contract)
                                                        <span class="badge bg-success">Current</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- ========================= PENDING INVOICES ========================= --}}
        <div class="col-12 mb-4 mt-4">
            <div class="card border-0 shadow bg-white border-start border-warning border-5">
                <div class="card-body">
                    <div class="d-flex justify-between">
                        <h5 class="text-warning fw-bold mb-3">
                            <i class="fas fa-file-invoice-dollar"></i> Pending Invoices / Payment Requests
                        </h5>
                        {{-- add button to compute current billing --}}
                        <div class="text-right mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#billingCalculatorModal">
                                <i class="fas fa-calculator me-1"></i> Compute Billing
                            </button>
                        </div>
                    </div>

                    @php
                        $pendingContracts = $contracts->where('payment_status', 'pending');
                    @endphp

                    @if ($pendingContracts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Package</th>
                                        <th>Created Date</th>
                                        <th>Amount Due</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingContracts as $contract)
                                        <tr>
                                            <td class="fw-bold text-primary">{{ $contract->package->name }}</td>
                                            <td>{{ $contract->created_at->format('Y-m-d') }}</td>
                                            <td class="fw-bold text-dark">TZS {{ number_format($contract->amount) }}</td>
                                            <td><span
                                                    class="badge bg-warning text-dark">{{ $contract->payment_status }}</span>
                                            </td>
                                            <td>
                                                @if(!$contract->payment_notified && $contract->payment_status != 'payed')
                                                    <a href="{{ route('contracts.users.notify_payment', $contract->id) }}" 
                                                       class="btn btn-success btn-sm rounded-pill shadow-sm">
                                                        <i class="fas fa-paper-plane me-1"></i> Notify Payment
                                                    </a>
                                                    
                                                    {{-- Modify button opens the calculator --}}
                                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm"
                                                        data-bs-toggle="modal" data-bs-target="#billingCalculatorModal">
                                                        <i class="fas fa-edit me-1"></i> Modify
                                                    </button>
                                                @elseif($contract->payment_status == 'payed' && (!$contract->is_current_contract || $contract->status == 'inactive') && \Carbon\Carbon::parse($contract->end_date)->isFuture())
                                                    <a href="{{ route('contracts.users.activate', ['contract_id' => $contract->id]) }}" 
                                                       class="btn btn-primary btn-sm rounded-pill shadow-sm">
                                                        <i class="fas fa-bolt me-1"></i> Activate Now
                                                    </a>
                                                @elseif($contract->payment_notified && $contract->payment_status != 'payed')
                                                    <span class="badge bg-info text-white"><i class="fas fa-check-circle me-1"></i> Payment Notified</span>
                                                @endif

                                                <form action="{{ route('contracts.destroy', $contract->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-outline-danger btn-sm rounded-pill shadow-sm">
                                                        <i class="fas fa-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No pending invoices found.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ========================= AVAILABLE PLANS ========================= --}}
        @if (!session('agent'))
            <div class="mt-5 mb-5">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="text-primary fw-bold mb-0"><i class="fas fa-file-invoice"></i> Contract &
                                    Billing
                                    Actions</h4>
                                <p class="text-muted mb-0 small">Manage your invoices and subscription renewals.</p>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <div class="d-flex gap-2 justify-content-md-end">
                                    <a href="#billingCycle" class="btn btn-white border shadow-sm rounded-pill px-4"
                                        onclick="document.getElementById('billingCycle').focus();">
                                        <i class="fas fa-history text-muted me-2"></i> New Subscription
                                    </a>

                                    {{-- Logic to determine if "Generate Bill" is relevant. Usually implies subscribing/renewing. --}}
                                    {{-- We can link to the standard package subscription or scroll to options --}}
                                    <a href="#billingCycle" class="btn btn-primary rounded-pill px-4 shadow-sm"
                                        onclick="document.getElementById('billingCycle').focus();">
                                        <i class="fas fa-plus-circle me-2"></i> Generate New Bill
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h3 class="text-primary fw-bold mb-4 text-center">
                    <i class="fas fa-gift"></i> Subscription Options
                </h3>

                {{-- CALCULATOR SECTION --}}
                <div class="card border-0 shadow-sm mb-5 bg-white">
                    <div class="card-header bg-white border-0 pt-4 pb-0">
                        <h4 class="text-primary fw-bold text-center">
                            <i class="fas fa-calculator"></i> Price Calculator
                        </h4>
                        <p class="text-muted text-center small">Check your current usage pricing or simulate future costs.
                        </p>
                    </div>
                    <div class="card-body p-4">

                        {{-- DYNAMIC PRICING MODE --}}
                        @if ($pricingData['mode'] == 'dynamic')
                            @php
                                $d = $pricingData['details'];
                            @endphp
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-uppercase">Items (Stock)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="fas fa-boxes"></i></span>
                                        <input type="number" class="form-control border-start-0" id="calcItems"
                                            value="{{ $d['items_count'] ?? 0 }}">
                                    </div>
                                    <small class="text-muted">Rate: TZS
                                        {{ number_format($d['items_rate'] ?? 0) }}/item</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-uppercase">Staff</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="fas fa-users"></i></span>
                                        <input type="number" class="form-control border-start-0" id="calcStaff"
                                            value="{{ $d['staff_count'] ?? 0 }}">
                                    </div>
                                    <small class="text-muted">Rate: TZS
                                        {{ number_format($d['staff_rate'] ?? 0) }}/staff</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-uppercase">Branches</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i
                                                class="fas fa-store"></i></span>
                                        <input type="number" class="form-control border-start-0" id="calcBranches"
                                            value="{{ $d['branches_count'] ?? 0 }}">
                                    </div>
                                    <small class="text-muted">Rate: TZS
                                        {{ number_format($d['branches_rate'] ?? 0) }}/branch</small>
                                </div>

                                <input type="hidden" id="rateItem" value="{{ $d['items_rate'] ?? 0 }}">
                                <input type="hidden" id="rateStaff" value="{{ $d['staff_rate'] ?? 0 }}">
                                <input type="hidden" id="rateBranch" value="{{ $d['branches_rate'] ?? 0 }}">
                            </div>

                            {{-- PROFIT SHARE MODE --}}
                        @elseif($pricingData['mode'] == 'profit_share')
                            @php
                                $d = $pricingData['details'];
                            @endphp
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-uppercase">Estimated Monthly Profit</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">TZS</span>
                                        <input type="number" class="form-control border-start-0 fw-bold" id="calcProfit"
                                            value="{{ $d['last_30_days_profit'] ?? 0 }}">
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="text-muted">Platform Fee: <span
                                                class="fw-bold text-dark">{{ $d['percentage'] ?? 0 }}%</span></small>
                                    </div>
                                    <input type="hidden" id="rateProfit" value="{{ $d['percentage'] ?? 0 }}">
                                </div>
                            </div>
                        @else
                            <div class="alert alert-light text-center border">
                                Standard Pricing Active. Price is fixed per package.
                            </div>
                        @endif

                        @if ($pricingData['mode'] != 'standard')
                            <div class="text-center mt-4">
                                <button class="btn btn-primary rounded-pill px-5 shadow-sm" type="button"
                                    onclick="computePrice()">
                                    <i class="fas fa-sync-alt me-2"></i> Compute Price
                                </button>
                            </div>

                            <div class="mt-4 p-3 bg-light rounded-3 text-center" id="resultContainer"
                                style="display:none;">
                                <span class="d-block text-muted small text-uppercase fw-bold">Estimated Monthly Cost</span>
                                <h2 class="fw-bold text-primary mb-0">TZS <span id="calcTotal">0</span></h2>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- JS For Calculator --}}
                <script>
                    function computePrice() {
                        let total = 0;
                        const mode = "{{ $pricingData['mode'] }}";

                        if (mode === 'dynamic') {
                            const items = parseFloat(document.getElementById('calcItems').value) || 0;
                            const staff = parseFloat(document.getElementById('calcStaff').value) || 0;
                            const branches = parseFloat(document.getElementById('calcBranches').value) || 0;

                            const rItem = parseFloat(document.getElementById('rateItem').value) || 0;
                            const rStaff = parseFloat(document.getElementById('rateStaff').value) || 0;
                            const rBranch = parseFloat(document.getElementById('rateBranch').value) || 0;

                            total = (items * rItem) + (staff * rStaff) + (branches * rBranch);

                        } else if (mode === 'profit_share') {
                            const profit = parseFloat(document.getElementById('calcProfit').value) || 0;
                            const percentage = parseFloat(document.getElementById('rateProfit').value) || 0;

                            total = profit * (percentage / 100);
                        }

                        const resultContainer = document.getElementById('resultContainer');
                        const totalSpan = document.getElementById('calcTotal');

                        totalSpan.textContent = new Intl.NumberFormat().format(total);
                        resultContainer.style.display = 'block';

                        // Optional: Animation effect
                        resultContainer.classList.add('animate__animated', 'animate__fadeIn');
                    }
                </script>

                <div class="card border-0 shadow">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-secondary m-0">Select Plan</h5>
                            <div class="d-flex align-items-center">
                                <label class="fw-bold me-2 text-nowrap" for="billingCycle">Billing Cycle:</label>
                                <select class="form-select-sm form-select shadow-sm" id="billingCycle"
                                    style="width: auto;">
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
                                    @php
                                        // Base values for JS
                                        $isStandard = $pricingData['mode'] == 'standard';
                                        $baseAmount = $isStandard ? 0 : $pricingData['amount']; // For dynamic/profit
                                        $agentMarkup = $isStandard ? 0 : $pricingData['agent_markup'];
                                    @endphp

                                    @foreach ($packages as $package)
                                        @php
                                            $activeContracts = Auth::user()->contracts->where('is_current_contract', 1);
                                            $hasAnyContract = Auth::user()->contracts->count();
                                            // Standard price fallback
                                            $pkgPrice = $package->price;
                                        @endphp

                                        <tr class="package-row {{ $hasAnyContract > 0 && $package->id == 1 ? 'd-none' : '' }}"
                                            data-agent-markup="{{ $agentMarkup }}"
                                            data-base-amount="{{ $baseAmount }}"
                                            data-is-standard="{{ $isStandard ? 'true' : 'false' }}"
                                            data-package-id="{{ $package->id }}"
                                            data-standard-price="{{ $pkgPrice }}">

                                            <td class="fw-bold text-primary">{{ $package->name }}</td>

                                            <td class="price-cell">
                                                <!-- Content filled/updated by JS -->
                                                <span class="price-display fw-bold text-dark"></span>
                                                <div class="agent-fee-display text-muted small fst-italic"></div>
                                            </td>

                                            <td class="duration-display">{{ $package->duration }} days</td>

                                            <td>
                                                @php
                                                    $btnClass = 'btn btn-primary btn-sm rounded-pill shadow action-btn';
                                                    $btnIcon = 'fas fa-check-circle';
                                                    $btnText = 'Subscribe';
                                                    $route = 'contracts.users.subscribe';

                                                    if ($activeContracts->count() > 0) {
                                                        if ($activeContracts->first()->package->id == $package->id) {
                                                            if ($activeContracts->first()->end_date < now()) {
                                                                $btnClass =
                                                                    'btn btn-danger btn-sm rounded-pill shadow action-btn';
                                                                $btnIcon = 'fas fa-sync-alt';
                                                                $btnText = 'Renew';
                                                                $route = 'contracts.users.renew';
                                                            } else {
                                                                $btnText = 'Current'; // Special case handled differently usually
                                                            }
                                                        } else {
                                                            $btnIcon = 'fas fa-arrow-up';
                                                            $btnText = 'Upgrade';
                                                            $route = 'contracts.users.upgrade';
                                                        }
                                                    }

                                                    // Base URL without params, JS will construct full URL
                                                    $baseUrl = route($route, [
                                                        'package_id' => $package->id,
                                                        'owner_id' => Auth::user()->id,
                                                    ]);
                                                @endphp

                                                @if ($btnText === 'Current')
                                                    <span class="badge bg-success">Current</span>
                                                @else
                                                    <a class="{{ $btnClass }}" data-base-url="{{ $baseUrl }}"
                                                        href="{{ $baseUrl }}" onclick="return confirmAction(this)">
                                                        <i class="{{ $btnIcon }}"></i> {{ $btnText }}
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
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
        @endif

        {{-- ========================= AGENT DETAILS ========================= --}}
        @if (session('agent'))
            @php $agent = session('agentData'); @endphp

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
                                    <td>{{ $agent->name }}</td>
                                    <td>{{ $agent->email }}</td>
                                    <td>{{ $agent->phone }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        @endif

    </div>
    </div>

    {{-- COUNTDOWN SCRIPT --}}
    <script>
        var countDownDate = new Date("{{ $current_contract_end_date }}").getTime();

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

@endsection
