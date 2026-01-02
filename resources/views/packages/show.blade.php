@extends("packages.app")

@section("content")
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-primary fw-bold mb-0">Package Details</h1>
                    <div>
                        <a class="btn btn-outline-secondary rounded-pill me-2" href="{{ route("packages") }}">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                        <a class="btn btn-primary rounded-pill shadow-sm" href="{{ route("packages.edit", $package->id) }}">
                            <i class="bi bi-pencil me-1"></i> Edit Package
                        </a>
                    </div>
                </div>

                <div class="card rounded-4 mb-4 overflow-hidden border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-8">
                                <h2 class="fw-bold text-dark mb-1">{{ $package->name }}</h2>
                                <p class="text-muted mb-0">
                                    <span class="badge bg-primary rounded-pill me-2 px-3 py-2">TZS
                                        {{ number_format($package->price) }}</span>
                                    <span class="badge bg-secondary rounded-pill me-2 px-3 py-2">{{ $package->duration }}
                                        Days</span>
                                    @if ($package->status)
                                        <span class="badge bg-success rounded-pill px-3 py-2">Active</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3 py-2">Inactive</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <form action="{{ route("packages.destroy", $package->id) }}" class="d-inline" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this package?');">
                                    @csrf
                                    @method("DELETE")
                                    <button
                                        class="btn btn-outline-danger rounded-pill {{ $package->id == 1 ? "disabled" : "" }}"
                                        type="submit">
                                        <i class="bi bi-trash me-1"></i> Delete Package
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Resource Allocations -->
                    <div class="col-md-6">
                        <div class="card rounded-4 h-100 border-0 shadow-sm">
                            <div class="card-header border-bottom bg-white p-3">
                                <h6 class="fw-bold text-uppercase text-muted small mb-0"><i
                                        class="bi bi-speedometer2 me-2"></i> Resource Limits</h6>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <span>Pharmacies</span>
                                        <span class="fw-bold">{{ number_format($package->number_of_pharmacies) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <span>Pharmacists</span>
                                        <span class="fw-bold">{{ number_format($package->number_of_pharmacists) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <span>Owner Accounts</span>
                                        <span class="fw-bold">{{ number_format($package->number_of_owner_accounts) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <span>Admin Accounts</span>
                                        <span
                                            class="fw-bold">{{ number_format($package->number_of_admin_accounts) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <span>Medicines Limit</span>
                                        <span class="fw-bold">{{ number_format($package->number_of_medicines) }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Enabled Modules -->
                    <div class="col-md-6">
                        <div class="card rounded-4 h-100 border-0 shadow-sm">
                            <div class="card-header border-bottom bg-white p-3">
                                <h6 class="fw-bold text-uppercase text-muted small mb-0"><i class="bi bi-grid me-2"></i>
                                    Module Access</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    @php
                                        $modules = [
                                            "reports" => "Reports",
                                            "stock_transfer" => "Stock Transfer",
                                            "stock_management" => "Stock Management",
                                            "staff_management" => "Staff Management",
                                            "receipts" => "Receipts",
                                            "analytics" => "Analytics",
                                            "whatsapp_chats" => "WhatsApp",
                                            "online_support" => "Support",
                                            "in_app_notification" => "App Notif.",
                                            "email_notification" => "Email Notif.",
                                            "sms_notifications" => "SMS Notif.",
                                        ];
                                    @endphp

                                    @foreach ($modules as $field => $label)
                                        <div class="col-md-6">
                                            <div
                                                class="d-flex align-items-center justify-content-between {{ $package->$field ? "bg-success-subtle border-success-subtle" : "bg-light text-muted" }} rounded border p-2">
                                                <span class="small fw-bold">{{ $label }}</span>
                                                @if ($package->$field)
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @else
                                                    <i class="bi bi-x-circle text-muted" style="opacity: 0.5;"></i>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
