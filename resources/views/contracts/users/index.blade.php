@extends('contracts.app')

@section('content')
    <div class="container mt-4">
        {{-- Show owner his current subscription contract plan --}}
        <div class="row d-flex justify-content-between">
            <div class="card col-md-4">
                {{-- Show current contract on the left  --}}
                <div class="card-body">
                    <h5 class="card-title  text-center fs-4 text-primary">Current Plan</h5>
                    @if ($contracts->count() > 0)
                        @foreach ($contracts as $contract)
                            @if ($contract->is_current_contract)
                                <h5 class="card-title" style="color: #007bff;">{{ $contract->package->name }}</h5>
                                @if (!session('agent'))
                                    <p class="card-text">Price: TZS {{ number_format($contract->package->price) }}</p>
                                @endif
                                <p class="card-text">Duration: {{ $contract->package->duration }} days</p>
                                <p class="card-text">Start Date: {{ $contract->start_date }}</p>
                                <p class="card-text">End Date: {{ $contract->end_date }}</p>
                                {{-- Display animated countdown of remained time before plan expire --}}
                                <p class="card-text">Time Remaining: <span class="text-danger" id="countdown"></span></p>
                                <p class="card-text">Status: {{ $contract->status }} <small
                                        class="text-warning">{{ $contract->status == 'graced' ? \Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans() : '' }}</small>
                                </p>
                                <p class="card-text">Payment: {{ $contract->payment_status }}</p>
                                {{-- <a href="{{ route('contracts.users.upgrade') }}" class="btn btn-primary">Change Plan</a> --}}
                            @endif
                        @endforeach
                    @else
                        <p class="card-text">No active plan</p>
                    @endif
                    <hr>
                </div>
                {{-- implement a simple table(columns: name of package, and action to activate) to show active contracts and payed but are not the current one, an action button should --}}
                {{-- be available to activate the contract --}}
                <div class="card-body table-responsive">
                    <h5 class="card-title text-center fs-4 text-primary">Active Plans</h5>
                    <table class="table" id="Table#">
                        <thead>
                            <tr>
                                <th scope="col">Plan</th>
                                {{-- <th scope="col">Price</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Status</th> --}}
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($contracts)
                                @foreach ($contracts as $contract)
                                    @if ($contract->is_current_contract == 0 && $contract->payment_status == 'payed' && $contract->status != 'inactive')
                                        <tr>
                                            <td>{{ $contract->package->name }}</td>
                                            {{-- <td>TZS {{ number_format($contract->package->price) }}</td>
                                            <td>{{ $contract->package->duration }} days</td>
                                            <td>{{ $contract->start_date }}</td>
                                            <td>{{ $contract->end_date }}</td>
                                            <td>{{ $contract->payment_status }}</td>
                                            <td>{{ $contract->status }}</td> --}}
                                            <td>
                                                <a href="{{ route('contracts.users.activate', ['contract_id' => $contract->id, 'owner_id' => Auth::user()->id]) }}"
                                                    class="btn btn-primary">Activate</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">No plans</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- show previous contracts on the right in a table form --}}
            <div class="card col-md-8">
                <div class="card-body table-responsive">
                    <h5 class="card-title text-center fs-4 text-primary">Previous Plans</h5>
                    <table class="table" id="Table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Plan</th>
                                @if (!session('agent'))
                                    <th scope="col">Price</th>
                                @endif
                                <th scope="col">Duration</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($contracts)
                                @foreach ($contracts as $contract)
                                    @if (!$contract->is_current_contract && $contract->payment_status != 'pending')
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $contract->package->name }}</td>
                                            @if (!session('agent'))
                                                <td>TZS {{ number_format($contract->package->price) }}</td>
                                            @endif
                                            <td>{{ $contract->package->duration }} days</td>
                                            <td>{{ $contract->start_date }}</td>
                                            <td>{{ $contract->end_date }}</td>
                                            <td>{{ $contract->payment_status }}</td>
                                            <td>{{ $contract->status }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">No plans</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- list all packages in a table with an action to upgrade to this package --}}

        @if (!session('agent'))
            <div class="mt-4">
                <h1 class="text-center fs-4 text-primary">Available Subscription Plans</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Plan</th>
                            <th scope="col">Price</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($packages)
                            @foreach ($packages as $package)
                                {{-- @if ($package->id != 1) --}}
                                {{-- count active contracts, if is less than 1, show subscribe button otherwise upgrade button --}}
                                @php
                                    $activeContracts = Auth::user()->contracts->where('is_current_contract', 1);
                                    $hasAnyContract = Auth::user()->contracts->count();
                                @endphp
                                <tr class="{{ $hasAnyContract > 0 && $package->id == 1 ? 'hidden' : '' }}">
                                    <td>{{ $package->name }}</td>
                                    <td>TZS {{ number_format($package->price) }}</td>
                                    <td>{{ $package->duration }} days</td>
                                    <td>
                                        @if ($activeContracts->count() < 1)
                                            <a href="{{ route('contracts.users.subscribe', ['package_id' => $package->id, 'owner_id' => Auth::user()->id]) }}"
                                                class="btn btn-primary">Subscribe</a>
                                        @endif
                                        @if ($activeContracts->count() > 0)
                                            @if ($activeContracts->first()->package->id == $package->id)
                                                @if ($activeContracts->first()->end_date < now())
                                                    <a href="{{ route('contracts.users.renew', ['package_id' => $package->id, 'owner_id' => Auth::user()->id]) }}"
                                                        class="btn btn-danger">Re-new</a>
                                                @else
                                                    <p class="text-success i">current!</p>
                                                @endif
                                            @else
                                                <a href="{{ route('contracts.users.upgrade', ['package_id' => $package->id, 'owner_id' => Auth::user()->id]) }}"
                                                    class="btn btn-primary">Upgrade</a>
                                            @endif
                                        @endif

                                        {{-- @if ($activeContracts->count() > 0 && $activeContracts->first()->package->id != $package->id)
                                    <a href="{{ route('contracts.users.upgrade', ['package_id'=>$package->id, 'owner_id'=>Auth::user()->id]) }}"
                                        class="btn btn-success">Renew</a>
                                    @endif --}}
                                    </td>
                                </tr>
                                {{-- @endif --}}
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No available subscription plans</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @endif
        @if (session('agent'))
        @php
            $agent = session('agentData');
        @endphp
            {{-- Display Agent details from variable $agent --}}
            <div class="mt-4">
                <h1 class="text-center fs-4 text-primary">Your Agent Details</h1>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Agent Name</th>
                            <th scope="col">Agent Email</th>
                            <th scope="col">Agent Phone</th>
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
        @endif
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
    </script>
@endsection
