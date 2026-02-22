@extends('contracts.app')

@section('content')
    <div class="container my-4">
        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center p-3">
                <h4 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i> Edit Contract</h4>
                <a href="{{ route('contracts.admin.index') }}" class="btn btn-sm btn-light rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('contracts.admin.update', $contract->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Owner & Package --}}
                        <div class="col-md-6 text-left">
                            <label class="form-label fw-bold small text-uppercase">Owner</label>
                            <select name="owner_id" class="form-select" required>
                                @foreach($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ $contract->owner_id == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 text-left">
                            <label class="form-label fw-bold small text-uppercase">Package</label>
                            <select name="package_id" class="form-select" required>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ $contract->package_id == $package->id ? 'selected' : '' }}>
                                        {{ $package->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dates --}}
                        <div class="col-md-6 text-left">
                            <label class="form-label fw-bold small text-uppercase">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 text-left">
                            <label class="form-label fw-bold small text-uppercase">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') }}" required>
                        </div>

                        {{-- Status & Payment --}}
                        <div class="col-md-4 text-left">
                            <label class="form-label fw-bold small text-uppercase">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ $contract->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $contract->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="graced" {{ $contract->status == 'graced' ? 'selected' : '' }}>Graced</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-left">
                            <label class="form-label fw-bold small text-uppercase">Payment Status</label>
                            <select name="payment_status" class="form-select" required>
                                <option value="payed" {{ $contract->payment_status == 'payed' ? 'selected' : '' }}>Paid</option>
                                <option value="unpayed" {{ $contract->payment_status == 'unpayed' ? 'selected' : '' }}>Unpaid</option>
                                <option value="pending" {{ $contract->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-left">
                            <label class="form-label fw-bold small text-uppercase">Is Current Plan?</label>
                            <select name="is_current_contract" class="form-select" required>
                                <option value="1" {{ $contract->is_current_contract ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$contract->is_current_contract ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        {{-- Special Add-ons Section --}}
                        <div class="col-12 mt-4">
                            <div class="p-3 bg-light rounded border border-info">
                                <h6 class="fw-bold text-info mb-3"><i class="fas fa-plus-circle me-2"></i> Manual Add-ons (No Extra Charge)</h6>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_whatsapp" id="editWhatsapp" 
                                            {{ ($contract->details['has_whatsapp'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="editWhatsapp">
                                            Enable WhatsApp Alerts
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="has_sms" id="editSms"
                                            {{ ($contract->details['has_sms'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="editSms">
                                            Enable SMS Alerts
                                        </label>
                                    </div>
                                </div>
                                <small class="text-muted mt-2 d-block">Checking these will enable features on the current contract without generating a new bill.</small>
                            </div>
                        </div>

                    </div>

                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                            Update Contract
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
