@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 text-end mb-3">
            <a href="{{ route('settings.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                <i class="bi bi-gear me-1"></i> Configure Threshold ({{ $shortDatedDays }} days)
            </a>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold text-primary mb-1"><i class="bi bi-clock-history me-2"></i> Short-Dated Stock</h3>
                            <p class="text-muted small">Items approaching their expiry date within the next {{ $shortDatedDays }} days.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="shortDatedTable">
                            <thead class="bg-light">
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Batch Number</th>
                                    <th>Expiry Date</th>
                                    <th>Days to Expiry</th>
                                    <th>Remaining Qty</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shortDatedStocks as $stock)
                                    @php
                                        $expireDate = $stock->expire_date ? \Carbon\Carbon::parse($stock->expire_date) : null;
                                        $diff = $expireDate ? \Carbon\Carbon::now()->diffInDays($expireDate, false) : 0;
                                        $badgeColor = $diff < 30 ? 'bg-danger text-white' : 'bg-primary text-white';
                                    @endphp
                                    <tr>
                                        <td class="fw-medium">{{ $stock->item->name ?? 'Unknown Item' }}</td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $stock->batch_number }}</span></td>
                                        <td class="fw-bold">{{ $stock->expire_date }}</td>
                                        <td><span class="badge {{ $badgeColor }}">{{ round($diff) }} days left</span></td>
                                        <td>{{ number_format($stock->remain_Quantity) }}</td>
                                        <td class="small">
                                            <span class="text-muted italic">Monitor for fast liquidation</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#shortDatedTable').DataTable({
        pageLength: 25,
        ordering: true,
        language: {
            searchPlaceholder: "Search medicine...",
            search: ""
        }
    });
});
</script>
@endsection
