@extends('contracts.app')

@section('content')
    <div class="container mt-4">
        {{-- Show owner his current subscription contract plan --}}
        <div class="row d-flex justify-content-between">
            <div class="card col-md-4">
                {{-- Show current contract on the left  --}}
                <div class="card-body">
                    <h5 class="card-title  text-center fs-4 text-primary">Current Subscription Plan</h5>
                    @if ($contracts)
                        @foreach ($contracts as $contract)
                            @if ($contract->is_current_contract)
                                <h5 class="card-title" style="color: #007bff;">{{ $contract->package->name }}</h5>
                                <p class="card-text">Price: TZS {{ number_format($contract->package->price) }}</p>
                                <p class="card-text">Duration: {{ $contract->package->duration }} days</p>
                                <p class="card-text">Start Date: {{ $contract->start_date }}</p>
                                <p class="card-text">End Date: {{ $contract->end_date }}</p>
                                <p class="card-text">Status: {{ $contract->status }}</p>
                                <a href="{{ route('contracts.users.create') }}" class="btn btn-primary">Change Plan</a>
                            @endif
                        @endforeach
                    @else
                        <p class="card-text">You have no active subscription plan</p>
                        <a href="{{ route('contracts.users.create') }}" class="btn btn-primary">Subscribe</a>
                    @endif
                </div>
            </div>
            {{-- show previous contracts on the right in a table form --}}
            <div class="card col-md-8"> 
                <div class="card-body">
                    <h5 class="card-title text-center fs-4 text-primary">Previous Subscription Plans</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Plan</th>
                                <th scope="col">Price</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($contracts)
                                @foreach ($contracts as $contract)
                                    @if (!$contract->is_current_contract)
                                        <tr>
                                            <td>{{ $contract->package->name }}</td>
                                            {{-- format in currency format with commas --}}
                                            <td>TZS {{ number_format($contract->package->price) }}</td>
                                            <td>{{ $contract->package->duration }} days</td>
                                            <td>{{ $contract->start_date }}</td>
                                            <td>{{ $contract->end_date }}</td>
                                            <td>{{ $contract->status }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">No previous subscription plans</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- list all packages in a table with an action to upgrade to this package --}}

        <div class="mt-4">
            <h1 class="text-center fs-4 text-primary" >Available Subscription Plans</h1>
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
                            <tr>
                                <td>{{ $package->name }}</td>
                                <td>TZS {{ number_format($package->price) }}</td>
                                <td>{{ $package->duration }} days</td>
                                <td>
                                    <a href="{{ route('contracts.users.create', $package->id) }}"
                                        class="btn btn-primary">Subscribe</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No available subscription plans</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
