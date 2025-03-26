@extends('pharmacies.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Stock</h2>
            <div>
                {{-- @show('create pharmacy') --}}
                {{-- @show('create pharmacy button') --}}
                <a href="{{ route('pharmacies.create') }}" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#addPharmacyModal">Add New Pharmacy</a>
                {{-- @endshow --}}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="Table">
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
                                <a href="#" class="btn btn-primary btn-sm"><i class="bi bi-eye" data-bs-toggle="modal"
                                        data-bs-target="#viewPharmacyModal{{ $pharmacy->id }}"></i></a>
                                <div class="modal fade" id="viewPharmacyModal{{ $pharmacy->id }}" tabindex="-1"
                                    aria-labelledby="viewPharmacyModalLabel{{ $pharmacy->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="viewPharmacyModalLabel{{ $pharmacy->id }}">
                                                    Pharmacy Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Display Pharmacy Information -->
                                                <div class="mb-3">
                                                    <strong>Name:</strong> {{ $pharmacy->name }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Location:</strong>
                                                    {{ $pharmacy->location ?? 'No description available' }}
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Created At:</strong>
                                                    {{ $pharmacy->created_at->format('d M, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- edit pharnacy modal -->
                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editPharmacyModal{{ $pharmacy->id }}"><i class="bi bi-pencil"></i></a>
                                <div class="modal fade" id="editPharmacyModal{{ $pharmacy->id }}" tabindex="-1"
                                    aria-labelledby="editPharmacyModalLabel{{ $pharmacy->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editPharmacyModalLabel{{ $pharmacy->id }}">Edit
                                                    Pharmacy</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Edit Pharmacy Form -->
                                                <form id="editPharmacyForm{{ $pharmacy->id }}" method="POST"
                                                    action="{{ route('pharmacies.update', $pharmacy->id) }}">
                                                    @csrf
                                                    @method('PUT') <!-- Using PUT to indicate an update -->

                                                    <input type="number" name="id" id=""
                                                        value="{{ $pharmacy->id }}" hidden>

                                                    <div class="mb-3">
                                                        <label for="name{{ $pharmacy->id }}" class="form-label">Pharmacy
                                                            Name</label>
                                                        <input type="text" class="form-control"
                                                            id="name{{ $pharmacy->id }}" name="name"
                                                            value="{{ $pharmacy->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="location{{ $pharmacy->id }}"
                                                            class="form-label">Pharmacy Location</label>
                                                        <textarea class="form-control" id="location{{ $pharmacy->id }}" name="location">{{ $pharmacy->location }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="hidden" name="pharmacy_id"
                                                            value="{{ session('current_pharmacy_id') }}">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update Pharmacy</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('pharmacies.destroy', $pharmacy->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Do you want to delete this pharmacy?')"
                                        class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addPharmacyModal" tabindex="-1" aria-labelledby="addPharmacyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPharmacyModalLabel">Add New Pharmacy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Pharmacy Form -->
                    <form id="pharmacyForm" method="POST" action="{{ route('pharmacies.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Pharmacy Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Pharmacy Location</label>
                            <textarea class="form-control" id="location" name="location"></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" name="pharmacy_id" value="{{ session('current_pharmacy_id') }}">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save Pharmacy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- {{$Pharmacy}} --}}
@endsection
