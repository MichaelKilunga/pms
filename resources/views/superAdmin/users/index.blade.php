@extends('superAdmin.users.app')

@section('content')
    <div class="container mt-4">
        <div class="container">
            <h1 class="text-center m-2">Manage Users</h1>
            <hr class="my-2">
            <table class="table table-striped" id="Table">
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
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                @if (Auth::user()->id != $user->id)
                                    <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-warning"><i
                                            class="bi bi-pencil"></i></a>
                                    <a href="{{ route('superadmin.users.show', $user->id) }}" class="btn btn-success"><i
                                            class="bi bi-eye"></i></a>
                                    <form action="{{ route('superadmin.users.delete', $user->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"  onclick='return confirm("Do you want to delete?")' class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
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
@endsection
