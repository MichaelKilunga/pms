@extends('superAdmin.app')

@section('content')
    @if (Auth::user()->role == 'super')
        <div class="container-fluid mt-4">
            <h2 class="mb-4 text-center fw-bold">Super Admin Dashboard</h2>

            <div class="row g-4">
                <!-- Pharmacies -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-hospital fa-3x text-success mb-3"></i>
                            <h5 class="card-title fw-bold">Total Pharmacies</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-danger">
                                    <h4>{{ $totalPharmacies }}</h4>
                                    <small>Total Pharmacies</small>
                                </div>
                            </div>
                            <a href="{{ route('superadmin.pharmacies') }}" class="btn btn-success btn-sm rounded-pill">View Pharmacies</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-hospital fa-3x text-success mb-3"></i>
                            <h5 class="card-title fw-bold">Working Pharmacies</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-success">
                                    <h4>{{ $countactivepharmacies }}</h4>
                                    <small>Active 6 Hours Ago</small>
                                </div>
                            </div>
                            <a href="{{ route('superadmin.pharmacies') }}" class="btn btn-success btn-sm rounded-pill">View Pharmacies</a>
                        </div>
                    </div>
                </div>

                {{-- medicines --}}
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-capsule fa-3x text-secondary mb-3"></i>
                            <h5 class="card-title fw-bold">Medicine</h5>
                            <div class="d-flex justify-content-around my-3">
                                {{-- <div class="text-success">
                                    <h4>{{ $activePackages }}</h4>
                                    <small>Active</small>
                                </div>
                                <div class="text-danger">
                                    <h4>{{ $inactivePackages }}</h4>
                                    <small>Inactive</small>
                                </div> --}}
                            </div>
                            <a href="{{ route('allMedicines.all') }}" class="btn btn-primary btn-sm rounded-pill">View Packages</a>
                        </div>
                    </div>
                </div>


                <!-- Packages -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-box fa-3x text-primary mb-3"></i>
                            <h5 class="card-title fw-bold">Packages</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-success">
                                    <h4>{{ $activePackages }}</h4>
                                    <small>Active</small>
                                </div>
                                <div class="text-danger">
                                    <h4>{{ $inactivePackages }}</h4>
                                    <small>Inactive</small>
                                </div>
                            </div>
                            <a href="{{ route('packages') }}" class="btn btn-primary btn-sm rounded-pill">View Packages</a>
                        </div>
                    </div>
                </div>

                <!-- Contracts -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-file-text text-warning mb-3"></i>
                            <h5 class="card-title fw-bold">Contracts</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-success">
                                    <h4>{{ $activeContracts }}</h4>
                                    <small>Active</small>
                                </div>
                                <div class="text-danger">
                                    <h4>{{ $expiredContracts }}</h4>
                                    <small>Expired</small>
                                </div>
                            </div>
                            <a href="{{ route('contracts') }}" class="btn btn-warning btn-sm rounded-pill">Manage Contracts</a>
                        </div>
                    </div>
                </div>

                <!-- System Users -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-person text-info mb-3"></i>
                            <h5 class="card-title fw-bold">System Users</h5>
                            <div class="d-flex justify-content-center my-3">
                                <div class="text-info">
                                    <h4>{{ $users }}</h4>
                                    <small>Total Users</small>
                                </div>
                            </div>
                            <a href="{{ route('superadmin.users') }}" class="btn btn-info btn-sm rounded-pill">View Users</a>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-envelope fa-3x text-danger mb-3"></i>
                            <h5 class="card-title fw-bold">Messages</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-primary">
                                    <h4>-</h4>
                                    <small>Unread</small>
                                </div>
                                <div class="text-muted">
                                    <h4>-</h4>
                                    <small>Total</small>
                                </div>
                            </div>
                            <a href="{{ route('agent.messages', ['action' => 'index']) }}" class="btn btn-danger btn-sm rounded-pill">View Messages</a>
                        </div>
                    </div>
                </div>

                <!-- Agents -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-person-badge text-dark mb-3"></i>
                            <h5 class="card-title fw-bold">Agents</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-success">
                                    <h4>{{ $agents }}</h4>
                                    <small>Active</small>
                                </div>
                            </div>
                            <a href="{{ route('superadmin.users') }}" class="btn btn-dark btn-sm rounded-pill">View Agents</a>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="bi bi-bell text-danger mb-3"></i>
                            <h5 class="card-title fw-bold">Notifications</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-success">
                                    <h4>0</h4>
                                    <small>Active</small>
                                </div>
                            </div>
                            <a href="{{ route('notifications') }}" class="btn btn-danger btn-sm rounded-pill">View Notifications</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endif
@endsection
