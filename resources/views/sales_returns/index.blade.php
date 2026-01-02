@extends("sales.app")

@section("content")
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-danger fs-2 fw-bold">Sales returns management</h2>
        </div>
        <hr>

        <hr class="mb-2">

        <!-- Sales Table -->
        <div class="table-responsive rounded-3 shadow-sm">
            <table class="table-striped table-hover table-bordered small table align-middle" id="Table">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Sales Name</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Sales Date</th>
                        <th>Reasons</th>
                        <th>Posted by</th>
                        <th>Approved By</th>
                        <th>Date</th>
                        <th>Status</th>
                        @hasrole("Owner")
                            <th>Actions</th>
                        @endhasrole
                    </tr>
                </thead>
                <tbody>
                    @foreach ($returns as $returns)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $returns->sale->item->name }}</td>
                            <td>{{ $returns->quantity }}</td>
                            <td>{{ $returns->refund_amount }}</td>
                            <td>{{ $returns->date }}</td>
                            <td>{{ $returns->reason ?? "NILL" }}</td>
                            <td>{{ $returns->staff->name }}</td>
                            <td>{{ $returns->approvedBy ? $returns->approvedBy->name : "Not approved" }}</td>
                            <td>{{ $returns->created_at }}</td>
                            <td>
                                @if ($returns->return_status == "pending")
                                    <span class="text-dark fw-bold">{{ $returns->return_status }}</span>
                                @elseif ($returns->return_status == "approved")
                                    <span class="text-success fw-bold">{{ $returns->return_status }}</span>
                                @else
                                    <span class="text-danger fw-bold">{{ $returns->return_status }}</span>
                                @endif
                            </td>
                            @hasrole("Owner")
                                <td>
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route("salesReturns.update") }}" class="d-inline" method="POST">
                                            @csrf
                                            <input name="return_id" type="hidden" value="{{ $returns->id }}">
                                            <input name="return_status" type="hidden" value="approved">
                                            <button class="btn btn-success btn-sm d-flex me-2"
                                                onclick="return confirm('Are you sure to approve?')" type="submit">
                                                <i class="bi bi-check"></i> Approve
                                            </button>
                                        </form>

                                        <form action="{{ route("salesReturns.update") }}" method="POST"
                                            style="margin-left: 5px;">
                                            @csrf
                                            <input name="return_id" type="hidden" value="{{ $returns->id }}">
                                            <input name="return_status" type="hidden" value="rejected">
                                            <button {{ $returns->return_status == "rejected" ? "disabled" : "" }}
                                                class="btn btn-danger btn-sm d-flex"
                                                onclick="return confirm('Are you sure to reject?')" type="submit">
                                                <i class="bi bi-x"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endhasrole

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
