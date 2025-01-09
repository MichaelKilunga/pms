@extends('packages.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between">
            <h1>Subscription Plans</h1>
            <a href="{{ route('packages.create') }}" class="btn btn-primary mb-3">Add New Package</a>
        </div>
        {{-- Use div classess for responsiveness of our table --}}
        
        <div class="table-responsive">
            {{-- Use table classess for styling our table --}}
            <table class="table table-bordered" id="Table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Status</th>
                        {{-- <th>Number of Pharmacies</th>
                        <th>Number of Pharmacists</th>
                        <th>Number of Medicines</th>
                        <th>In App Notification</th>
                        <th>Email Notification</th>
                        <th>SMS Notifications</th>
                        <th>Online Support</th>
                        <th>Number of Owner Accounts</th>
                        <th>Number of Admin Accounts</th>
                        <th>Reports</th>
                        <th>Stock Transfer</th>
                        <th>Stock Management</th>
                        <th>Staff Management</th>
                        <th>Receipts</th>
                        <th>Analytics</th>
                        <th>Whatsapp Chats</th> --}}
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($packages as $package)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->price }}</td>
                            <td>{{ $package->duration }}</td>
                            <td>{{ $package->status }}</td>
                            {{-- <td>{{ $package->number_of_pharmacies }}</td>
                            <td>{{ $package->number_of_pharmacists }}</td>
                            <td>{{ $package->number_of_medicines }}</td>
                            <td>{{ $package->in_app_notification }}</td>
                            <td>{{ $package->email_notification }}</td>
                            <td>{{ $package->sms_notifications }}</td>
                            <td>{{ $package->online_support }}</td>
                            <td>{{ $package->number_of_owner_accounts }}</td>
                            <td>{{ $package->number_of_admin_accounts }}</td>
                            <td>{{ $package->reports }}</td>
                            <td>{{ $package->stock_transfer }}</td>
                            <td>{{ $package->stock_management }}</td>
                            <td>{{ $package->staff_management }}</td>
                            <td>{{ $package->receipts }}</td>
                            <td>{{ $package->analytics }}</td>
                            <td>{{ $package->whatsapp_chats }}</td> --}}
                            <td>
                                <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-primary"><i class="bi bi-pencil" ></i></a>
                                <a href="{{ route('packages.show', $package->id) }}" class="btn btn-info"><i class="bi bi-eye" ></i></a>
                                <form action="{{ route('packages.destroy', $package->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash" ></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
