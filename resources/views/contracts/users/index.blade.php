@extends('contracts.app')

@section('content')

    <div class="container my-4">

        {{-- ========================= CURRENT PLAN ========================= --}}
        <div class="row g-4">

            {{-- CURRENT PLAN CARD --}}
            <div class="col-md-4">
                <div class="card shadow border-0 rounded-3">
                    <div class="card-body">

                        <h4 class="text-center text-primary mb-4 fw-bold">
                            <i class="fas fa-bolt"></i> Current Plan
                        </h4>

                        @if ($contracts->count() > 0)
                            @foreach ($contracts as $contract)
                                @if ($contract->is_current_contract)
                                    <div class="p-3 border rounded bg-light mb-3 shadow-sm">
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
                                        {{ number_format($contract->package->price) }} / {{ $contract->package->duration }}
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
                                @endif
                            @endforeach
                        @else
                            <p class="text-muted text-center">No Active Plan</p>
                        @endif

                        <hr class="my-4">

                        {{-- ========================= ACTIVE BUT NOT CURRENT ========================= --}}
                        <h5 class="text-center text-primary fw-bold mb-3">
                            <i class="fas fa-sync-alt"></i> Active Plans (Not Current)
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
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
                                                    <a href="{{ route('contracts.users.activate', ['contract_id' => $contract->id, 'owner_id' => Auth::user()->id]) }}"
                                                        class="btn btn-primary btn-sm rounded-pill shadow">
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
                <div class="card shadow border-0 rounded-3">
                    <div class="card-body">

                        <h4 class="text-center text-primary mb-4 fw-bold">
                            <i class="fas fa-history"></i> Payment History
                        </h4>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="Table">
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

        {{-- ========================= AVAILABLE PLANS ========================= --}}
        @if (!session('agent'))
            <div class="mt-5">
                <h3 class="text-center text-primary fw-bold mb-4">
                    <i class="fas fa-gift"></i> Available Subscription Plans
                </h3>

                <div class="card shadow border-0">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-hover" id="Table2">
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
                                            $activeContracts = Auth::user()->contracts->where('is_current_contract', 1);
                                            $hasAnyContract = Auth::user()->contracts->count();
                                        @endphp

                                        <tr class="{{ $hasAnyContract > 0 && $package->id == 1 ? 'd-none' : '' }}">
                                            <td class="fw-bold text-primary">{{ $package->name }}</td>
                                            <td>TZS {{ number_format($package->price) }}</td>
                                            <td>{{ $package->duration }} days</td>
                                            <td>

                                                @if ($activeContracts->count() < 1)
                                                    <a href="{{ route('contracts.users.subscribe', ['package_id' => $package->id, 'owner_id' => Auth::user()->id]) }}"
                                                        class="btn btn-primary btn-sm rounded-pill shadow">
                                                        Subscribe
                                                    </a>
                                                @endif

                                                @if ($activeContracts->count() > 0)
                                                    @if ($activeContracts->first()->package->id == $package->id)
                                                        @if ($activeContracts->first()->end_date < now())
                                                            <a href="{{ route('contracts.users.renew', ['package_id' => $package->id, 'owner_id' => Auth::user()->id]) }}"
                                                                class="btn btn-danger btn-sm rounded-pill shadow">
                                                                Renew
                                                            </a>
                                                        @else
                                                            <span class="badge bg-success">Current</span>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('contracts.users.upgrade', ['package_id' => $package->id, 'owner_id' => Auth::user()->id]) }}"
                                                            class="btn btn-primary btn-sm rounded-pill shadow">
                                                            Upgrade
                                                        </a>
                                                    @endif
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>

            </div>
        @endif

        {{-- ========================= AGENT DETAILS ========================= --}}
        @if (session('agent'))
            @php $agent = session('agentData'); @endphp

            <div class="mt-5">
                <h3 class="text-center text-primary fw-bold mb-4">
                    <i class="fas fa-user-tie"></i> Your Agent Details
                </h3>

                <div class="card shadow border-0">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
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
