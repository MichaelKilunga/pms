@extends('medicines.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Medicines</h2>
            <div>
                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createMedicineModal">Add New
                    Medicine</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Pharmacy</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicines as $medicine)
                        <tr>
                            <td>{{ $medicine->id }}</td>
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->category->name }}</td>
                            <td>{{ $medicine->pharmacy->name }}</td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#viewMedicineModal{{ $medicine->id }}"><i class="bi bi-eye"></i></a>
                                <div class="modal fade" id="viewMedicineModal{{ $medicine->id }}" tabindex="-1"
                                    aria-labelledby="viewMedicineModalLabel{{ $medicine->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewMedicineModalLabel{{ $medicine->id }}">
                                                    Medicine Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div><strong>Name:</strong> {{ $medicine->name }}</div>
                                                <div><strong>Category:</strong> {{ $medicine->category->name }}</div>
                                                <div><strong>Pharmacy:</strong> {{ $medicine->pharmacy->name }}</div>
                                                <div><strong>Created At:</strong>
                                                    {{ $medicine->created_at->format('d M, Y') }}</div>
                                                <div><strong>Updated At:</strong>
                                                    {{ $medicine->updated_at->format('d M, Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editMedicineModal{{ $medicine->id }}"><i class="bi bi-pencil"></i></a>
                                <div class="modal fade" id="editMedicineModal{{ $medicine->id }}" tabindex="-1"
                                    aria-labelledby="editMedicineModalLabel{{ $medicine->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editMedicineModalLabel{{ $medicine->id }}">Edit
                                                    Medicine</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('medicines.update', $medicine->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="id" id=""
                                                        value="{{ $medicine->id }}" hidden>
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Medicine Name</label>
                                                        <input type="text" class="form-control" name="name"
                                                            value="{{ $medicine->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="category" class="form-label">Category</label>
                                                        <select name="category_id" class="chosen form-select" required>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ $medicine->category_id == $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="pharmacy" class="form-label">Pharmacy:
                                                            {{ $medicine->pharmacy->name }}</label>
                                                        <input type="number" name="pharmacy_id"
                                                            value="{{ $medicine->pharmacy_id }}" hidden>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Update Medicine</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
        </div>


        <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" style="display:inline;">
            {{-- <form action="medicines/destroy" method="DELETE" style="display:inline;"> --}}
            @csrf
            @method('DELETE')
            <input type="number" class="hidden" value="">
            <button type="submit" onclick="return confirm('Do you want to delete this medicine?')"
                class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
        </form>
        </td>
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
    </div>




    <!-- Create Medicine Modal -->
    <div class="modal fade" id="createMedicineModal" tabindex="-1" aria-labelledby="createMedicineModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMedicineModalLabel">Add New Medicine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('medicines.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Medicine Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category_id" class="form-select" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pharmacy" class="form-label">Pharmacy: {{ session('pharmacy_name') }}</label>
                            <input type="number" name="pharmacy_id" value="{{ session('current_pharmacy_id') }}"
                                hidden>
                        </div>
                        <button type="submit" class="btn mb-2 btn-success">Save Medicine</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
