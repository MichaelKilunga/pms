@extends('agent.app')

@section('content')
    <div class="container#">
        {{-- Quick Actions, add pharmacy(opens modal  to create pharmacy), add package(open modal to create package), message, report case --}}
        <h2 class="h2  text-center text-primary">Quick Actions</h2>
        <div class="text-light p-2 d-flex justify-content-between">
            <button data-toggle="modal" data-target="#addPharmacyModal"
                class="btn btn-outline-primary rounded-lg shadow-md">Add Pharmacy</button>
            <button data-toggle="modal" data-target="#addPackageModal"
                class="btn btn-outline-secondary rounded-lg shadow-md">Add a new package</button>
            <a href="{{ route('agent.messages') }}" class="btn btn-outline-success rounded shadow-md">Message</a>
            <a href="{{ route('agent.cases') }}" class="btn btn-outline-warning text-dark rounded-lg shadow-md">Report
                Case</a>
        </div>
        {{-- end of quick actions --}}
        <hr>
        {{-- summary cards showing number of pharmacies, packages, active pharmacies, New messages, client's case filed and Inactive pharmacies --}}
        <h2 class="h2 mt-2 text-center text-primary">Summary</h2>
        <div class="text-light p-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-primary rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold mb-2">Total Number of Pharmacies</h2>
                <p class="text-white-700">{{ $totalPharmacies }}</p>
            </div>
            <div class="bg-secondary rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold mb-2">Total Number of Packages</h2>
                <p class="text-white-700">{{ $totalPackages }}</p>
            </div>
            <div class="bg-danger rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold mb-2">Total Number of Active Pharmacies</h2>
                <p class="text-white-700">{{ $activePharmacies }}</p>
            </div>
            <div class="bg-success rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold mb-2">Total Number of New Messages</h2>
                <p class="text-white-700">{{ $totalMessages }}</p>
            </div>
            <div class="bg-warning text-dark rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold mb-2">Total Number of Client's Case Filed</h2>
                <p class="text-gray-700">{{ $totalCases }}</p>
            </div>
            <div class="bg-white text-dark rounded-lg shadow-md p-4">
                <h2 class="text-lg font-semibold mb-2">Total Number of Inactive Pharmacies</h2>
                <p class="text-gray-700">{{ $inactivePharmacies }}</p>
            </div>
        </div>
        {{-- end of summary cards --}}
    </div>
@endsection
