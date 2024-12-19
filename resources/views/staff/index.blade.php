@extends('staff.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h2>Staff</h2>
            <div>
                <a href="{{ route('staff.create') }}" class="btn btn-success">Add New staff</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Staff Name</th>
                        <th>Pharmacy</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staff as $staff)
                        <tr>
                            <td>{{ $staff->id }}</td>
                            <td>{{ $staff->user->name }}</td>
                            <td>{{ $staff->pharmacy->name }}</td>
                            <td>
                                <a href="{{ route('staff.show', $staff->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye" ></i></a>
                                <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-success btn-sm"><i class="bi bi-pencil" ></i></a>
                                <form action="{{ route('staff.destroy', $staff->user_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this staff?')" class="btn btn-danger btn-sm"><i class="bi bi-trash" ></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection


 {{-- {{$staff}} --}}