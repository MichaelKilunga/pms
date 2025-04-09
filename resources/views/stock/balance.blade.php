@extends('stock.app')

@section('content')
    <div class="container">
        <h4 class="text-primary fs-2 fw-bold mt-2 mb-1">Stock Balance Summary</h4>
        <hr class="mt-2 mb-2">

        {{-- <hr class="mb-2"> --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medicine Name</th>
                        <th>Stocked Quantity</th>
                        <th>Remaining Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockBalances as $stock)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stock->item->name }}</td>
                            <td>{{ $stock->quantity }}</td>
                            <td>{{ $stock->remain_Quantity }}</td>
                            <td>
                                @if ($stock->remain_Quantity > 0)
                                <span class="text-success fw-bold"><p>Available</p></span>
                                @else
                                <span class="text-danger fw-bold">Finished</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No stock records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
