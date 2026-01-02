@extends("categories.app")

@section("content")
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Category</h2>
            <div>
                <a class="btn btn-success" data-bs-target="#addCategoryModal" data-bs-toggle="modal"
                    href="{{ route("category.create") }}">Add New Category</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-striped table-bordered table-hover small table" id="Table">
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
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->description }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" data-bs-target="#viewCategoryModal{{ $category->id }}"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-eye"></i></a>

                                <div aria-hidden="true" aria-labelledby="viewCategoryModalLabel{{ $category->id }}"
                                    class="modal fade" id="viewCategoryModal{{ $category->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewCategoryModalLabel{{ $category->id }}">
                                                    Category Details</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display Category Information -->
                                                <div class="mb-3">
                                                    <strong>Name:</strong> {{ $category->name }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Description:</strong>
                                                    {{ $category->description ?? "No description available" }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Pharmacy:</strong> {{ $category->pharmacy->name }}
                                                    <!-- Assuming you have a pharmacy relationship -->
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Created At:</strong>
                                                    {{ $category->created_at->format("d M, Y") }}
                                                </div>
                                                {{-- <div class="mb-3">
                                                    <strong>Updated At:</strong>
                                                    {{ $category->updated_at->format('d M, Y') }}
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a class="btn btn-success disabled btn-sm"
                                    data-bs-target="#editCategoryModal{{ $category->id }}" data-bs-toggle="modal"
                                    href="#"><i class="bi bi-pencil"></i></a>
                                <!-- Edit Category Modal -->
                                <div aria-hidden="true" aria-labelledby="editCategoryModalLabel{{ $category->id }}"
                                    class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Edit
                                                    Category</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Edit Category Form -->
                                                <form action="{{ route("category.update", $category->id) }}"
                                                    id="editCategoryForm{{ $category->id }}" method="POST">
                                                    @csrf
                                                    @method("PUT") <!-- Using PUT to indicate an update -->

                                                    <input hidden id="" name="id" type="number"
                                                        value="{{ $category->id }}">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="name{{ $category->id }}">Category
                                                            Name</label>
                                                        <input class="form-control" id="name{{ $category->id }}"
                                                            name="name" required type="text"
                                                            value="{{ $category->name }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="description{{ $category->id }}">Description</label>
                                                        <textarea class="form-control" id="description{{ $category->id }}" name="description">{{ $category->description }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input name="pharmacy_id" type="hidden"
                                                            value="{{ session("current_pharmacy_id") }}">
                                                    </div>
                                                    <button class="btn btn-primary" type="submit">Update Category</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route("category.destroy", $category->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-danger btn-sm" disabled
                                        onclick="return confirm('Are you sure you want to delete this category?')"
                                        type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div aria-hidden="true" aria-labelledby="addCategoryModalLabel" class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <!-- Category Form -->
                    <form action="{{ route("category.store") }}" id="categoryForm" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Category Name</label>
                            <input class="form-control" id="name" name="name" required type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <input name="pharmacy_id" type="hidden" value="{{ session("current_pharmacy_id") }}">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
