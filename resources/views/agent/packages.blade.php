@extends('agent.app')

@section('content')
    <div class="container mt-4">
        <div class="container">
            <div class="flex justify-between">
                <h2 class="h3 text-primary">Select Owner to Manage</h2>
                <span
                    class="{{ session('owner') ? 'text-success' : 'text-danger' }}">{{ session('owner') ? session('owner') : 'Select Owner' }}</span>
            </div>
            <form action="{{ route('agent.packages.manage', ['action' => 'index']) }}" method="post">
                @csrf
                <div class="row mt-2">
                    <div class="col-8">
                        <select class="form-select rounded" id="owner_id" name="owner_id" required>
                            <option value="">--Select Owner--</option>
                            @foreach ($owners as $owner)
                                <option {{ session('owner_id') == $owner->id ? 'selected' : '' }}
                                    value="{{ $owner->id }}">
                                    {{ $owner->name }} ({{ $owner->phone }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <div></div>
                        <button type="submit" class="btn btn-primary">Manage</button>
                    </div>
                </div>
            </form>
        </div>

        <hr class="mt-4">

        {{-- Show owner his current subscription contract plan --}}
        <div class="row mt-4 d-flex justify-content-between">
            <div class="card col-md-4">
                {{-- Show current contract on the left  --}}
                <div class="card-body">
                    <h5 class="card-title  text-center fs-4 text-primary">Current Plan</h5>
                    @if ($contracts->count() > 0)
                        @foreach ($contracts as $contract)
                            @if ($contract->is_current_contract)
                                <h5 class="card-title" style="color: #007bff;">{{ $contract->package->name }}</h5>
                                <p class="card-text">Price: TZS {{ number_format($contract->package->price) }}</p>
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
                        <p class="card-text">No activated plan</p>
                    @endif
                    <hr>
                </div>
                {{-- implement a simple table(columns: name of package, and action to activate) to show active contracts and payed but are not the current one, an action button should --}}
                {{-- be available to activate the contract --}}
                <div class="card-body table-responsive">
                    <h5 class="card-title text-center fs-4 text-primary">Activate Plan</h5>
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
                            @php $activePlans = 0; @endphp
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
                                                <a href="{{ route('contracts.users.activate', ['contract_id' => $contract->id, 'owner_id' => session('owner_id')]) }}"
                                                    class="btn btn-primary">Activate</a>
                                            </td>
                                        </tr>
                                        @php $activePlans++; @endphp
                                    @endif
                                @endforeach
                            @endif
                            @if ($activePlans == 0)
                                <tr>
                                    <td colspan="6">No Active plans</td>
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
                                <th scope="col">Price</th>
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
                                            {{-- format in currency format with commas --}}
                                            <td>TZS {{ number_format($contract->package->price) }}</td>
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
                                $activeContracts = \App\Models\Contract::where('owner_id', session('owner_id'))
                                    ->where('is_current_contract', 1)
                                    ->with('package');
                                $hasAnyContract = \App\Models\Contract::where('owner_id', session('owner_id'))->count();
                            @endphp
                            <tr class="{{ $hasAnyContract > 0 && $package->id == 1 ? 'hidden' : '' }}">
                                <td>{{ $package->name }}</td>
                                <td>TZS {{ number_format($package->price) }}</td>
                                <td>{{ $package->duration }} days</td>
                                <td>
                                    @if ($activeContracts->count() < 1)
                                        <a href="{{ route('contracts.users.subscribe', ['package_id' => $package->id, 'owner_id' => session('owner_id')]) }}"
                                            class="btn btn-primary">Subscribe</a>
                                    @endif
                                    @if ($activeContracts->count() > 0)
                                        @if ($activeContracts->first()->package->id == $package->id)
                                            @if ($activeContracts->first()->end_date < now())
                                                <a href="{{ route('contracts.users.renew', ['package_id' => $package->id, 'owner_id' => session('owner_id')]) }}"
                                                    class="btn btn-danger">Re-new</a>
                                            @else
                                                <p class="text-success i">current!</p>
                                            @endif
                                        @else
                                            <a href="{{ route('contracts.users.upgrade', ['package_id' => $package->id, 'owner_id' => session('owner_id')]) }}"
                                                class="btn btn-primary">Upgrade</a>
                                        @endif
                                    @endif
                                    {{-- show the package details in a modal --}}
                                    <a href="#" class="btn text-light btn-danger btn-info" data-bs-toggle="modal"
                                        data-bs-target="#packageModal{{ $package->id }}"><i class="bi bi-eye" ></i> More</a>
                                </td>
                            </tr>

                            {{-- Modal to show package details USE ABOVE particulars: --}}
                            <div class="modal fade" id="packageModal{{ $package->id }}" tabindex="-1"
                                aria-labelledby="packageModalLabel{{ $package->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="packageModalLabel{{ $package->id }}">
                                                {{ $package->name }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                {{-- <span aria-hidden="true">&times;</span> --}}
                                            </button>
                                        </div>
                                        <div class="modal-body">    
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">Package Details</h5>
                                                    <p class="card-text">
                                                        <strong>Name:</strong> {{ $package->name }}
                                                        <br>
                                                        <strong>Price:</strong> {{ $package->price }}
                                                        <br>
                                                        <strong>Description:</strong> {{ $package->description }}
                                                        <br>
                                                        <strong>Duration:</strong> {{ $package->duration }}
                                                        <br>
                                                        <strong>Features:</strong>
                                                    <ul class="list-unstyled ">
                                                        <li>✔ {{ $package->number_of_pharmacists }} pharmacist per
                                                            1 Pharmacy</li>
                                                        <li>✔ {{ $package->number_of_owner_accounts }} Owner
                                                            account</li>
                                                        <li>✔ {{ $package->number_of_admin_accounts }} Admin
                                                            account</li>
                                                        <li>✔ {{ $package->number_of_pharmacies }} pharmacy</li>
                                                        <li>✔ {{ $package->number_of_medicines }} Medicines per
                                                            Pharmacy</li>
                                                        <li>✔ In App Notification </li>
                                                        @if ($package->email_notification)
                                                            <li>✔ Email Notification </li>
                                                        @endif
                                                        @if ($package->sms_notifications)
                                                            <li>✔ SMS Notifications </li>
                                                        @endif
                                                        @if ($package->whatsapp_chat)
                                                            <li>✔ WhatsApp Chat </li>
                                                        @endif
                                                        @if ($package->reports)
                                                            <li>✔ Sales Reporting </li>
                                                        @endif
                                                        @if ($package->analytics)
                                                            <li>✔ Sales Analytics </li>
                                                        @endif
                                                        @if ($package->receipts)
                                                            <li>✔ Receipts </li>
                                                        @endif
                                                        @if ($package->stock_management)
                                                            <li>✔ Stocks Management </li>
                                                        @endif
                                                        @if ($package->staff_management)
                                                            <li>✔ Staffs Management </li>
                                                        @endif
                                                        @if ($package->stock_transfer)
                                                            <li>✔ Stocks Transfer </li>
                                                        @endif
                                                        <li>✔ Free Online support</li>
                                                        <li>✔ Works on PC, Mac, mobile and Tablet</li>
                                                    </ul>
                                                    <br>
                                                    </p>
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
                    @else
                        <tr>
                            <td colspan="4">No plan</td>
                        </tr>
                    @endif
                </tbody>
            </table>
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
    </script>
@endsection
