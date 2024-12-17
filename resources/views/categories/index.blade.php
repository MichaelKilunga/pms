@extends('categories.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Category</h2>
            <div>
                <a href="{{ route('category.create') }}" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#addCategoryModal">Add New Category</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Category Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description }}</td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm"  data-bs-toggle="modal" data-bs-target="#viewCategoryModal{{ $category->id }}" ><i
                                        class="bi bi-eye"></i></a>
                                <div class="modal fade" id="viewCategoryModal{{ $category->id }}" tabindex="-1"
                                    aria-labelledby="viewCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewCategoryModalLabel{{ $category->id }}">
                                                    Category Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display Category Information -->
                                                <div class="mb-3">
                                                    <strong>Name:</strong> {{ $category->name }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Description:</strong>
                                                    {{ $category->description ?? 'No description available' }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Pharmacy:</strong> {{ $category->pharmacy->name }}
                                                    <!-- Assuming you have a pharmacy relationship -->
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Created At:</strong>
                                                    {{ $category->created_at->format('d M, Y') }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Updated At:</strong>
                                                    {{ $category->updated_at->format('d M, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal{{ $category->id }}"><i class="bi bi-pencil"></i></a>
                                <!-- Edit Category Modal -->
                                <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1"
                                    aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Edit
                                                    Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Edit Category Form -->
                                                <form id="editCategoryForm{{ $category->id }}" method="POST"
                                                    action="{{ route('category.update', $category->id) }}">
                                                    @csrf
                                                    @method('PUT') <!-- Using PUT to indicate an update -->

                                                    <input type="number" name="id" id=""
                                                        value="{{ $category->id }}" hidden>
                                                    <div class="mb-3">
                                                        <label for="name{{ $category->id }}" class="form-label">Category
                                                            Name</label>
                                                        <input type="text" class="form-control"
                                                            id="name{{ $category->id }}" name="name"
                                                            value="{{ $category->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description{{ $category->id }}"
                                                            class="form-label">Description</label>
                                                        <textarea class="form-control" id="description{{ $category->id }}" name="description">{{ $category->description }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="hidden" name="pharmacy_id"
                                                            value="{{ session('current_pharmacy_id') }}">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update Category</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this category?')"
                                        class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

<!-- Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Category Form -->
                <form id="categoryForm" method="POST" action="{{ route('category.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="pharmacy_id" value="{{ session('current_pharmacy_id') }}">
                    </div>
                    <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
