@extends('superAdmin.medicines.app')

@section('content')
<div class="container">
    <div class="card mt-5 shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Import Medicines</h3>
        </div>
        <x-nav-link href="{{ route('allMedicines.all') }}" :active="request()->routeIs('allMedicines.all')">
            {{ __('Medicines') }}
        </x-nav-link>
        <div class="card-body">
            {{-- Display success or error messages --}}
            {{-- @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif --}}

            {{-- File Upload Form --}}
            <form action="{{ route('medicines.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Upload File</label>
                    <input type="file" name="file" id="file" class="form-control bg-warning" required>
                    <small class="form-text text-muted">Supported formats: CSV, Excel (.xlsx)</small>
                </div>
                <button type="submit" class="btn btn-primary w-100">Import Medicines</button>
            </form>
        </div>
    </div>
</div>
@endsection
