@extends("superAdmin.medicines.app")

@section("content")
    <div class="container">
        <div class="card mt-5 shadow-sm">
            <div class="card-header bg-primary d-flex justify-content-between text-white">
                <h3 class="mb-0">All Medicines</h3>
                <div>
                    <a class="btn btn-success m-1" href="{{ route("medicines.import-form") }}">Import Medicines</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{-- Check if there are any medicines --}}
            @if ($medicines->isEmpty())
                <div class="alert alert-info">No medicines found in the database.</div>
            @else
                <table class="table-striped table-bordered small table" id="Table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Brand Name</th>
                            <th>Generic Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medicines as $medicine)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Str::limit($medicine->brand_name, 15) ?? "N/A" }}</td>
                                <td>{{ Str::limit($medicine->generic_name, 10) ?? "N/A" }}</td>
                                <td>{{ $medicine->category ?? "N/A" }}</td>
                                {{-- Description should be limited to 20 characters --}}
                                <td>{{ Str::limit($medicine->description, 20) ?? "N/A" }}</td>
                                <td>{{ ucfirst($medicine->status ?? "unknown") }}</td>
                                <td class="">
                                    <a class="btn btn-sm btn-success"
                                        href="{{ route("allMedicines.edit", $medicine->id) }}"><i
                                            class="bi bi-pencil"></i></a>
                                    <form action="{{ route("allMedicines.destroy", $medicine->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method("DELETE")
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this medicine?')"
                                            type="submit"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    </div>
@endsection
