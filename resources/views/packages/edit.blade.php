@extends('packages.app')

@section('content')
<div class="container">
    <h1>Edit Package</h1>
    <form action="{{ route('packages.update', $package->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $package->name }}" required>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ $package->price }}" required>
        </div>
        <div class="form-group">
            <label for="duration">Duration (days):</label>
            <input type="text" name="duration" id="duration" class="form-control" value="{{ $package->duration }}" required>
        </div>
        <div class="form-group">
            <label for="features">Features (JSON):</label>
            <textarea name="features" id="features" class="form-control">{{ $package->features }}</textarea>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control">
                <option value="1" {{ $package->status ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$package->status ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
