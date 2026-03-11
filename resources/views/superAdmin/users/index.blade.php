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

                                        <button type="button" class="btn btn-secondary" 
                                            data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $user->id }}"
                                            title="Reset Password">
                                            <i class="bi bi-key"></i>
                                        </button>

                                        <!-- Reset Password Modal -->
                                        <div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1" aria-labelledby="resetPasswordModalLabel{{ $user->id }}" aria-hidden="true">
                                          <div class="modal-dialog">
                                            <form action="{{ route('superadmin.users.reset-password', $user->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-content text-start">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title fw-bold text-danger" id="resetPasswordModalLabel{{ $user->id }}">
                                                        <i class="bi bi-exclamation-triangle"></i> Reset Password
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body text-dark">
                                                    <p>Are you sure you want to reset the password for <strong>{{ $user->name }}</strong>?</p>
                                                    <p>This will set their password to the default <code>password</code> and force them to change it upon their next login.</p>
                                                    <div class="mb-3">
                                                        <label for="email{{ $user->id }}" class="form-label">Please confirm by entering their email address (<strong>{{ $user->email }}</strong>):</label>
                                                        <input type="email" class="form-control border-danger" id="email{{ $user->id }}" name="email" required autocomplete="off" placeholder="Enter user email">
                                                    </div>
                                                  </div>
                                                  <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reset Password</button>
                                                  </div>
                                                </div>
                                            </form>
                                          </div>
                                        </div>
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
