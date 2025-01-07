@extends('sales.app')

@section('content')
    <div class="container mt-4">
        {{-- return back --}}
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('sales') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2>Receipts</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Receipt No</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($receipts as $receipt)
                            <tr>
                                {{-- loop number here --}}
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $receipt->total_amount }}</td>
                                {{-- format date to date-month-year --}}
                                <td>{{ date('d-m-Y', strtotime($receipt->date)) }}</td>
                                {{-- implement a print button, when clicked send request with date as parameter, guard the content so that it may not be altered by the browther --}}
                                <td>
                                    <a href="{{ route('printReceipt', ['date' => $receipt->date]) }}"
                                        class="btn btn-primary"><i class="bi bi-printer" ></i> Print</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
