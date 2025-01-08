@extends('contracts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-2">
        <h1>All Contracts</h1>
            <a href="{{ route('contracts.admin.create') }}" class="btn btn-primary">Create Contract</a>
        </div>
        
    <table class="table mt-4 table-striped" id="Table">
        <thead>
            <tr>
                <th>#</th>
                <th>Owner</th>
                <th>Package</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contracts as $contract)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contract->owner->name }}</td>
                    <td>{{ $contract->package->name }}</td>
                    <td>{{ $contract->status }}</td>
                    <td>{{ $contract->payment_status }}</td>
                    <td>
                        {{-- below create a a view button and a modal to view contract when view action button is clicked --}}
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $contract->id }}" href="#"><i class="bi bi-eye" ></i></a>
                        {{-- modal --}}
                        <div class="modal fade " id="viewModal{{ $contract->id }}" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewModalLabel">View Contract</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-left">
                                        <p><strong>Owner:</strong> {{ $contract->owner->name }}</p>
                                        <p><strong>Package:</strong> {{ $contract->package->name }}</p>
                                        <p><strong>Status:</strong> {{ $contract->status }}</p>
                                        <p><strong>Payment Status:</strong> {{ $contract->payment_status }}</p>
                                        <p><strong>Start Date:</strong> {{ $contract->start_date }}</p>
                                        <p><strong>End Date:</strong> {{ $contract->end_date }}</p>
                                        <p><strong>Grace Period:</strong> {{ $contract->grace_period }}</p>
                                        <p><strong>Created At:</strong> {{ $contract->created_at }}</p>
                                        <p><strong>Updated At:</strong> {{ $contract->updated_at }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        {{-- action button to confirm payement --}}
                        <a class="btn btn-success"  onclick="return confirm('Do you want to confirm payement?')" href="{{ route('contracts.admin.confirm', $contract->id) }}"><i class="bi bi-cash-coin" ></i></a>
                        {{-- action button to grace a grace period --}}
                        <a class="btn btn-warning" onclick="return prompt('How many days do you want to add?')" href="{{ route('contracts.admin.grace', $contract->id) }}"><i class="bi bi-clock" ></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
