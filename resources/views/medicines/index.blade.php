@extends("medicines.app")

@section("content")
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2 class="m-2">Medicines</h2>
            <div class="d-flex justify-content-between">
                <div>
                    <a class="btn btn-success m-1" data-bs-target="#createMedicineModal" data-bs-toggle="modal"
                        href="#">Add
                        New
                        Medicine</a>
                </div>
                <div>
                    <a class="btn btn-danger m-1" href="import">Import from online</a>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form action="{{ route("medicines") }}" class="row g-2 d-flex justify-content-between" method="GET">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input class="form-control" name="search" placeholder="Search medicine name..."
                                type="text" value="{{ request("search") }}">
                        </div>
                    </div>
                    <div class="col-md-4 d-none">
                        <select class="form-select" name="category_id">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option {{ request("category_id") == $category->id ? "selected" : "" }}
                                    value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1" type="submit">Filter</button>
                        @if (request()->has("search") || request()->has("category_id"))
                            <a class="btn btn-outline-secondary" href="{{ route("medicines") }}">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-striped table-bordered table-hover small table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        {{-- <th>Pharmacy</th> --}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicines as $medicine)
                        <tr>
                            <td>{{ ($medicines->currentPage() - 1) * $medicines->perPage() + $loop->iteration }}</td>
                            {{-- <td>{{ $medicine->id }}</td> --}}
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->category->name }}</td>
                            {{-- <td>{{ $medicine->pharmacy->name }}</td> --}}
                            <td>
                                <a class="btn btn-primary btn-sm" data-bs-target="#viewMedicineModal{{ $medicine->id }}"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-eye"></i></a>
                                <div aria-hidden="true" aria-labelledby="viewMedicineModalLabel{{ $medicine->id }}"
                                    class="modal fade" id="viewMedicineModal{{ $medicine->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewMedicineModalLabel{{ $medicine->id }}">
                                                    Medicine Details</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div><strong>Name:</strong> {{ $medicine->name }}</div>
                                                <div><strong>Category:</strong> {{ $medicine->category->name }}</div>
                                                <div><strong>Pharmacy:</strong> {{ $medicine->pharmacy->name }}</div>
                                                <div><strong>Created At:</strong>
                                                    {{ $medicine->created_at->format("d M, Y") }}</div>
                                                <div><strong>Updated At:</strong>
                                                    {{ $medicine->updated_at->format("d M, Y") }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a class="btn btn-success btn-sm" data-bs-target="#editMedicineModal{{ $medicine->id }}"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-pencil"></i></a>
                                <div aria-hidden="true" aria-labelledby="editMedicineModalLabel{{ $medicine->id }}"
                                    class="modal fade" id="editMedicineModal{{ $medicine->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editMedicineModalLabel{{ $medicine->id }}">Edit
                                                    Medicine</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route("medicines.update", $medicine->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method("PUT")
                                                    <input hidden id="" name="id" type="number"
                                                        value="{{ $medicine->id }}">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="name">Medicine Name</label>
                                                        <input class="form-control" name="name" required type="text"
                                                            value="{{ $medicine->name }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="category">Category</label>
                                                        <select class="chosen form-select" name="category_id" required>
                                                            @foreach ($categories as $category)
                                                                <option
                                                                    {{ $medicine->category_id == $category->id ? "selected" : "" }}
                                                                    value="{{ $category->id }}">
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="pharmacy">Pharmacy:
                                                            {{ $medicine->pharmacy->name }}</label>
                                                        <input hidden name="pharmacy_id" type="number"
                                                            value="{{ $medicine->pharmacy_id }}">
                                                    </div>
                                                    <button class="btn btn-success" type="submit">Update Medicine</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- if medicine having stock, disable delete button --}}
                                @php
                                    //select * from stocks where item_id = $medicine->id
                                    $hasStock = \App\Models\Stock::where("item_id", $medicine->id)->exists();
                                    // dd($hasStock);
                                @endphp
                                @if ($hasStock)
                                    <button class="btn btn-danger btn-sm" disabled><i class="bi bi-trash"></i></button>
                                @else
                                    {{-- enable delete button --}}
                                    <form action="{{ route("medicines.destroy", $medicine->id) }}" method="POST"
                                        style="display:inline;">
                                        {{-- <form action="medicines/destroy" method="DELETE" style="display:inline;"> --}}
                                        @csrf
                                        @method("DELETE")
                                        <input class="hidden" type="number" value="">
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Do you want to delete this medicine?')"
                                            type="submit"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $medicines->links() }}
        </div>
    </div>

    <!-- Create Medicine Modal -->
    <div aria-hidden="true" aria-labelledby="createMedicineModalLabel" class="modal fade" id="createMedicineModal"
        tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMedicineModalLabel">Add New Medicine</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route("medicines.store") }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Medicine Name</label>
                            <input class="form-control" id="name" name="name" required type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-select" name="category_id" required>
                                <option selected value="">--Select category--</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="pharmacy">Pharmacy: {{ session("pharmacy_name") }}</label>
                            <input hidden name="pharmacy_id" type="number"
                                value="{{ session("current_pharmacy_id") }}">
                        </div>
                        <button class="btn btn-success mb-2" type="submit">Save Medicine</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
