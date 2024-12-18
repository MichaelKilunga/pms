@extends('pharmacies.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Stock</h2>
            <div>
                <a href="{{ route('pharmacies.create') }}" class="btn btn-success">Add New Pharmacy</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pharmacy Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pharmacies as $pharmacy)
                        <tr>
                            <td>{{ $pharmacy->id }}</td>
                            <td>{{ $pharmacy->name }}</td>
                            <td class="d-flex justify-content-end">
                                <a href="{{ route('pharmacies.show', $pharmacy->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye" ></i></a>
                                <a href="{{ route('pharmacies.edit', $pharmacy->id) }}" class="btn btn-success btn-sm"><i class="bi bi-pencil" ></i></a>
                                <form action="{{ route('pharmacies.destroy', $pharmacy->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Do you want to delete this pharmacy?')" class="btn btn-danger btn-sm"><i class="bi bi-trash" ></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


 {{-- {{$Pharmacy}} --}}