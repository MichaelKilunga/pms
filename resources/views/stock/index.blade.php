@extends('stock.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Stock</h2>
            <div>
                <a href="{{ route('stock.create') }}" class="btn btn-success">Add New Stock</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Stock Name</th>
                        <th>Selling Price</th>
                        <th>Buying Price</th>
                        <th>Quantity</th>
                        <th>In Date</th>
                        <th>Expire Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stocks as $stock)
                        <tr>
                            <td>{{ $stock->id }}</td>
                            <td>{{ $stock->item->name }}</td>
                            <td>{{ $stock->selling_price }}</td>
                            <td>{{ $stock->buying_price }}</td>
                            <td>{{ $stock->quantity }}</td>
                            <td>{{ $stock->in_date }}</td>
                            <td>{{ $stock->expire_date }}</td>
                          
                            <td>
                                <a href="{{ route('stock.show', $stock->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye" ></i></a>
                                <a href="{{ route('stock.edit', $stock->id) }}" class="btn btn-success btn-sm"><i class="bi bi-pencil" ></i></a>
                                <form action="{{ route('stock.destroy', $stock->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash" ></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


 {{-- {{$stock}} --}}