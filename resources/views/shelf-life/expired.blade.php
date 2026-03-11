@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold text-danger mb-1"><i class="bi bi-calendar-x me-2"></i> Expired Stock</h3>
                            <p class="text-muted small">Medicines that have passed their "Use By" or "Expiry" date.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="expiredTable">
                            <thead class="bg-light">
                                <tr>
                                    <th>Medicine Name</th>
                                    <th>Batch Number</th>
                                    <th>Expiry Date</th>
                                    <th>Total Qty</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expiredStocks as $stock)
                                    <tr>
                                        <td class="fw-medium">{{ $stock->item->name ?? 'Unknown Item' }}</td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">{{ $stock->batch_number }}</span></td>
                                        <td class="text-danger fw-bold">{{ $stock->expire_date }}</td>
                                        <td>{{ number_format($stock->remain_Quantity) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                                data-bs-toggle="modal" data-bs-target="#disposeModal{{ $stock->id }}">
                                                <i class="bi bi-trash-fill me-1"></i> Dispose
                                            </button>

                                            <!-- Disposal Request Modal -->
                                            <div class="modal fade" id="disposeModal{{ $stock->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow rounded-4">
                                                        <form action="{{ route('shelf-life.dispose') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                                                            <div class="modal-header border-0 bg-danger text-white p-4">
                                                                <h5 class="modal-title fw-bold">Request Disposal</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body p-4">
                                                                <p class="mb-4">Are you sure you want to remove <strong>{{ $stock->item->name ?? 'Unknown Item' }}</strong> (Batch: {{ $stock->batch_number }}) from active stock?</p>
                                                                
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-medium">Quantity to Dispose</label>
                                                                    <input type="number" name="quantity" class="form-control rounded-3" value="{{ $stock->remain_Quantity }}" max="{{ $stock->remain_Quantity }}" min="1" required>
                                                                    <div class="form-text">Max available: {{ $stock->remain_Quantity }}</div>
                                                                </div>
                                                                
                                                                <div class="alert alert-warning small border-0 shadow-sm">
                                                                    <i class="bi bi-info-circle-fill me-2"></i> This item will be moved to the disposal store and await owner approval.
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-0 p-4 pt-0">
                                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger rounded-pill px-4">Submit Request</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
    $('#expiredTable').DataTable({
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
