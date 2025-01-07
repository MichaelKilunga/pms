{{-- Display all package's detail actions for edit, delete and back to the list. 
Database columns are here;
$request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'status' => 'required|boolean',
            'number_of_pharmacies' => 'required|numeric',
            'number_of_pharmacists' => 'required|numeric',
            'number_of_medicines' => 'required|numeric',
            'in_app_notification' => 'required|boolean',
            'email_notification' => 'required|boolean',
            'sms_notifications' => 'required|boolean',
            'online_support' => 'required|boolean',
            'number_of_owner_accounts' => 'required|numeric',
            'number_of_admin_accounts' => 'required|numeric',
            'reports' => 'required|boolean',
            'stock_transfer' => 'required|boolean',
            'stock_management' => 'required|boolean',
            'staff_management' => 'required|boolean',
            'receipts' => 'required|boolean',
            'analytics' => 'required|boolean',
            'whatsapp_chats' => 'required|boolean',
        ]); --}}
@extends('packages.app')

@section('content')
<div class="container">
    <h1>Package Details</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $package->name }}
            </div>
            <divi class="form-group">
                <strong>Price:</strong>
                {{ $package->price }}
            </div>
            <div class="form-group">
                <strong>Duration:</strong>
                {{ $package->duration }}
            </div>
            <div class="form-group">
                <strong>Status:</strong>
                {{ $package->status }}
            </div>
            <div class="form-group">
                <strong>Number of Pharmacies:</strong>
                {{ $package->number_of_pharmacies }}
            </div>
            <div class="form-group">
                <strong>Number of Pharmacists:</strong>
                {{ $package->number_of_pharmacists }}
            </div>
            <div class="form-group">
                <strong>Number of Medicines:</strong>
                {{ $package->number_of_medicines }}
            </div>
            <div class="form-group">
                <strong>In App Notification:</strong>
                {{ $package->in_app_notification }}
            </div> 
            <div class="form-group">
                <strong>Email Notification:</strong>
                {{ $package->email_notification }}
            </div>
            <div class="form-group">
                <strong>SMS Notifications:</strong>
                {{ $package->sms_notifications }}
            </div> 
            <div class="form-group">
                <strong>Online Support:</strong>
                {{ $package->online_support }}
            </div>
            <div class="form-group">
                <strong>Number of Owner Accounts:</strong>
                {{ $package->number_of_owner_accounts }}
            </div>
            <div class="form-group">
                <strong>Number of Admin Accounts:</strong>
                {{ $package->number_of_admin_accounts }}
            </div>
            <div class="form-group">
                <strong>Reports:</strong>
                {{ $package->reports }}
            </div>
            <div class="form-group">
                <strong>Stock Transfer:</strong>
                {{ $package->stock_transfer }}
            </div>
            <div class="form-group">
                <strong>Stock Management:</strong>
                {{ $package->stock_management }}
            </div>
            <div class="form-group">
                <strong>Staff Management:</strong>
                {{ $package->staff_management }}
            </div>
            <div class="form-group">
                <strong>Receipts:</strong>
                {{ $package->receipts }}
            </div>
            <div class="form-group">
                <strong>Analytics:</strong>
                {{ $package->analytics }}
            </div>
            <div class="form-group">
                <strong>Whatsapp Chats:</strong>
                {{ $package->whatsapp_chats }}
            </div>
            <div class="form-group">
                <a href="{{ route('packages') }}" class="btn btn-primary">Back</a>
                <a href="{{ route('packages.edit', $package->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('packages.destroy', $package->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger {{$package->id==1? 'disabled':''}}">Delete</button>
                </form>
            </div>
</div>
@endsection
