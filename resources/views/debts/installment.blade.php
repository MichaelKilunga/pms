@extends('debts.app')

@section('content')
    <div class="row">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Installments</h1>
            <!-- Optional: Add Installment Button -->
                    <a href="{{ route('debts.index') }}" class="btn btn-secondary mb-3"><span class="bi bi-arrow-left"></span> Back to Debts</a>

        </div>

        <div class="table-reponsive">
            <!-- Table of Installments -->
            <table class="table table-bordered mt-3" id="Table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Stock</th>
                        {{-- <th>Debt Amount</th> --}}
                        <th>Installment Amount</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($installments as $installment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $installment->debt->stock->item->name }}({{$installment->debt->stock->batch_number}}-{{ $installment->debt->stock->supplier }})</td>
                            {{-- <td>{{ number_format($installment->debt->debtAmount) }}</td> --}}
                            <td>{{ number_format($installment->amount) }}</td>
                            <td>{{ $installment->created_at->format('Y-m-d H:i') }}</td>
                            <td>  
                               <form action="{{ route('installments.destroyinst', $installment->id) }}" method   ="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this debt?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><span class="bi bi-trash"></span></button>
                               </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Installment Modal -->
    <div class="modal fade" id="addInstallmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('installments.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Installment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Select Debt</label>
                            <select name="debt_id" class="form-control" required>
                                <option value="">-- Select Debt --</option>
                                @foreach ($debts as $debt)
                                    <option value="{{ $debt->id }}">
                                        Debt #{{ $debt->id }} - {{ $debt->stock->supplier }} (Qty:
                                        {{ $debt->debtAmount }} | Status: {{ $debt->status }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                    </div>
                       <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Installment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
    </script>
@endsection
