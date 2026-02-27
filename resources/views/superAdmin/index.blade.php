@extends('superAdmin.app')

@section('content')
    @hasrole('Superadmin')
        <style>
            .quick-card {
                transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
                cursor: pointer;
                border-radius: 16px;
                /* Slightly more rounded */
                overflow: hidden;
                border: 1px solid rgba(255, 255, 255, 0.1);
                /* Subtle border for glassmorphism cue */
                position: relative;
                backdrop-filter: blur(5px);
                /* Premium feel if over bg image */
            }

            .quick-card:hover {
                transform: translateY(-4px) scale(1.02);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15), 0 5px 15px rgba(0, 0, 0, 0.1) !important;
                z-index: 10;
            }

            .quick-card .card-body {
                padding: 1.25rem 1rem;
                /* Reduced padding */
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: white;
                height: 100%;
                /* Ensure full height usage */
            }

            .quick-card i {
                font-size: 2rem;
                /* Reduced from 3rem */
                margin-bottom: 0.25rem;
                opacity: 0.9;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .quick-card h5 {
                font-weight: 600;
                font-size: 1rem;
                /* Explicitly set easier reading size */
                margin-bottom: 0.25rem;
                letter-spacing: 0.5px;
                text-align: center;
            }

            .quick-card .stat-value {
                font-size: 1.25rem;
                /* Reduced from 1.5rem */
                font-weight: 800;
                margin-bottom: 0px;
            }

            .quick-card small {
                font-size: 0.75rem;
                opacity: 0.8;
                font-weight: 500;
            }

            /* Refining Gradients for a softer, modern look */
            .bg-gradient-primary {
                background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            }

            .bg-gradient-success {
                background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
            }

            .bg-gradient-info {
                background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
            }

            .bg-gradient-warning {
                background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
                color: #fff;
            }

            .bg-gradient-danger {
                background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
            }

            .bg-gradient-dark {
                background: linear-gradient(135deg, #5a5c69 0%, #373840 100%);
            }

            .bg-gradient-secondary {
                background: linear-gradient(135deg, #858796 0%, #60616f 100%);
            }

            a.quick-card {
                text-decoration: none;
            }
        </style>

        <div class="container-fluid mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-gray-800">Super Admin Dashboard</h2>
                <div class="text-muted">{{ now()->format('l, F j, Y') }}</div>
            </div>

            <!-- Quick Stats Row -->
            <div class="row g-4 mb-5">
                <!-- Total Pharmacies -->
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('superadmin.pharmacies') }}" class="card quick-card bg-gradient-success shadow-sm h-100">
                        <div class="card-body">
                            <i class="bi bi-hospital"></i>
                            <h5>Total Pharmacies</h5>
                            <div class="stat-value">{{ $totalPharmacies }}</div>
                            <small class="opacity-75">View Details</small>
                        </div>
                    </a>
                </div>

                <!-- Active Pharmacies (Derived from sales logic in controller) -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card quick-card bg-gradient-info shadow-sm h-100">
                        <div class="card-body">
                            <i class="bi bi-activity"></i>
                            <h5>Active Pharmacies</h5>
                            <div class="stat-value">{{ $countactivepharmacies }}</div>
                            <small class="opacity-75">Last 6 Hours</small>
                        </div>
                    </div>
                </div>

                <!-- System Users -->
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('superadmin.users') }}" class="card quick-card bg-gradient-primary shadow-sm ">
                        <div class="card-body">
                            <i class="bi bi-people-fill"></i>
                            <h5>System Users</h5>
                            <div class="stat-value">{{ $users }}</div>
                            <small class="opacity-75">Manage Users</small>
                        </div>
                    </a>
                </div>

                <!-- Agents -->
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('superadmin.users') }}" class="card quick-card bg-gradient-dark shadow-sm h-100">
                        <div class="card-body">
                            <i class="bi bi-person-badge"></i>
                            <h5>Agents</h5>
                            <div class="stat-value">{{ $agents }}</div>
                            <small class="opacity-75">View Agents</small>
                        </div>
                    </a>
                </div>
            </div>

            <h4 class="fw-bold text-gray-800 mb-3">Management & Resources</h4>
            <div class="row g-4 mb-4">
                <!-- Packages -->
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('packages') }}" class="card quick-card bg-gradient-secondary shadow-sm h-100">
                        <div class="card-body">
                            <i class="bi bi-box-seam"></i>
                            <h5>Packages</h5>
                            <div class="d-flex w-100 justify-content-around mt-2">
                                <div class="text-center">
                                    <span class="d-block fw-bold">{{ $activePackages }}</span>
                                    <small class="opacity-75">Active</small>
                                </div>
                                <div class="text-center border-start ps-3">
                                    <span class="d-block fw-bold">{{ $inactivePackages }}</span>
                                    <small class="opacity-75">Inactive</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Medicines (All Packages?) -->
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('allMedicines.all') }}" class="card quick-card bg-gradient-warning shadow-sm h-100">
                        <div class="card-body text-white">
                            <i class="bi bi-capsule"></i>
                            <h5>Medicine Database</h5>
                            <small class="opacity-75">View Global List</small>
                        </div>
                    </a>
                </div>

                <!-- Contracts -->
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('contracts.admin.index') }}" class="card quick-card bg-gradient-danger shadow-sm h-100">
                        <div class="card-body">
                            <i class="bi bi-file-earmark-text"></i>
                            <h5>Contracts</h5>
                            <div class="d-flex w-100 justify-content-around mt-2">
                                <div class="text-center">
                                    <span class="d-block fw-bold">{{ $activeContracts }}</span>
                                    <small class="opacity-75">Active</small>
                                </div>
                                <div class="text-center border-start ps-3">
                                    <span class="d-block fw-bold">{{ $expiredContracts }}</span>
                                    <small class="opacity-75">Expired</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Messages -->
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('agent.messages', ['action' => 'index']) }}"
                        class="card quick-card bg-primary shadow-sm h-100" style="background: #6610f2;">
                        <div class="card-body">
                            <i class="bi bi-chat-dots"></i>
                            <h5>Messages</h5>
                            <small class="opacity-75">Check Inbox</small>
                        </div>
                    </a>
                </div>

                <!-- Notifications -->
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('notifications') }}" class="card quick-card bg-secondary shadow-sm h-100">
                        <div class="card-body">
                            <i class="bi bi-bell"></i>
                            <h5>Notifications</h5>
                            <small class="opacity-75">System Alerts</small>
                        </div>
                    </a>
                </div>

                <!-- Manual Broadcasts -->
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="{{ route('superAdmin.notifications.history') }}" class="card quick-card bg-dark shadow-sm h-100" style="background: #e83e8c;">
                        <div class="card-body">
                            <i class="bi bi-broadcast"></i>
                            <h5>Broadcasts</h5>
                            <small class="opacity-75">Notify Users</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endhasrole
@endsection
