@extends('packages.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between">
            <h1>Subscription Plans</h1>
            <a href="{{ route('packages.create') }}" class="btn btn-primary mb-3">Add New Package</a>
        </div>
        <table class="table table-bordered" id="Table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Price (TZS)</th>
                    <th>Duration (days)</th>
                    {{-- <th>Features</th> --}}
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($packages as $package)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $package->name }}</td>
                        <td>{{ number_format($package->price, 0) }}</td>
                        <td>{{ $package->duration }}</td>
                        <td>{{ $package->status ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-warning btn-sm"><i
                                    class="bi bi-pencil"></i></a>
                            <a href="{{ route('packages.show', $package->id) }}" class="btn btn-success btn-sm"><i
                                    class="bi bi-eye"></i></a>
                            <form action="{{ route('packages.destroy', $package->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
