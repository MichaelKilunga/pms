@extends('staff.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-2">
            <h2 class="text-primary fw-bold">Pharmacist</h2>
            <div>
                <button class="btn btn-success" data-bs-target="#addUserModal" data-bs-toggle="modal" type="button">
                    Add New Pharmacist
                </button>
                <!-- <a class="btn btn-success" href="{{ route('staff.create') }}">Add New staff</a> -->
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
                        <th>Permissions</th>
                        <th>Status</th>
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
                            <td>{{ $staff->user->role == 'Staff' ? 'Pharmacist' : 'Pharmacist' }}</td>
                            <td>
                                @if ($staff->user->hasPermissionTo('manage sales'))
                                    <span class="badge bg-primary">Sales</span>
                                @endif
                                @if ($staff->user->hasPermissionTo('manage stock'))
                                    <span class="badge bg-success">Stock</span>
                                @endif
                                @if ($staff->user->hasPermissionTo('add stock'))
                                    <span class="badge bg-info text-dark">Add Stock</span>
                                @endif
                                @if ($staff->user->hasPermissionTo('view reports'))
                                    <span class="badge bg-warning text-dark">Reports</span>
                                @endif
                                @if ($staff->user->permissions->count() == 0)
                                    <span class="badge bg-secondary">No Permissions</span>
                                @endif
                            </td>
                            <td>{{ $staff->status == 'active' ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" data-bs-target="#viewStaffModal{{ $staff->id }}"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-eye"></i></a>
                                <div aria-hidden="true" aria-labelledby="viewStaffModalLabel{{ $staff->id }}"
                                    class="modal fade" id="viewStaffModal{{ $staff->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
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
                                                    {{ $staff->user->email ?? 'No eamil available' }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Phone:</strong>
                                                    {{ $staff->user->phone ?? 'No phone# available' }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Role:</strong>
                                                    {{ $staff->user->role ? 'Pharmacist' : 'Pharmacist' }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Created At:</strong>
                                                    {{ $staff->user->created_at->format('d M, Y') }}
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
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="editStaffModalLabel{{ $staff->id }}">Edit
                                                    Pharmacist</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Edit Pharmacist Form -->
                                                <form action="{{ route('staff.update', $staff->user->id) }}"
                                                    id="editStaffForm{{ $staff->id }}" method="POST">
                                                    @csrf
                                                    @method('PUT') <!-- Using PUT to indicate an update -->

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
                                                        <label class="form-label"
                                                            for="role{{ $staff->id }}">Role</label>
                                                        <select class="form-select" id="role{{ $staff->id }}"
                                                            name="role" required>
                                                            <option {{ $staff->user->role == 'staff' ? 'selected' : '' }}
                                                                value="staff">Pharmacist</option>
                                                            {{-- <option {{ $staff->user->role == "admin" ? "selected" : "" }}
                                                                value="admin">Manager (Admin)</option> --}}
                                                        </select>
                                                    </div>



                                                    <div class="mb-3">
                                                        <label class="form-label">Permissions</label>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="manage sales" id="perm_sales{{ $staff->id }}" @checked($staff->user->hasPermissionTo('manage sales'))>
                                                                    <label class="form-check-label" for="perm_sales{{ $staff->id }}">Manage Sales</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="manage stock" id="perm_stock{{ $staff->id }}" @checked($staff->user->hasPermissionTo('manage stock'))>
                                                                    <label class="form-check-label" for="perm_stock{{ $staff->id }}">Manage Stock</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="add stock" id="perm_add{{ $staff->id }}" @checked($staff->user->hasPermissionTo('add stock'))>
                                                                    <label class="form-check-label" for="perm_add{{ $staff->id }}">Add Stock</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="view reports" id="perm_reports{{ $staff->id }}" @checked($staff->user->hasPermissionTo('view reports'))>
                                                                    <label class="form-check-label" for="perm_reports{{ $staff->id }}">View Reports</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <input name="pharmacy_id" type="hidden"
                                                            value="{{ session('current_pharmacy_id') }}">
                                                    </div>
                                                    <button class="btn btn-primary" type="submit">Update
                                                        Pharmacist</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('staff.destroy', $staff->user_id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    @if ($staff->status == 'active')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to deactivate?')"
                                            type="submit"><i class="bi bi-x-circle"></i>
                                        </button>
                                    @endif
                                    @if ($staff->status == 'inactive')
                                        <button class="btn btn-success btn-sm"
                                            onclick="return confirm('Are you sure you want to activate?')"
                                            type="submit"><i class="bi bi-check-circle"></i>
                                        </button>
                                    @endif
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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addUserModalLabel">Add New Pharmacist</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('staff.store') }}" id="addUserForm" method="POST">
                        @csrf
                        <input class="form-control" hidden id="pharmacy_id" name="pharmacy_id" type="number"
                            value="{{ session('current_pharmacy_id') }}">
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
                                {{-- <option value="admin">Manager (Admin)</option> --}}
                                <option value="staff">Pharmacist</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="manage sales" id="add_perm_sales">
                                        <label class="form-check-label" for="add_perm_sales">Manage Sales</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="manage stock" id="add_perm_stock">
                                        <label class="form-check-label" for="add_perm_stock">Manage Stock</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="add stock" id="add_perm_add">
                                        <label class="form-check-label" for="add_perm_add">Add Stock</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="view reports" id="add_perm_reports">
                                        <label class="form-check-label" for="add_perm_reports">View Reports</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary" type="submit">Add Pharmacist</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- {{$staff}} --}}
