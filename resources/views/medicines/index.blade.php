@extends('medicines.app')

@section('content')
@if (session('success'))
<span class="bg-success">
    {{session('success')}}!
</span>
@endif
<div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Medicines</h2>
            <div>
                <a href="{{ route('medicines.create') }}" class="btn btn-success">Add New Medicine</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Pharmacy</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicines as $medicine)
                        <tr>
                            <td>{{ $medicine->id }}</td>
                            <td>{{ $medicine->name }}</td>
                            <td>{{ $medicine->category->name }}</td>
                            <td>{{ $medicine->pharmacy->name }}</td>
                            <td>
                                <a href="{{ route('medicines.show', $medicine->id) }}" class="btn btn-primary btn-sm"><i
                                        class="bi bi-eye"></i></a>
                                <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-success btn-sm"><i
                                        class="bi bi-pencil"></i></a>
                                <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" style="display:inline;">
                                {{-- <form action="medicines/destroy" method="DELETE" style="display:inline;"> --}}
                                    @csrf
                                    @method('DELETE')
                                    <input type="number" class="hidden" value="">
                                    <button type="submit" onclick="confirm('Do you want to delete this medicine?')" class="btn btn-danger btn-sm"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
