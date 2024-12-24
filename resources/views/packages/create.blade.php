@extends('packages.app')

@section('content')
<div class="container">
    <h1>Add New Package</h1>
    <form action="{{ route('packages.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="duration">Duration (days):</label>
            <input type="text" name="duration" id="duration" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="features">Features (JSON):</label>
            <textarea name="features" id="features" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
