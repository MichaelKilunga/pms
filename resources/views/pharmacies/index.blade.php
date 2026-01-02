@extends("pharmacies.app")

@section("content")
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Stock</h2>
            <div>
                {{-- @show('create pharmacy') --}}
                {{-- @show('create pharmacy button') --}}
                <a class="btn btn-success" data-bs-target="#addPharmacyModal" data-bs-toggle="modal"
                    href="{{ route("pharmacies.create") }}">Add New Pharmacy</a>
                {{-- @endshow --}}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-striped table-bordered table-hover small table" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pharmacy Name</th>
                        <th>Pharmacy Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pharmacies as $pharmacy)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pharmacy->name }}</td>
                            <td>{{ $pharmacy->location }}</td>
                            <!-- <td class="d-flex justify-content-between"> -->
                            <td>
                                <a class="btn btn-primary btn-sm" href="#"><i class="bi bi-eye"
                                        data-bs-target="#viewPharmacyModal{{ $pharmacy->id }}"
                                        data-bs-toggle="modal"></i></a>
                                <div aria-hidden="true" aria-labelledby="viewPharmacyModalLabel{{ $pharmacy->id }}"
                                    class="modal fade" id="viewPharmacyModal{{ $pharmacy->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewPharmacyModalLabel{{ $pharmacy->id }}">
                                                    Pharmacy Details</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display Pharmacy Information -->
                                                <div class="mb-3">
                                                    <strong>Name:</strong> {{ $pharmacy->name }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Location:</strong>
                                                    {{ $pharmacy->location ?? "No description available" }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Created At:</strong>
                                                    {{ $pharmacy->created_at->format("d M, Y") }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- edit pharnacy modal -->
                                <a class="btn btn-success btn-sm" data-bs-target="#editPharmacyModal{{ $pharmacy->id }}"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-pencil"></i></a>
                                <div aria-hidden="true" aria-labelledby="editPharmacyModalLabel{{ $pharmacy->id }}"
                                    class="modal fade" id="editPharmacyModal{{ $pharmacy->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editPharmacyModalLabel{{ $pharmacy->id }}">Edit
                                                    Pharmacy</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Edit Pharmacy Form -->
                                                <form action="{{ route("pharmacies.update", $pharmacy->id) }}"
                                                    id="editPharmacyForm{{ $pharmacy->id }}" method="POST">
                                                    @csrf
                                                    @method("PUT") <!-- Using PUT to indicate an update -->

                                                    <input hidden id="" name="id" type="number"
                                                        value="{{ $pharmacy->id }}">

                                                    <div class="mb-3">
                                                        <label class="form-label" for="name{{ $pharmacy->id }}">Pharmacy
                                                            Name</label>
                                                        <input class="form-control" id="name{{ $pharmacy->id }}"
                                                            name="name" required type="text"
                                                            value="{{ $pharmacy->name }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="location{{ $pharmacy->id }}">Pharmacy Location</label>
                                                        <textarea class="form-control" id="location{{ $pharmacy->id }}" name="location">{{ $pharmacy->location }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input name="pharmacy_id" type="hidden"
                                                            value="{{ session("current_pharmacy_id") }}">
                                                    </div>
                                                    <button class="btn btn-primary" type="submit">Update Pharmacy</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route("pharmacies.destroy", $pharmacy->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Do you want to delete this pharmacy?')" type="submit"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div aria-hidden="true" aria-labelledby="addPharmacyModalLabel" class="modal fade" id="addPharmacyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPharmacyModalLabel">Add New Pharmacy</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <!-- Pharmacy Form -->
                    <form action="{{ route("pharmacies.store") }}" id="pharmacyForm" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Pharmacy Name</label>
                            <input class="form-control" id="name" name="name" required type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="location">Pharmacy Location</label>
                            <textarea class="form-control" id="location" name="location"></textarea>
                        </div>
                        <div class="mb-3">
                            <input name="pharmacy_id" type="hidden" value="{{ session("current_pharmacy_id") }}">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Save Pharmacy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- {{$Pharmacy}} --}}
@endsection
