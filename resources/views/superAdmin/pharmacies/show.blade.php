@extends('superAdmin.users.app')

@section('content')
    <div class="container container-fluid mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Pharmacy Details</h3>
                <a href="{{ route('superadmin.pharmacies') }}" class="btn btn-secondary">Back to List</a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="fw-bold">ID:</label>
                    <p>{{ $pharmacy->id }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Name:</label>
                    <p>{{ $pharmacy->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Location:</label>
                    <p>{{ $pharmacy->location ?? 'N/A' }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Owner:</label>
                    <p>{{ $pharmacy->owner->name ?? 'Unknown' }} ({{ $pharmacy->owner->email ?? 'N/A' }})</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Status:</label>
                    <p>
                        <span class="badge bg-{{ $pharmacy->status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($pharmacy->status) }}
                        </span>
                    </p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Created At:</label>
                    <p>{{ $pharmacy->created_at->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('superadmin.pharmacies.edit', $pharmacy->id) }}" class="btn btn-warning">Edit Pharmacy</a>
            </div>
        </div>
    </div>
@endsection
