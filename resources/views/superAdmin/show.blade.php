@extends('medicines.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Medicine Details</h1>
            <a href="{{ route('medicines') }}" class="btn btn-secondary">Back to Medicines</a>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $medicine->name }}</h4>
                <p class="card-text"><strong>Name:</strong> {{ $medicine->name }}</p>
                <p class="card-text"><strong>Category:</strong> {{ $medicine->category->name }}</p>
                <p class="card-text"><strong>Pharmacy:</strong> {{ $medicine->pharmacy->name }}</p>
                {{-- <p class="card-text"><strong>Stock:</strong> {{ $medicine->stock }} units</p> --}}
                <p class="card-text"><strong>Price:</strong> ${{ number_format($medicine->price, 2) }}</p>
                {{-- <p class="card-text"><strong>Expiration Date:</strong> {{ $medicine->expiration_date->format('d M Y') }}</p> --}}
                {{-- <p class="card-text"><strong>Description:</strong> {{ $medicine->description }}</p> --}}
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-warning">Edit Medicine</a>

            <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this medicine?');">
                    Delete Medicine
                </button>
            </form>
        </div>
    </div>
@endsection
