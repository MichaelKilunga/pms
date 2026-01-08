@extends('superAdmin.users.app')

@section('content')
    <div class="container">
        <div class="container mt-4">
            <h1 class="m-2 text-center">Manage Pharmacies</h1>
            <hr class="my-2">
            <div class="text-end mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPharmacyModal">
                    <i class="bi bi-plus-lg"></i> Add More Pharmacy to Exists Owner
                </button>
            </div>
            <table class="table-striped small table" id="Table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Owner</th>
                        <th>Created at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pharmacies as $pharmacy)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pharmacy->name }}</td>
                            <td>{{ $pharmacy->location }}</td>
                            <td>{{ $pharmacy->owner->name }}</td>
                            <td>{{ $pharmacy->created_at }}</td>
                            <td>
                                <a class="btn btn-warning"
                                    href="{{ route('superadmin.pharmacies.edit', $pharmacy->id) }}"><i
                                        class="bi bi-pencil"></i></a>
                                <a class="btn btn-success"
                                    href="{{ route('superadmin.pharmacies.show', $pharmacy->id) }}"><i
                                        class="bi bi-eye"></i></a>
                                <form action="{{ route('superadmin.pharmacies.delete', $pharmacy->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" onclick='return confirm("Do you want to delete?")'
                                        type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Pharmacy Modal -->
    <div class="modal fade" id="addPharmacyModal" tabindex="-1" aria-labelledby="addPharmacyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('superadmin.pharmacies.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPharmacyModalLabel">Add New Pharmacy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Pharmacy Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <textarea class="form-control" id="location" name="location" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="owner_id" class="form-label">Owner</label>
                            <select class="form-select" id="owner_id" name="owner_id" required>
                                <option value="" selected disabled>Select Owner</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->name }} ({{ $owner->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Pharmacy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
