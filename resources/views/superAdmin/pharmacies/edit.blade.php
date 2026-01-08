@extends('superAdmin.users.app')

@section('content')
    <div class="container container-fluid mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Edit Pharmacy</h3>
                <a href="{{ route('superadmin.pharmacies') }}" class="btn btn-secondary">Back to List</a>
            </div>
            <div class="card-body">
                <form action="{{ route('superadmin.pharmacies.update', $pharmacy->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Pharmacy Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $pharmacy->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <textarea class="form-control" id="location" name="location" rows="3">{{ old('location', $pharmacy->location) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="owner_id" class="form-label">Owner</label>
                        <select class="form-select" id="owner_id" name="owner_id" required>
                            <option value="" disabled>Select Owner</option>
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}"
                                    {{ $pharmacy->owner_id == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }} ({{ $owner->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Update Pharmacy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
