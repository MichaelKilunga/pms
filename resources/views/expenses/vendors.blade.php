@extends('expenses.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><i class="fas fa-users me-2"></i> Vendors</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVendorModal">
                <i class="fas fa-plus-circle me-1"></i> Add Vendor
            </button>
        </div>

        <!-- Vendors Table -->
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle" id="vendorsTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vendor->name }}</td>
                                <td>{{ $vendor->phone ?? '-' }}</td>
                                <td>{{ $vendor->email ?? '-' }}</td>
                                <td>{{ $vendor->city ?? '-' }}</td>
                                <td>{{ $vendor->country ?? '-' }}</td>
                                <td>
                                    @if ($vendor->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- <div class="btn-group"> --}}
                                        <!-- View -->
                                        <button class="btn btn-sm btn-info text-white view-vendor"
                                            data-vendor='@json($vendor)' data-bs-toggle="modal"
                                            data-bs-target="#viewVendorModal{{ $vendor->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-warning edit-vendor"
                                            data-vendor='@json($vendor)' data-bs-toggle="modal"
                                            data-bs-target="#editVendorModal{{ $vendor->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <!-- Delete -->
                                        <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this vendor?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    {{-- </div> --}}
                                </td>
                            </tr>

                            <!-- ================= View Vendor Modal ================= -->
                            <div class="modal fade" id="viewVendorModal{{ $vendor->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content shadow-lg rounded-3">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-person-badge me-2"></i> Vendor Details
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="list-group list-group-flush">
                                                        <div class="list-group-item"><strong>Name:</strong>
                                                            {{ $vendor->name }}</div>
                                                        <div class="list-group-item"><strong>Phone:</strong>
                                                            {{ $vendor->phone ?? '-' }}</div>
                                                        <div class="list-group-item"><strong>Email:</strong>
                                                            {{ $vendor->email ?? '-' }}</div>
                                                        <div class="list-group-item"><strong>City:</strong>
                                                            {{ $vendor->city ?? '-' }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="list-group list-group-flush">
                                                        <div class="list-group-item"><strong>Country:</strong>
                                                            {{ $vendor->country ?? '-' }}</div>
                                                        <div class="list-group-item"><strong>Address:</strong>
                                                            {{ $vendor->address ?? '-' }}</div>
                                                        <div class="list-group-item"><strong>TIN:</strong>
                                                            {{ $vendor->tin ?? '-' }}</div>
                                                        <div class="list-group-item">
                                                            <strong>Status:</strong>
                                                            @if ($vendor->is_active)
                                                                <span class="badge bg-success">Active</span>
                                                            @else
                                                                <span class="badge bg-secondary">Inactive</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle me-1"></i> Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ================= Edit Vendor Modal ================= -->
                            <div class="modal fade" id="editVendorModal{{ $vendor->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">

                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('vendors.update', $vendor->id) }}"
                                            class="modal-content">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Vendor</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ $vendor->name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Phone</label>
                                                    <input type="text" name="phone" class="form-control"
                                                        value="{{ $vendor->phone }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        value="{{ $vendor->email }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">City</label>
                                                    <input type="text" name="city" class="form-control"
                                                        value="{{ $vendor->city }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Country</label>
                                                    <input type="text" name="country" class="form-control"
                                                        value="{{ $vendor->country }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Status</label>
                                                    <select name="is_active" class="form-select">
                                                        <option value="1" {{ $vendor->is_active ? 'selected' : '' }}>
                                                            Active</option>
                                                        <option value="0"
                                                            {{ !$vendor->is_active ? 'selected' : '' }}>
                                                            Inactive</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Address</label>
                                                    <textarea name="address" class="form-control">{{ $vendor->address }}</textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">TIN</label>
                                                    <input type="text" name="tin" class="form-control"
                                                        value="{{ $vendor->tin }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-warning">Update</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No vendors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= Add Vendor Modal ================= -->
    <div class="modal fade" id="addVendorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('vendors.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Vendor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required
                                placeholder="Enter Vendor Name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="Enter Phone Number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter Email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control" placeholder="Enter City">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control" placeholder="Enter Country">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" rows="2" class="form-control" placeholder="Enter Address"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">TIN</label>
                            <input type="text" name="tin" class="form-control" placeholder="Enter TIN">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Vendor</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // DataTable
            $('#vendorsTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true
            });
        });
    </script>
@endpush
