@extends('superAdmin.medicines.app')

@section('content')
    <div class="container">
        <div class="card mt-5 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Edit Medicine</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('allMedicines.update', $medicine->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $medicine->name) }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="approved" {{ $medicine->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ $medicine->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ $medicine->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Update</button>
                    <a href="{{ route('allMedicines.all') }}" class="btn btn-secondary mt-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
