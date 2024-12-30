@extends('medicines.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Add New Medicine</h1>
            <a href="{{ route('medicines') }}" class="btn btn-secondary">Back to Medicines</a>
        </div>

        <div class="card">
            <div class="card-body">
                {{-- Display Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Medicine Creation Form --}}
                <form action="{{ route('medicines.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Medicine Name</label>
                        <input type="text" name="name" id="name" class="form-control" 
                               value="{{ old('name') }}" placeholder="Enter medicine name" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="" disabled selected>Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="pharmacy_id" class="form-label">Pharmacy</label>
                        <select name="pharmacy_id" id="pharmacy_id" class="form-control" required>
                            <option value="" disabled selected>Select a pharmacy</option>
                            @foreach ($pharmacies as $pharmacy)
                                <option value="{{ $pharmacy->id }}" 
                                        {{ old('pharmacy_id') == $pharmacy->id ? 'selected' : '' }}>
                                    {{ $pharmacy->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Add Medicine</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
