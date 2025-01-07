@extends('packages.app')

@section('content')
    <div class="container">
        <h1>Edit Package</h1>
        <form action="{{ route('packages.update', $package->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $package->name }}" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" name="price" id="price" class="form-control" value="{{ $package->price }}"
                    required>
            </div>
            <div class="form-group">
                <label for="duration">Duration (days):</label>
                <input type="text" name="duration" id="duration" class="form-control" value="{{ $package->duration }}"
                    required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="1" {{ $package->status ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$package->status ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="form-group">
                <label for="number_of_pharmacies">Number of Pharmacies:</label>
                <input type="number" name="number_of_pharmacies" id="number_of_pharmacies" class="form-control"
                    value="{{ $package->number_of_pharmacies }}" required>
            </div>
            <div class="form-group">
                <label for="number_of_pharmacists">Number of Pharmacists:</label>
                <input type="number" name="number_of_pharmacists" id="number_of_pharmacists" class="form-control"
                    value="{{ $package->number_of_pharmacists }}" required>
            </div>
            <div class="form-group">
                <label for="number_of_medicines">Number of Medicines:</label>
                <input type="number" name="number_of_medicines" id="number_of_medicines" class="form-control"
                    value="{{ $package->number_of_medicines }}" required>
            </div>
            <div class="form-group">
                <label for="in_app_notification">In App Notification:</label>
                <select name="in_app_notification" id="in_app_notification" class="form-control">
                    <option value="1" {{ $package->in_app_notification ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$package->in_app_notification ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email_notification">Email Notification:</label>
                <select name="email_notification" id="email_notification" class="form-control">
                    <option value="1" {{ $package->email_notification ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$package->email_notification ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sms_notifications">SMS Notifications:</label>
                <select name="sms_notifications" id="sms_notifications" class="form-control">
                    <option value="1" {{ $package->sms_notifications ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$package->sms_notifications ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="online_support">Online Support:</label>
                <select name="online_support" id="online_support" class="form-control">
                    <option value="1" {{ $package->online_support ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$package->online_support ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="number_of_owner_accounts">Number of Owner Accounts:</label>
                <input type="number" name="number_of_owner_accounts" id="number_of_owner_accounts" class="form-control"
                    value="{{ $package->number_of_owner_accounts }}" required>
            </div>
            <div class="form-group">
                <label for="number_of_admin_accounts">Number of Admin Accounts:</label>
                <input type="number" name="number_of_admin_accounts" id="number_of_admin_accounts" class="form-control"
                    value="{{ $package->number_of_admin_accounts }}" required>
            </div>
            <div class="form-group">
                <label for="reports">Reports:</label>
                <select name="reports" id="reports" class="form-control">
                    <option value="1" {{ $package->reports ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$package->reports ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="stock_transfer">Stock Transfer:</label>
                <select name="stock_transfer" id="stock_transfer" class="form-control">
                    <option value="1" {{ $package->stock_transfer ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$package->stock_transfer ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="stock_management">Stock Management:</label>
                <select name="stock_management" id="stock_management" class="form-control">
                    <option value="1" {{ $package->stock_management ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$package->stock_management ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="staff_management">Staff Management:</label>
                <select name="staff_management" id="staff_management" class="form-control">
                    <option value="1" {{ $package->staff_management ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$package->staff_management ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="receipts">Receipts:</label>
                <select name="receipts" id="receipts" class="form-control">
                    <option value="1" {{ $package->receipts ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$package->receipts ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="analytics">Analytics:</label>
                <select name="analytics" id="analytics" class="form-control">
                    <option value="1" {{ $package->analytics ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$package->analytics ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="form-group">
                <label for="whatsapp_chats">Whatsapp Chats:</label>
                <select name="whatsapp_chats" id="whatsapp_chats" class="form-control">
                    <option value="1" {{ $package->whatsapp_chats ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$package->whatsapp_chats ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="d-flex justify-content-between m-2">
                <a href="{{ route('packages') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection
