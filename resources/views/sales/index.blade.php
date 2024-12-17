@extends('sales.app')

@section('content')
@if (session('success'))
<span class="bg-success">
    {{session('success')}}!
</span>
@endif

    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Sales</h2>
            <div>
                <a href="{{ route('sales.create') }}" class="btn btn-success">Add New Sales</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sales Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sales)
                        <tr>
                            <td>{{ $sales->id }}</td>
                            <td>{{ $sales->item->name }}</td>
                            <td>{{ $sales->total_price }}</td>
                            <td>{{ $sales->quantity }}</td>
                            <td>{{ $sales->date }}</td>
                            <td>
                                <a href="{{ route('sales.show', $sales->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye" ></i></a>
                                <a href="{{ route('sales.edit', $sales->id) }}" class="btn btn-success btn-sm"><i class="bi bi-pencil" ></i></a>
                                <form action="{{ route('sales.destroy', $sales->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="confirm('Do you want to delete this sales?')" class="btn btn-danger btn-sm"><i class="bi bi-trash" ></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


 {{-- {{$sales}} --}}