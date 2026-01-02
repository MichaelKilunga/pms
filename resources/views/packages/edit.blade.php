@extends("packages.app")

@section("content")
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-primary fw-bold mb-0">Edit Package: {{ $package->name }}</h1>
                    <a class="btn btn-outline-secondary rounded-pill" href="{{ route("packages") }}">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card rounded-4 overflow-hidden border-0 shadow-sm">
                    <div class="card-header border-bottom bg-white p-4">
                        <h5 class="fw-bold text-dark mb-0">Package Configuration</h5>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route("packages.update", $package->id) }}" method="POST">
                            @csrf
                            @method("PUT")

                            <!-- Section 1: Basic Information -->
                            <h6 class="text-uppercase text-muted fw-bold small mb-3">Basic Information</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="name">Package Name</label>
                                    <input class="form-control" id="name" name="name" required type="text"
                                        value="{{ $package->name }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold" for="price">Price (TZS)</label>
                                    <input class="form-control" id="price" name="price" required type="number"
                                        value="{{ $package->price }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold" for="duration">Duration (Days)</label>
                                    <input class="form-control" id="duration" name="duration" required type="number"
                                        value="{{ $package->duration }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" for="status">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option {{ $package->status ? "selected" : "" }} value="1">Active</option>
                                        <option {{ !$package->status ? "selected" : "" }} value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="text-muted my-4">

                            <!-- Section 2: Account Limits -->
                            <h6 class="text-uppercase text-muted fw-bold small mb-3">Resource Limits</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label text-muted small">No. of Pharmacies</label>
                                    <input class="form-control" name="number_of_pharmacies" required type="number"
                                        value="{{ $package->number_of_pharmacies }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small">No. of Pharmacists</label>
                                    <input class="form-control" name="number_of_pharmacists" required type="number"
                                        value="{{ $package->number_of_pharmacists }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small">No. of Owner Accounts</label>
                                    <input class="form-control" name="number_of_owner_accounts" required type="number"
                                        value="{{ $package->number_of_owner_accounts }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small">No. of Admin Accounts</label>
                                    <input class="form-control" name="number_of_admin_accounts" required type="number"
                                        value="{{ $package->number_of_admin_accounts }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small">No. of Medicines</label>
                                    <input class="form-control" name="number_of_medicines" required type="number"
                                        value="{{ $package->number_of_medicines }}">
                                </div>
                            </div>

                            <hr class="text-muted my-4">

                            <!-- Section 3: Module Access (Switches) -->
                            <h6 class="text-uppercase text-muted fw-bold small mb-3">Module Access & Features</h6>
                            <div class="row g-4">
                                @php
                                    $modules = [
                                        "reports" => "Reports Module",
                                        "stock_transfer" => "Stock Transfer",
                                        "stock_management" => "Stock Management",
                                        "staff_management" => "Staff Management",
                                        "receipts" => "Receipt Printing",
                                        "analytics" => "Analytics Dashboard",
                                        "whatsapp_chats" => "WhatsApp Integration",
                                        "online_support" => "Online Support",
                                        "in_app_notification" => "In-App Notifications",
                                        "email_notification" => "Email Notifications",
                                        "sms_notifications" => "SMS Notifications",
                                    ];
                                @endphp

                                @foreach ($modules as $field => $label)
                                    <div class="col-md-4">
                                        <div
                                            class="form-check form-switch d-flex align-items-center justify-content-between bg-light m-0 rounded border p-0 p-3">
                                            <label class="form-check-label fw-bold mb-0"
                                                for="{{ $field }}">{{ $label }}</label>
                                            <input name="{{ $field }}" type="hidden" value="0">
                                            <input {{ $package->$field ? "checked" : "" }}
                                                class="form-check-input ms-2 mt-0" id="{{ $field }}"
                                                name="{{ $field }}" role="switch" style="transform: scale(1.2);"
                                                type="checkbox" value="1">
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-end mt-5">
                                <a class="btn btn-secondary rounded-pill me-2 px-4"
                                    href="{{ route("packages") }}">Cancel</a>
                                <button class="btn btn-primary rounded-pill fw-bold px-5 shadow" type="submit">Save
                                    Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
