@extends('expenses.app');

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Expense Categories</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                + Add Category
            </button>
        </div>

        {{-- Categories Table --}}
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="Table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $index => $category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description ?? '-' }}</td>
                                    @if ($category->is_active == '1')
                                        <td><span class="badge bg-success">Active</span></td>
                                    @endif
                                    @if ($category->is_active == '0')
                                        <td><span class="badge bg-danger">Inactive</span></td>
                                    @endif

                                    <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm view-category"
                                            data-category='@json($category)' data-bs-toggle="modal"
                                            data-bs-target="#viewCategoryModal{{ $category->id }}">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button class="btn btn-warning btn-sm edit-category"
                                            data-category='@json($category)' data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal{{ $category->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>


                                        {{-- ================= View Category Modal ================= --}}
                                        <div class="modal fade" id="viewCategoryModal{{ $category->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-md modal-dialog-scrollable">
                                                <div class="modal-content shadow-lg rounded-3">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title">
                                                            <i class="bi bi-tags me-2"></i> Category Details
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <div class="list-group list-group-flush">
                                                                    <div class="list-group-item">
                                                                        <strong>Name:</strong> <span
                                                                            id="view-name">{{ $category->name }}</span>
                                                                    </div>
                                                                    <div class="list-group-item">
                                                                        <strong>Description:</strong> <span
                                                                            id="view-description">{{ $category->description ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="list-group list-group-flush">
                                                                    <div class="list-group-item">
                                                                        <strong>Status:</strong>
                                                                        @if ($category->is_active == '1')
                                                                            <span class="badge bg-success">Active</span>
                                                                        @else
                                                                            <span class="badge bg-secondary">Inactive</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="list-group-item">
                                                                        <strong>Created At:</strong> <span
                                                                            id="view-created-at">{{ $category->created_at->format('Y-m-d') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="bi bi-x-circle me-1"></i> Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        {{-- ================= Edit Category Modal ================= --}}
                                        <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <form method="POST" action="{{ route('category.update', $category->id) }}"
                                                    class="modal-content" id="editCategoryForm{{ $category->id }}">
                                                    @csrf @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Expense Category</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="name">Name</label>
                                                            <input type="text" name="name" id="edit-name"
                                                                class="form-control" required
                                                                value="{{ $category->name }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Description</label>
                                                            <textarea name="description" id="edit-description" class="form-control">{{ $category->description }}</textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Category Status</label>
                                                            <select name="is_active" class="form-select" required>
                                                                @if ($category->is_active == '1')
                                                                    <option selected value="1" selected>Active</option>
                                                                    <option value="0">Inactive</option>
                                                                @endif
                                                                @if ($category->is_active == '0')
                                                                    <option value="1" selected>Active</option>
                                                                    <option selected value="0">Inactive</option>
                                                                @endif

                                                            </select>
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

                                        <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this category?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= Add Category Modal ================= --}}
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('category.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Expense Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Expenses name"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" placeholder="Enter description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Category Status</label>
                        <select name="is_active" class="form-select" required>
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // View category
            $('.view-category').on('click', function() {
                var category = $(this).data('category'); // already parsed by jQuery
                $('#view-name').text(category.name);
                $('#view-description').text(category.description ?? '-');
                $('#view-status').text(category.is_active ? category.is_active : 'N/A');
                $('#view-created-at').text(category.created_at ?? '-');
            });

            // Edit category
            $('.edit-category').on('click', function() {
                var category = $(this).data('category');
                $('#editCategoryForm').attr('action', '/expense-categories/' + category.id);
                $('#edit-name').val(category.name);
                $('#edit-description').val(category.description);
            });


            // Datatable
            $('#categoriesTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true
            });
        });
    </script>
@endpush
