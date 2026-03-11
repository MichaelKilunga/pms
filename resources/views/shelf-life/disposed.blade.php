@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold text-secondary mb-1"><i class="bi bi-trash-fill me-2"></i> Disposed Stock Logs</h3>
                            <p class="text-muted small">Tracking the removal of expired stock from the active inventory.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="disposedTable">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date Requested</th>
                                    <th>Medicine Name</th>
                                    <th>Batch Number</th>
                                    <th>Disposed Qty</th>
                                    <th>Requested By</th>
                                    <th>Status</th>
                                    <th>Action / Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($disposedStocks as $disposed)
                                    <tr>
                                        <td class="small">{{ $disposed->removed_date?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                                        <td class="fw-medium">{{ $disposed->stock->item->name ?? 'Unknown Item' }}</td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $disposed->stock->batch_number ?? 'N/A' }}</span></td>
                                        <td class="fw-bold">{{ number_format($disposed->expired_quantity) }}</td>
                                        <td>{{ $disposed->removedBy->name ?? 'Unknown' }}</td>
                                        <td>
                                            @php
                                                $statusClass = [
                                                    'pending' => 'bg-warning text-dark',
                                                    'approved' => 'bg-success',
                                                    'rejected' => 'bg-danger'
                                                ][$disposed->status];
                                            @endphp
                                            <span class="badge {{ $statusClass }} rounded-pill px-3">{{ ucfirst($disposed->status) }}</span>
                                        </td>
                                        <td>
                                            @if($disposed->status === 'pending')
                                                @hasrole('Owner')
                                                <div class="d-flex gap-2">
                                                    <form action="{{ route('shelf-life.approve-disposal', $disposed->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="action" value="approve">
                                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">Approve</button>
                                                    </form>
                                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $disposed->id }}">Reject</button>
                                                </div>

                                                <!-- Rejection Modal -->
                                                <div class="modal fade" id="rejectModal{{ $disposed->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content rounded-4 border-0">
                                                            <form action="{{ route('shelf-life.approve-disposal', $disposed->id) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="action" value="reject">
                                                                <div class="modal-header border-0 bg-danger text-white p-4">
                                                                    <h5 class="modal-title fw-bold">Reject Disposal Request</h5>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body p-4">
                                                                    <label class="form-label fw-medium">Reason for Rejection</label>
                                                                    <textarea name="reason" class="form-control" placeholder="Optional..." rows="3"></textarea>
                                                                </div>
                                                                <div class="modal-footer border-0 p-4 pt-0">
                                                                    <button type="submit" class="btn btn-danger rounded-pill px-4">Confirm Rejection</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @else
                                                <span class="text-muted italic small">Awaiting Owner Approval</span>
                                                @endhasrole
                                            @elseif($disposed->status === 'approved')
                                                <div class="small">
                                                    <div class="text-success"><i class="bi bi-check2-circle"></i> Approved</div>
                                                    <div class="text-muted small">By {{ $disposed->approvedBy->name ?? 'System' }} at {{ $disposed->approved_at?->format('Y-m-d') ?? 'N/A' }}</div>
                                                </div>
                                            @elseif($disposed->status === 'rejected')
                                                <div class="small">
                                                    <div class="text-danger"><i class="bi bi-x-circle"></i> Rejected</div>
                                                    @if($disposed->rejection_reason)
                                                        <div class="text-muted italic">{{ $disposed->rejection_reason }}</div>
                                                    @endif
                                                </div>
                                            @endif
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
    $('#disposedTable').DataTable({
        pageLength: 25,
        ordering: true,
        order: [[0, 'desc']],
        language: {
            searchPlaceholder: "Search records...",
            search: ""
        }
    });
});
</script>
@endsection
