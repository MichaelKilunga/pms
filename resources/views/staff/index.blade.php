@extends("staff.app")

@section("content")
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Pharmacist</h2>
            <div>
                <button class="btn btn-success" data-bs-target="#addUserModal" data-bs-toggle="modal" type="button">
                    Add New Pharmacist
                </button>
                <!-- <a class="btn btn-success" href="{{ route("staff.create") }}">Add New staff</a> -->
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-striped table-bordered table-hover small table" id="Table">
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
                                <a class="btn btn-primary btn-sm" data-bs-target="#viewStaffModal{{ $staff->id }}"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-eye"></i></a>
                                <div aria-hidden="true" aria-labelledby="viewStaffModalLabel{{ $staff->id }}"
                                    class="modal fade" id="viewStaffModal{{ $staff->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewStaffModalLabel{{ $staff->id }}">
                                                    Pharmacist's Details</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display Pharmacy Information -->
                                                <div class="mb-3">
                                                    <strong>Name:</strong> {{ $staff->user->name }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Email:</strong>
                                                    {{ $staff->user->email ?? "No eamil available" }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Phone:</strong>
                                                    {{ $staff->user->phone ?? "No phone# available" }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Role:</strong>
                                                    {{ $staff->user->role }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Created At:</strong>
                                                    {{ $staff->user->created_at->format("d M, Y") }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a class="btn btn-success btn-sm" data-bs-target="#editStaffModal{{ $staff->id }}"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-pencil"></i></a>
                                <div aria-hidden="true" aria-labelledby="editStaffModalLabel{{ $staff->id }}"
                                    class="modal fade" id="editStaffModal{{ $staff->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editStaffModalLabel{{ $staff->id }}">Edit
                                                    Pharmacist</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Edit Pharmacist Form -->
                                                <form action="{{ route("staff.update", $staff->user->id) }}"
                                                    id="editStaffForm{{ $staff->id }}" method="POST">
                                                    @csrf
                                                    @method("PUT") <!-- Using PUT to indicate an update -->

                                                    <input hidden id="" name="id" type="number"
                                                        value="{{ $staff->user_id }}">

                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="name{{ $staff->id }}">Pharmacist's
                                                            Name</label>
                                                        <input class="form-control" id="name{{ $staff->id }}"
                                                            name="name" required type="text"
                                                            value="{{ $staff->user->name }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" for="name{{ $staff->id }}">Pharmacist
                                                            Phone</label>
                                                        <input class="form-control" id="name{{ $staff->id }}"
                                                            name="phone" required type="text"
                                                            value="{{ $staff->user->phone }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" for="name{{ $staff->id }}">Pharmacist
                                                            Email</label>
                                                        <input class="form-control" id="name{{ $staff->id }}"
                                                            name="email" required type="text"
                                                            value="{{ $staff->user->email }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <input name="pharmacy_id" type="hidden"
                                                            value="{{ session("current_pharmacy_id") }}">
                                                    </div>
                                                    <button class="btn btn-primary" type="submit">Update
                                                        Pharmacist</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route("staff.destroy", $staff->user_id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this staff?')"
                                        type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add User Modal -->
    <div aria-hidden="true" aria-labelledby="addUserModalLabel" class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New Pharmacist</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route("staff.store") }}" id="addUserForm" method="POST">
                        @csrf
                        <input class="form-control" hidden id="pharmacy_id" name="pharmacy_id" type="number"
                            value="{{ session("current_pharmacy_id") }}">
                        <input class="form-control" hidden id="user_id" name="user_id" type="number" value="0">
                        <input class="form-control" hidden id="password" name="password" type="password"
                            value="0">
                        <div class="mb-3">
                            <label class="form-label" for="name">Pharmacist Name</label>
                            <input class="form-control" id="name" name="name" required type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Pharmacist Email</label>
                            <input class="form-control" id="email" name="email" required type="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone">Pharmacist Phone</label>
                            <input class="form-control" id="phone" name="phone" required type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="role">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option disabled selected value="">Select Role</option>
                                {{-- <option value="admin">Admin</option> --}}
                                <option value="staff">Pharmacist</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">Add Pharmacist</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- {{$staff}} --}}
