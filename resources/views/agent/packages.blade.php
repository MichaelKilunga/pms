@extends('agent.app')

@section('content')
    <div class="container mt-4">

        {{-- ========================= SELECT OWNER ========================= --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h3 text-primary">Select Owner to Manage</h2>
                <span class="{{ session('owner') ? 'text-success' : 'text-danger' }}">
                    {{ session('owner') ? session('owner') : 'Select Owner' }}
                </span>
            </div>
            <form action="{{ route('agent.packages.manage', ['action' => 'index']) }}" method="post">
                @csrf
                <div class="row mt-2 g-2">
                    <div class="col-md-8">
                        <select class="form-select rounded" id="owner_id" name="owner_id" required>
                            <option value="">--Select Owner--</option>
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}"
                                    {{ session('owner_id') == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }} ({{ $owner->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-start">
                        <button type="submit" class="btn btn-primary"> <i class="fas fa-gear me-1"></i> Manage
                    </div>
                </div>
            </form>
        </div>

        <hr>

        {{-- ========================= CURRENT & ACTIVE PLANS ========================= --}}
        <div class="row mt-4 g-4">

            {{-- Current & Active Plans --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0">

                    {{-- Current Plan --}}
                    <div class="card-body">
                        {{-- <div class="p-3 border rounded bg-light mb-3 shadow-sm"> --}}
                        <h5 class="card-title text-center fs-4 text-primary"><i class="fas fa-bolt"></i> Current Plan
                        </h5>
                        {{-- </div> --}}
                        @if ($contracts->count() > 0)
                            @foreach ($contracts as $contract)
                                @if ($contract->is_current_contract)
                                    <div class="p-3 border rounded bg-light mb-3 shadow-sm">
                                        <h5 class="card-title text-primary fw-bold"><i class="fas fa-layer-group"></i> {{ $contract->package->name }}</h5>
                                    </div>
                                    <p class="card-text mb-1">Package Price: TZS
                                        {{ number_format($contract->package->price) }} / {{ $contract->package->duration }}
                                        days</p>
                                    <p class="card-text mb-1">Amount Paid: TZS
                                        {{ number_format((\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30) * $contract->package->price) }}
                                    </p>
                                    <p class="card-text mb-1">Duration:
                                        {{ \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) }}
                                        days
                                        ({{ \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30 }}
                                        Month)
                                    </p>
                                    <p class="card-text mb-1">Start Date: {{ $contract->start_date }}</p>
                                    <p class="card-text mb-1">End Date: {{ $contract->end_date }}</p>
                                    <p class="card-text mb-1">Time Remaining: <span class="text-danger"
                                            id="countdown"></span></p>
                                    <p class="card-text mb-1">Status: {{ $contract->status }} <small
                                            class="text-warning">{{ $contract->status == 'graced' ? \Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans() : '' }}</small>
                                    </p>
                                    <p class="card-text mb-0">Payment: {{ $contract->payment_status }}</p>
                                @endif
                            @endforeach
                        @else
                            <p class="card-text">No activated plan</p>
                        @endif
                        <hr>
                    </div>

                    {{-- Active but not current plans --}}
                    <div class="card-body table-responsive">
                        <h5 class="card-title text-center fs-4 text-primary"><i class="fas fa-sync-alt"></i>
                            Activate Plans (Not Current)</h5>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Plan</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $activePlans = 0; @endphp
                                @foreach ($contracts as $contract)
                                    @if (!$contract->is_current_contract && $contract->payment_status == 'payed' && $contract->status != 'inactive')
                                        <tr>
                                            <td>{{ $contract->package->name }}</td>
                                            <td>
                                                <a href="{{ route('contracts.users.activate', ['contract_id' => $contract->id, 'owner_id' => session('owner_id')]) }}"
                                                    class="btn btn-primary btn-sm"><i class="fas fa-check-circle"></i>
                                                    Activate</a>
                                            </td>
                                        </tr>
                                        @php $activePlans++; @endphp
                                    @endif
                                @endforeach
                                @if ($activePlans == 0)
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No Active Plans</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            {{-- Previous Plans --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body table-responsive">
                        <h5 class="card-title text-center fs-4 text-primary"><i class="fas fa-history"></i> Previous Plans
                        </h5>
                        <table class="table table-striped" id="Table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Plan</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Duration</th>
                                    <th>Amount</th>
                                    <th>Payment Status</th>
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
                                            <td>{{ \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) }}
                                                days
                                                ({{ \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30 }}
                                                Month)
                                            </td>
                                            <td>{{ number_format((\Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30) * $contract->package->price) }}
                                            </td>
                                            <td>{{ $contract->payment_status }}</td>
                                            <td>{{ $contract->status }}
                                                @if ($contract->is_current_contract)
                                                    <span class="badge bg-success">Current</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                {{-- @if ($contracts->count() == 0)
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No Plans</td>
                                    </tr>
                                @endif --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========================= AVAILABLE SUBSCRIPTION PLANS ========================= --}}
        <div class="mt-5">
            <h3 class="text-center text-primary fw-bold mb-4"><i class="fas fa-layer-group"></i> Available Subscription Plans</h3>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Plan</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($packages as $package)
                            @php
                                $activeContracts = \App\Models\Contract::where('owner_id', session('owner_id'))
                                    ->where('is_current_contract', 1)
                                    ->get();
                                $hasAnyContract = \App\Models\Contract::where('owner_id', session('owner_id'))->count();
                                $activatableContract = \App\Models\Contract::where('owner_id', session('owner_id'))
                                    ->where('package_id', $package->id)
                                    ->where('status', 'active')
                                    ->where('payment_status', 'payed')
                                    ->where('is_current_contract', 0)
                                    ->first();

                                $blockUpgrade = !is_null($activatableContract);
                                // last contract that is status = inactive, payment_status = payed and is_current_contract = 0
                                $lastExpiredContract = \App\Models\Contract::where('owner_id', session('owner_id'))
                                    ->where('package_id', $package->id)
                                    ->where('status', 'inactive')
                                    ->where('payment_status', 'payed')
                                    ->where('end_date', '<', now()) // ensure truly expired
                                    ->where('is_current_contract', 0)
                                    ->orderBy('end_date', 'desc')
                                    ->first();

                            @endphp
                            <tr class="{{ $hasAnyContract > 0 && $package->id == 1 ? 'd-none' : '' }}">
                                <td>{{ $package->name }}</td>
                                <td>TZS {{ number_format($package->price) }}</td>
                                <td>{{ $package->duration }} days</td>
                                <td class="d-flex flex-wrap align-items-center gap-2">

                                    {{-- ========================= CHECK: NO ACTIVE CONTRACTS? (Subscribe State) ========================= --}}
                                    @if ($activeContracts->count() < 1)
                                        {{-- Month selection for new subscription --}}
                                        <select class="form-select form-select-sm me-2 w-auto"
                                            id="months_{{ $package->id }}" required>
                                            <option value="">Select months</option>

                                            {{-- Loop months 1–12 --}}
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}">{{ $i }}
                                                    month{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                        </select>

                                        {{-- Subscribe button --}}
                                        <button type="button"
                                            class="btn btn-primary btn-sm rounded-pill shadow subscribe-btn"
                                            data-url="{{ route('contracts.users.subscribe', ['package_id' => $package->id, 'owner_id' => session('owner_id')]) }}">
                                            <i class="fa-solid fa-badge-check"></i> Subscribe
                                        </button>
                                    @else
                                        {{-- ========================= HAS ACTIVE CONTRACT(S) ========================= --}}

                                        {{-- Check if the active contract belongs to this package --}}
                                        @if ($activeContracts->first()->package->id == $package->id)
                                            @php
                                                // The user currently has an active contract for this package
                                                $currentContract = $activeContracts->first();

                                                // Get its end date
                                                $endDate = \Carbon\Carbon::parse($currentContract->end_date);

                                                // If a previously expired contract (same package) exists,
                                                // use its end_date (used for renewal logic)
                                                if ($lastExpiredContract) {
                                                    $endDate = \Carbon\Carbon::parse($lastExpiredContract->end_date);
                                                }
                                            @endphp

                                            {{-- ========================= CONTRACT END DATE CHECK (Renew or Current Plan) ========================= --}}
                                            @if ($endDate->isPast() && $currentContract->status == 'inactive')
                                                {{-- Contract expired → Show Renew --}}
                                                <select class="form-select form-select-sm me-2 w-auto"
                                                    id="months_{{ $package->id }}" required>
                                                    <option value="">Select months</option>
                                                    @for ($i = 1; $i <= 12; $i++)
                                                        <option value="{{ $i }}">{{ $i }}
                                                            month{{ $i > 1 ? 's' : '' }}</option>
                                                    @endfor
                                                </select>

                                                <button type="button"
                                                    class="btn btn-warning btn-sm rounded-pill shadow subscribe-btn"
                                                    data-url="{{ route('contracts.users.renew', ['package_id' => $package->id, 'owner_id' => session('owner_id')]) }}">
                                                    <i class="fa-solid fa-clock-rotate-left"></i> Renew
                                                </button>
                                            @else
                                                {{-- Contract still active → user cannot renew --}}
                                                <button type="button" class="btn btn-success btn-sm rounded-pill" disabled>
                                                    Current Plan
                                                </button>
                                            @endif
                                        @else
                                            {{-- ========================= USER HAS ACTIVE CONTRACT BUT FOR ANOTHER PACKAGE ========================= --}}

                                            {{-- If upgrade is blocked, allow "Activate" previous contract --}}
                                            @if ($blockUpgrade)
                                                <a href="{{ route('contracts.users.activate', ['contract_id' => $activatableContract->id, 'owner_id' => session('owner_id')]) }}"
                                                    class="btn btn-primary btn-sm rounded-pill shadow">
                                                    <i class="fas fa-check-circle"></i> Activate
                                                </a>
                                            @else
                                                {{-- Show Upgrade option --}}
                                                <select class="form-select form-select-sm me-2 w-auto"
                                                    id="months_{{ $package->id }}" required>
                                                    <option value="">Select months</option>

                                                    @for ($i = 1; $i <= 12; $i++)
                                                        <option value="{{ $i }}">{{ $i }}
                                                            month{{ $i > 1 ? 's' : '' }}</option>
                                                    @endfor
                                                </select>

                                                <button type="button"
                                                    class="btn btn-primary btn-sm rounded-pill shadow subscribe-btn"
                                                    data-url="{{ route('contracts.users.upgrade', ['package_id' => $package->id, 'owner_id' => session('owner_id')]) }}">
                                                    <i class="fa-solid fa-arrow-trend-up"></i> Upgrade
                                                </button>
                                            @endif
                                        @endif
                                    @endif

                                    {{-- ========================= PACKAGE DETAILS MODAL BUTTON ========================= --}}
                                    <a href="#" class="btn btn-danger btn-sm text-white rounded-pill"
                                        data-bs-toggle="modal" data-bs-target="#packageModal{{ $package->id }}">
                                        <i class="bi bi-eye"></i> More
                                    </a>

                                </td>

                            </tr>

                            {{-- Modal --}}
                            <div class="modal fade" id="packageModal{{ $package->id }}" tabindex="-1"
                                aria-labelledby="packageModalLabel{{ $package->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $package->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">Package Details</h5>
                                                    <p class="card-text">
                                                        <strong>Name:</strong> {{ $package->name }}<br>
                                                        <strong>Price:</strong> {{ $package->price }}<br>
                                                        <strong>Description:</strong> {{ $package->description }}<br>
                                                        <strong>Duration:</strong> {{ $package->duration }} days<br>
                                                        <strong>Features:</strong>
                                                    </p>
                                                    <ul class="list-unstyled mb-0">
                                                        <li>✔ {{ $package->number_of_pharmacists }} pharmacist per 1
                                                            Pharmacy</li>
                                                        <li>✔ {{ $package->number_of_owner_accounts }} Owner account</li>
                                                        <li>✔ {{ $package->number_of_admin_accounts }} Admin account</li>
                                                        <li>✔ {{ $package->number_of_pharmacies }} pharmacy</li>
                                                        <li>✔ {{ $package->number_of_medicines }} Medicines per Pharmacy
                                                        </li>
                                                        @if ($package->email_notification)
                                                            <li>✔ Email Notification</li>
                                                        @endif
                                                        @if ($package->sms_notifications)
                                                            <li>✔ SMS Notifications</li>
                                                        @endif
                                                        @if ($package->whatsapp_chat)
                                                            <li>✔ WhatsApp Chat</li>
                                                        @endif
                                                        @if ($package->reports)
                                                            <li>✔ Sales Reporting</li>
                                                        @endif
                                                        @if ($package->analytics)
                                                            <li>✔ Sales Analytics</li>
                                                        @endif
                                                        @if ($package->receipts)
                                                            <li>✔ Receipts</li>
                                                        @endif
                                                        @if ($package->stock_management)
                                                            <li>✔ Stocks Management</li>
                                                        @endif
                                                        @if ($package->staff_management)
                                                            <li>✔ Staffs Management</li>
                                                        @endif
                                                        @if ($package->stock_transfer)
                                                            <li>✔ Stocks Transfer</li>
                                                        @endif
                                                        <li>✔ Free Online support</li>
                                                        <li>✔ Works on PC, Mac, mobile and Tablet</li>
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if ($packages->count() == 0)
                            <tr>
                                <td colspan="4" class="text-center text-muted">No Packages</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    <script>
        // Set the date we're counting down to
        var countDownDate = new Date("{{ $current_contract_end_date }}").getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get the current date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="countdown"
            document.getElementById("countdown").innerHTML = days + "d " + hours + "h " +
                minutes + "m " + seconds + "s ";

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "EXPIRED";
            }
        }, 1000);

        // document.addEventListener('DOMContentLoaded', function() {
        //     document.querySelectorAll('.subscribe-btn').forEach(function(button) {
        //         button.addEventListener('click', function() {
        //             const url = button.dataset.url;
        //             const packageId = url.match(/package_id=(\d+)/)?.[1];
        //             const select = document.querySelector(`#months_${packageId}`);
        //             const months = select.value;

        //             if (!months) {
        //                 alert('Please select the number of months first!');
        //                 return;
        //             }

        //             // Redirect with months as query parameter
        //             window.location.href = `${url}&months=${months}`;
        //         });
        //     });
        // });
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.subscribe-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const url = button.dataset.url;
                    const packageId = url.match(/package_id=(\d+)/)?.[1];
                    const select = document.querySelector(`#months_${packageId}`);
                    const months = select.value;

                    // Remove previous red border if user fixed it
                    select.style.border = '';

                    if (!months) {
                        // Show alert
                        alert('Please select the number of months first!');
                        // Highlight the select box with a red border
                        select.style.border = '2px solid red';
                        // Scroll to it (optional for better UX)
                        select.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        return;
                    }

                    // Redirect with months as query parameter
                    window.location.href = `${url}&months=${months}`;
                });

                // Automatically remove red border when user makes a valid selection
                const packageId = button.dataset.url.match(/package_id=(\d+)/)?.[1];
                const select = document.querySelector(`#months_${packageId}`);
                if (select) {
                    select.addEventListener('change', function() {
                        if (select.value) {
                            select.style.border = '';
                        }
                    });
                }
            });
        });
    </script>
@endsection
