@extends('contracts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-2">
        <h1>All Contracts</h1>
            <a href="{{ route('contracts.admin.create') }}" class="btn btn-primary">Create Contract</a>
        </div>
        
    <table class="table mt-4 table-striped" id="Table">
        <thead>
            <tr>
                <th>#</th>
                <th>Owner</th>
                <th>Package</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contracts as $contract)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $contract->owner->name }}</td>
                    <td>{{ $contract->package->name }}</td>
                    <td>{{ $contract->status }}</td>
                    <td>{{ $contract->payment_status }}</td>
                    <td>
                        <a href="{{ route('contracts.admin.show', $contract->id) }}">View</a>
                        <a href="{{ route('contracts.admin.edit', $contract->id) }}">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
