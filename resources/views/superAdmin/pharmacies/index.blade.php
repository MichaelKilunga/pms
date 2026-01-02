@extends("superAdmin.users.app")

@section("content")
    <div class="container">
        <div class="container mt-4">
            <h1 class="m-2 text-center">Manage Pharmacies</h1>
            <hr class="my-2">
            <table class="table-striped small table" id="Table">
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
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pharmacy->name }}</td>
                            <td>{{ $pharmacy->location }}</td>
                            <td>{{ $pharmacy->owner->name }}</td>
                            <td>{{ $pharmacy->created_at }}</td>
                            <td>
                                <a class="btn btn-warning" href="{{ route("superadmin.pharmacies.edit", $pharmacy->id) }}"><i
                                        class="bi bi-pencil"></i></a>
                                <a class="btn btn-success" href="{{ route("superadmin.pharmacies.show", $pharmacy->id) }}"><i
                                        class="bi bi-eye"></i></a>
                                <form action="{{ route("superadmin.pharmacies.delete", $pharmacy->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-danger" onclick='return confirm("Do you want to delete?")'
                                        type="submit"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
