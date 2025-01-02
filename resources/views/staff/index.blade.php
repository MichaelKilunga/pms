@extends('staff.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Staff</h2>
        <div>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Add New Staff
            </button>
            <!-- <a href="{{ route('staff.create') }}" class="btn btn-success">Add New staff</a> -->
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="Table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($staff as $staff)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $staff->user->name }}</td>
                    <td>{{ $staff->user->email }}</td>
                    <td>{{ $staff->user->phone }}</td>
                    <td>{{ $staff->user->role }}</td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewStaffModal{{ $staff->id }}"><i class="bi bi-eye"></i></a>
                        <div class="modal fade" id="viewStaffModal{{ $staff->id }}" tabindex="-1"
                            aria-labelledby="viewStaffModalLabel{{ $staff->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewStaffModalLabel{{ $staff->id }}">
                                            Staff Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Display Pharmacy Information -->
                                        <div class="mb-3">
                                            <strong>Name:</strong> {{ $staff->user->name }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Email:</strong>
                                            {{$staff->user->email ?? 'No eamil available' }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Phone:</strong>
                                            {{$staff->user->phone ?? 'No phone# available' }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Role:</strong>
                                            {{$staff->user->role}}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Created At:</strong>
                                            {{ $staff->user->created_at->format('d M, Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editStaffModal{{ $staff->id }}"><i class="bi bi-pencil"></i></a>
                        <div class="modal fade" id="editStaffModal{{ $staff->id }}" tabindex="-1"
                            aria-labelledby="editStaffModalLabel{{ $staff->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editStaffModalLabel{{ $staff->id }}">Edit
                                            Staff</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Edit Staff Form -->
                                        <form id="editStaffForm{{ $staff->id }}" method="POST"
                                            action="{{ route('staff.update', $staff->user->id) }}">
                                            @csrf
                                            @method('PUT') <!-- Using PUT to indicate an update -->

                                            <input type="number" name="id" id=""
                                                value="{{ $staff->user_id }}" hidden>

                                            <div class="mb-3">
                                                <label for="name{{ $staff->id }}" class="form-label">Staff
                                                    Name</label>
                                                <input type="text" class="form-control"
                                                    id="name{{ $staff->id }}" name="name"
                                                    value="{{ $staff->user->name }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="name{{ $staff->id }}" class="form-label">Staff
                                                    Phone</label>
                                                <input type="text" class="form-control"
                                                    id="name{{ $staff->id }}" name="phone"
                                                    value="{{ $staff->user->phone }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="name{{ $staff->id }}" class="form-label">Staff
                                                    Email</label>
                                                <input type="text" class="form-control"
                                                    id="name{{ $staff->id }}" name="email"
                                                    value="{{ $staff->user->email }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <input type="hidden" name="pharmacy_id"
                                                    value="{{ session('current_pharmacy_id') }}">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Update staff</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('staff.destroy', $staff->user_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this staff?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" method="POST" action="{{ route('staff.store') }}">
                    @csrf
                    <input type="number" class="form-control" id="pharmacy_id" value="{{ session('current_pharmacy_id')}}" name="pharmacy_id" hidden>
                    <input type="number" class="form-control" id="user_id" value="0" name="user_id" hidden>
                    <input type="password" class="form-control" id="password" value="0" name="password" hidden>
                    <div class="mb-3">
                        <label for="name" class="form-label">Staff Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Staff Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Staff Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Staff</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection



{{-- {{$staff}} --}}