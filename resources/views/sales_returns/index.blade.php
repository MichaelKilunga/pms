@extends('sales.app')

@section('content')
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between mb-3">
            <h2 class="text-danger fs-2 fw-bold">Sales returns management</h2>
        </div>
        <hr>

        <hr class="mb-2">

        <!-- Sales Table -->
        <div class="table-responsive shadow-sm rounded-3">
            <table class="table table-striped table-hover table-bordered align-middle" id="Table">
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
                        @if (Auth::user()->role == 'owner')
                            <th>Actions</th>
                        @endif
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
                            <td>{{ $returns->reason ?? 'NILL' }}</td>
                            <td>{{ $returns->staff->name }}</td>
                            <td>{{ $returns->approvedBy ? $returns->approvedBy->name : 'Not approved' }}</td>
                            <td>{{ $returns->created_at }}</td>
                            <td>
                                @if ($returns->return_status == 'pending')
                                    <span class=" text-dark fw-bold">{{ $returns->return_status }}</span>
                                @elseif ($returns->return_status == 'approved')
                                    <span class="text-success fw-bold">{{ $returns->return_status }}</span>
                                @else
                                    <span class="text-danger fw-bold">{{ $returns->return_status }}</span>
                                @endif
                            </td>
                            @if (Auth::user()->role == 'owner')
                                <td>
                                    <div class="d-flex align-items-center">
                                        <form action="{{ route('salesReturns.update') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="return_id" value="{{ $returns->id }}">
                                            <input type="hidden" name="return_status" value="approved">
                                            <button type="submit" class="btn btn-success btn-sm me-2 d-flex"
                                                onclick="return confirm('Are you sure to approve?')">
                                                <i class="bi bi-check"></i> Approve
                                            </button>
                                        </form>

                                        <form action="{{ route('salesReturns.update') }}" method="POST"
                                            style="margin-left: 5px;">
                                            @csrf
                                            <input type="hidden" name="return_id" value="{{ $returns->id }}">
                                            <input type="hidden" name="return_status" value="rejected">
                                            <button type="submit" class="btn btn-danger btn-sm d-flex" {{$returns->return_status == 'rejected' ? 'disabled' : ''}}
                                                onclick="return confirm('Are you sure to reject?')">
                                                <i class="bi bi-x"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
