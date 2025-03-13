@extends('sales.app')

@section('content')
    <div class="container mt-4">
        {{-- return back --}}
        <div class="row d-flex justify-content-between m-2">
            <div class="text-primary text-left fs-4 col-md-6">
                <h2>Receipts</h2>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('sales') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table id="Table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Receipt No</th>
                            <th>Amount(TZS)</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($receipts as $receipt)
                            <tr>
                                {{-- loop number here --}}
                                <td>{{ $loop->iteration }}</td>
                                {{-- display total amount as currencies --}}
                                <td>{{ number_format($receipt->total_amount, 0) }}</td>
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
