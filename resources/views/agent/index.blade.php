@extends('agent.app')

@section('content')
    <div class="row">
        {{-- Quick Actions, add pharmacy(opens modal  to create pharmacy), add package(open modal to create package), message, report case --}}
        <div class="col-md-4">
            <h2 class="h2 mt-2 text-center text-primary">Quick Actions</h2>
            <div class="text-primary p-2 mx-4">
                <ul class="list-group">
                    <li class="list-group-item text-primary mb-2">
                        <button data-bs-toggle="modal" data-bs-target="#addPharmacyModal"
                            class="btn# btn-outline-primary# rounded-lg# shadow-md#">&gt; Add Pharmacy</button>
                    </li>
                    <li class="list-group-item text-primary mb-2">
                        <button data-toggle="modal" data-target="#addPackageModal"
                            class="btn# btn-outline-secondary# rounded-lg# shadow-md#"> &gt; Add a new package</button>
                    </li>
                    <li class="list-group-item text-primary mb-2">
                        <a href="{{ route('agent.messages', ['action' => 'index']) }}"
                            class="btn# btn-outline-success# rounded# shadow-md#">&gt; Message</a>
                    </li>
                    <li class="list-group-item text-primary mb-2">
                        <a href="{{ route('agent.cases', ['action' => 'index']) }}"
                            class="btn# btn-outline-warning# text-dark# rounded-lg# shadow-md#">&gt; Report
                            Case</a>
                    </li>
                </ul>
            </div>
        </div>
        {{-- end of quick actions --}}

        {{-- summary cards showing number of pharmacies, packages, active pharmacies, New messages, client's case filed and Inactive pharmacies --}}
        <div class="col-md-8">
            <h2 class="h2 mt-2 text-center text-primary">Summary</h2>
            <div class="text-light mx-2 p-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-primary rounded shadow-md p-2">
                    <h2 class="font-semibold mb-2">All Pharmacies</h2>
                    <p class="text-white-700">{{ $totalPharmacies }}</p>
                </div>
                <div class="bg-secondary rounded shadow-md p-2">
                    <h2 class="font-semibold mb-2">Packages</h2>
                    <p class="text-white-700">{{ $totalPackages }}</p>
                </div>
                <div class="bg-danger rounded shadow-md p-2">
                    <h2 class="font-semibold mb-2">Active Pharmacies</h2>
                    <p class="text-white-700">{{ $activePharmacies }}</p>
                </div>
                <div class="bg-success rounded shadow-md p-2">
                    <h2 class="font-semibold mb-2">New Messages</h2>
                    <p class="text-white-700">{{ $totalMessages }}</p>
                </div>
                <div class="bg-warning text-dark rounded shadow-md p-2">
                    <h2 class="font-semibold mb-2">Client's Cases</h2>
                    <p class="text-gray-700">{{ $totalCases }}</p>
                </div>
                <div class="bg-white text-dark rounded shadow-md p-2">
                    <h2 class="font-semibold mb-2">Inactive Pharmacies</h2>
                    <p class="text-gray-700">{{ $inactivePharmacies }}</p>
                </div>
            </div>
        </div>
        {{-- end of summary cards --}}
    </div>

    {{-- Draw a graph of number of pharmacies Vs the income generated --}}
    <div class="row">
        <div class="col-md-12">
            <h2 class="h2 mt-2 text-center text-primary">Pharmacies Vs Income</h2>
            <div class="text-light mx-2 p-2">
                <canvas id="pharmaciesVsIncomeChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Modal to create a new pharmacy -->
        <div class="modal fade" id="addPharmacyModal" tabindex="-1" aria-labelledby="addPharmacyModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title  text-primary" id="addPharmacyModalLabel">Add a new pharmacy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('agent.pharmacies.store', ['action' => 'create']) }}" method="POST">
                            @csrf
                            <x-validation-errors class="mb-4" />
                            <div class="row">
                                <div class="col-6">
                                    <h2 class="h5 text-primary">Pharmacy Details</h2>
                                    <div class="mb-3">
                                        <x-label for="pharmacy_name" value="Name" class="form-label" />
                                        <x-input type="text" class="form-control rounded" id="pharmacy_name"
                                            name="pharmacy_name" placeholder="Pill Pharmacy" :value="old('pharmacy_name')" required />
                                    </div>
                                    <div class="mb-3">
                                        <x-label for="location" class="form-label" value="Location" />
                                        <x-input type="text" :value="old('location')" class="form-control rounded"
                                            id="location" name="location" placeholder="Morogoro" />
                                    </div>
                                    <div class="mb-3">
                                        <x-label for="status" class="form-label" value="Status" />
                                        <select class="form-select rounded" id="status" name="status" required>
                                            <option {{ old('status') == 'active' ? 'selected' : '' }} value="active">Active
                                            </option>
                                            <option {{ old('status') == 'inactive' ? 'selected' : '' }} value="inactive">
                                                Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h2 class="h5 text-primary">Owner Details</h2>
                                    <div>
                                        <x-label for="name" value="{{ __('Name') }}" />
                                        <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                            :value="old('name')" required autofocus autocomplete="name"
                                            placeholder="Pill Point" />
                                    </div>

                                    <div class="mt-4">
                                        <x-label for="email" value="{{ __('Email') }}" />
                                        <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                            :value="old('email')" required autocomplete="username"
                                            placeholder="info@pillpoint.com" />
                                    </div>

                                    <div class="mt-4">
                                        <x-label for="phone_number" value="{{ __('Phone Number') }}" />
                                        <x-input id="phone_number" class="block mt-1 w-full" type="tel"
                                            name="phone_number" :value="old('phone_number')" required placeholder="0742177328"
                                            autocomplete="phone_number" />
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

        {{-- script for id="pharmaciesVsIncomeChart" --}}
        <script>
            $(document).ready(function() {
                var ctx = document.getElementById('pharmaciesVsIncomeChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: {
                            ['Pharmacy 1', 'Pharmacy 2', 'Pharmacy 3', 'Pharmacy 4', 'Pharmacy 5'],
                        },
                        datasets: [{
                                label: 'Income',
                                data: {
                                    [100, 200, 150, 300, 250],
                                },
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                    x: {
                                        ticks: {
                                            autoSkip: false,
                                            maxRotation: 90,
                                            minRotation: 90
                                        }
                                    }
                                }
                            }
                        ]
                    }
                });
            });
        </script>
    @endsection
