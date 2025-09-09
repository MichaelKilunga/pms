@extends('superAdmin.app')

@section('content')
    @if (Auth::user()->role == 'super')
        <div class="container-fluid mt-4">
            <h2 class="mb-4 text-center fw-bold">Super Admin Dashboard</h2>

            <div class="row g-4">
                <!-- Packages -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-box fa-3x text-primary mb-3"></i>
                            <h5 class="card-title fw-bold">Packages</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-success">
                                    <h4>12</h4>
                                    <small>Active</small>
                                </div>
                                <div class="text-danger">
                                    <h4>5</h4>
                                    <small>Inactive</small>
                                </div>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm rounded-pill">View Packages</a>
                        </div>
                    </div>
                </div>

                <!-- Pharmacies -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-clinic-medical fa-3x text-success mb-3"></i>
                            <h5 class="card-title fw-bold">Pharmacies</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-success">
                                    <h4>25</h4>
                                    <small>Active</small>
                                </div>
                                <div class="text-danger">
                                    <h4>8</h4>
                                    <small>Inactive</small>
                                </div>
                            </div>
                            <a href="#" class="btn btn-success btn-sm rounded-pill">View Pharmacies</a>
                        </div>
                    </div>
                </div>

                <!-- Contracts -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-file-contract fa-3x text-warning mb-3"></i>
                            <h5 class="card-title fw-bold">Contracts</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-success">
                                    <h4>18</h4>
                                    <small>Active</small>
                                </div>
                                <div class="text-danger">
                                    <h4>6</h4>
                                    <small>Expired</small>
                                </div>
                            </div>
                            <a href="#" class="btn btn-warning btn-sm rounded-pill">Manage Contracts</a>
                        </div>
                    </div>
                </div>

                <!-- System Users -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x text-info mb-3"></i>
                            <h5 class="card-title fw-bold">System Users</h5>
                            <div class="d-flex justify-content-center my-3">
                                <div class="text-info">
                                    <h4>120</h4>
                                    <small>Total Users</small>
                                </div>
                            </div>
                            <a href="#" class="btn btn-info btn-sm rounded-pill">View Users</a>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-envelope fa-3x text-danger mb-3"></i>
                            <h5 class="card-title fw-bold">Messages</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-primary">
                                    <h4>34</h4>
                                    <small>Unread</small>
                                </div>
                                <div class="text-muted">
                                    <h4>200</h4>
                                    <small>Total</small>
                                </div>
                            </div>
                            <a href="#" class="btn btn-danger btn-sm rounded-pill">View Messages</a>
                        </div>
                    </div>
                </div>

                <!-- Agents -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-tie fa-3x text-dark mb-3"></i>
                            <h5 class="card-title fw-bold">Agents</h5>
                            <div class="d-flex justify-content-around my-3">
                                <div class="text-success">
                                    <h4>10</h4>
                                    <small>Active</small>
                                </div>
                                <div class="text-danger">
                                    <h4>2</h4>
                                    <small>Inactive</small>
                                </div>
                            </div>
                            <a href="#" class="btn btn-dark btn-sm rounded-pill">View Agents</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
