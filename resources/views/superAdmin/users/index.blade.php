@extends("superAdmin.users.app")

@section("content")
    <div class="container mt-4">
        <div class="table-reponsive container">
            <h1 class="m-2 text-center">Manage Users</h1>
            <hr class="my-2">
            <div class="table-responsive">
                <table class="table-striped small table" id="Table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created at</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->role }}</td>
                                <td>
                                    @if (Auth::user()->id != $user->id)
                                        <a class="btn btn-warning" href="{{ route("superadmin.users.edit", $user->id) }}"><i
                                                class="bi bi-pencil"></i></a>
                                        <a class="btn btn-success" href="{{ route("superadmin.users.show", $user->id) }}"><i
                                                class="bi bi-eye"></i></a>
                                        <form action="{{ route("superadmin.users.delete", $user->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method("DELETE")
                                            <button class="btn btn-danger"
                                                onclick='return confirm("Do you want to delete?")' type="submit"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                        <a class="btn btn-info"
                                            href="{{ route("superAdmin.users.notifications", $user->id) }}"
                                            title="Notification Settings">
                                            <i class="bi bi-bell"></i>
                                        </a>
                                    @else
                                        <p class="text-primary text-center">You!</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
