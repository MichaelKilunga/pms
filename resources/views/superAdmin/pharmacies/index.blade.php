@extends('superAdmin.users.app')

@section('content')
    <div class="container">
        <div class="container mt-4">
            <h1 class="text-center m-2">Manage Pharmacies</h1>
            <hr class="my-2">
            <table class="table table-striped" id="Table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Owner</th>
                        <th>Created at</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pharmacies as $pharmacy)
                        <tr>
                            <td>{{ $pharmacy->id }}</td>
                            <td>{{ $pharmacy->name }}</td>
                            <td>{{ $pharmacy->location }}</td>
                            <td>{{ $pharmacy->owner->name }}</td>
                            <td>{{ $pharmacy->created_at }}</td>
                            <td>
                                <a href="{{ route('superadmin.pharmacies.edit', $pharmacy->id) }}" class="btn btn-warning"><i
                                    class="bi bi-pencil"></i></a>
                            <a href="{{ route('superadmin.pharmacies.show', $pharmacy->id) }}" class="btn btn-success"><i
                                    class="bi bi-eye"></i></a>
                            <form action="{{ route('superadmin.pharmacies.delete', $pharmacy->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick='return confirm("Do you want to delete?")' class="btn btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
