@extends('layouts.app')

@section('title', 'Shelf Life Durability Tracking')

@section('content')
<div class="row g-4">
    <div class="col-12 mb-2">
        <h3 class="fw-bold text-dark"><i class="bi bi-hourglass-split me-2 text-primary"></i> Shelf Life Durability Tracker</h3>
        <p class="text-muted small">Tracking medicinal shelf stability ensures patient safety and minimizes financial losses. Monitoring short-dated items and disposing of expired ones protects your business integrity.</p>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-danger-subtle text-danger p-3 me-3 fs-4">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Expired Stock</h5>
                        <small class="text-muted">Requires Immediate Action</small>
                    </div>
                </div>
                <div class="display-5 fw-bold text-danger mb-3">{{ number_format($expiredCount) }}</div>
                <p class="text-muted small mb-4">Stock that has passed its "Use By" or "Expiry" date and must be removed.</p>
                <a href="{{ route('shelf-life.expired') }}" class="btn btn-outline-danger w-100 rounded-pill px-4">
                    View Expired Stock <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-warning-subtle text-warning p-3 me-3 fs-4">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Short-Dated Stock</h5>
                        <small class="text-muted">Approaching Expiry</small>
                    </div>
                </div>
                <div class="display-5 fw-bold text-warning mb-3">{{ number_format($shortDatedCount) }}</div>
                <p class="text-muted small mb-4">Monitoring stock that is nearing its end-of-life cycle for faster liquidation.</p>
                <a href="{{ route('shelf-life.short-dated') }}" class="btn btn-outline-warning w-100 rounded-pill px-4">
                    Check Short-Dated Stock <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-secondary-subtle text-secondary p-3 me-3 fs-4">
                        <i class="bi bi-trash"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Disposal Management</h5>
                        <small class="text-muted">Legal Disposal Logs</small>
                    </div>
                </div>
                <div class="display-5 fw-bold text-secondary mb-3">{{ number_format($pendingDisposalCount) }}</div>
                <p class="text-muted small mb-4">Records of expired medicine removed from the main active inventory for destruction.</p>
                <a href="{{ route('shelf-life.disposed') }}" class="btn btn-outline-secondary w-100 rounded-pill px-4">
                    Manage Disposals @if($pendingDisposalCount > 0) <span class="badge bg-danger rounded-pill ms-2">{{ $pendingDisposalCount }} Pending</span> @endif
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Chart / Info Section -->
    <div class="col-12 mt-5">
        <div class="card border-0 shadow bg-primary text-white rounded-5 p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="fw-bold mb-2">Automated Shelf Life Integrity</h4>
                    <p class="mb-0 small">The system periodically checks your inventory against current system dates. Configure threshold alerts in <strong>Settings</strong> to customize your short-dated notifications to fit your workflow needs.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('settings.index') }}" class="btn btn-light px-4 rounded-pill"> <i class="bi bi-gear me-1"></i> Configure System</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
