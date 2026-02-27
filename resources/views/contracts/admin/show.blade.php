@extends('contracts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-primary fw-bold mb-0">Contract Details</h1>
        <div>
            <a href="{{ route('contracts.admin.index') }}" class="btn btn-outline-secondary rounded-pill me-2">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
            <a href="{{ route('contracts.admin.edit', $contract->id) }}" class="btn btn-warning rounded-pill shadow-sm">
                <i class="bi bi-pencil me-1"></i> Edit Contract
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Contract Basic Info -->
        <div class="col-md-6">
            <div class="card rounded-4 border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="fw-bold text-uppercase text-muted small mb-0"><i class="bi bi-file-earmark-text me-2"></i> Information</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between p-3">
                            <span class="text-muted">Owner:</span>
                            <span class="fw-bold text-dark">{{ $contract->owner->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-3">
                            <span class="text-muted">Package:</span>
                            <span class="badge bg-primary rounded-pill px-3">{{ $contract->package->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-3">
                            <span class="text-muted">Amount:</span>
                            <span class="fw-bold text-success">TZS {{ number_format($contract->amount) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-3">
                            <span class="text-muted">Pricing Strategy:</span>
                            <span class="text-capitalize">{{ str_replace('_', ' ', $contract->pricing_strategy) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-3">
                            <span class="text-muted">Current Contract:</span>
                            @if($contract->is_current_contract)
                                <span class="badge bg-success rounded-pill px-3">Yes</span>
                            @else
                                <span class="badge bg-light text-muted border px-3">No</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Status & Dates -->
        <div class="col-md-6">
            <div class="card rounded-4 border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="fw-bold text-uppercase text-muted small mb-0"><i class="bi bi-calendar-event me-2"></i> Status & Timeline</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between p-3">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-{{ $contract->status == 'active' ? 'success' : ($contract->status == 'inactive' ? 'secondary' : 'warning') }} rounded-pill px-3 text-capitalize">
                                {{ $contract->status }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-3">
                            <span class="text-muted">Payment Status:</span>
                            <span class="badge bg-{{ $contract->payment_status == 'payed' ? 'success' : 'warning' }} rounded-pill px-3 text-capitalize">
                                {{ $contract->payment_status }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-3">
                            <span class="text-muted">Start Date:</span>
                            <span>{{ \Carbon\Carbon::parse($contract->start_date)->format('M d, Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-3">
                            <span class="text-muted">End Date:</span>
                            <span class="{{ \Carbon\Carbon::parse($contract->end_date)->isPast() ? 'text-danger fw-bold' : '' }}">
                                {{ \Carbon\Carbon::parse($contract->end_date)->format('M d, Y') }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between p-3">
                            <span class="text-muted">Days Remained:</span>
                            @if(\Carbon\Carbon::parse($contract->end_date)->isFuture())
                                <span class="text-primary fw-bold">{{ \Carbon\Carbon::parse($contract->end_date)->diffInDays(now()) }} Days</span>
                            @else
                                <span class="text-danger">Expired</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Details / JSON View -->
        <div class="col-12 mt-4">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="fw-bold text-uppercase text-muted small mb-0"><i class="bi bi-code-square me-2"></i> Configuration Details</h6>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded-3 mb-0" style="font-size: 0.85rem;">{{ json_encode($contract->details, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
