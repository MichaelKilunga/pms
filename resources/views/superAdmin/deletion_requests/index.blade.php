@extends("superAdmin.users.app")

@section("content")
    <div class="container mt-4">
        <div class="table-reponsive container">
            <h1 class="m-2 text-center">Account Deletion Requests</h1>
            <hr class="my-2">
            <div class="table-responsive">
                <table class="table-striped small table" id="Table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>User Email</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $request->user->name ?? 'Deleted User' }}</td>
                                <td>{{ $request->user->email ?? 'N/A' }}</td>
                                <td>{{ $request->reason }}</td>
                                <td>
                                    <span class="badge @if($request->status == 'pending') bg-warning @elseif($request->status == 'approved') bg-success @else bg-danger @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if ($request->status == 'pending' && $request->user)
                                        <form action="{{ route("superadmin.deletion_requests.approve", $request->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button class="btn btn-danger btn-sm"
                                                onclick='return confirm("Are you sure you want to PERMANENTLY DELETE this user account?")' type="submit">
                                                Approve Deletion
                                            </button>
                                        </form>

                                        <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                            Reject
                                        </button>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route("superadmin.deletion_requests.reject", $request->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="rejectModalLabel{{ $request->id }}">Reject Deletion Request</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="rejection_reason" class="form-label">Rejection Reason</label>
                                                                <textarea class="form-control" name="rejection_reason" required rows="3" placeholder="Explain why the request is rejected..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject Request</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($request->status == 'rejected')
                                        <small class="text-muted">Rejected: {{ $request->rejection_reason }}</small>
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
