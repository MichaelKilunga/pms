@extends('debts.app')

@section('content')
    <div class="row">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Stock Debt Installments</h1>
            <!-- Optional: Add Installment Button -->
                    <a href="{{ route('debts.index') }}" class="btn btn-primary mb-3"> Debts </a>

        </div>

        <div class="table-reponsive">
            <!-- Table of Installments -->
            <table class="table table-bordered mt-3" id="Table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Stock</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($installments as $installment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $installment->debt->stock->item->name }}({{$installment->debt->stock->batch_number}}-{{ $installment->debt->stock->supplier }})</td>
                            <td>{{ $installment->description }}</td>
                            <td>{{ number_format($installment->amount) }}</td>
                            <td>{{ $installment->created_at->format('Y-m-d H:i') }}</td>
                            <td>  
                               <form action="{{ route('installments.destroyinst', $installment->id) }}" method   ="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this debt?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><span class="bi bi-trash"></span></button>
                               </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection