@extends('contracts.app')

@section('content')
    <div class="container mt-4">
        {{-- Show owner his current subscription contract plan --}}
        <h1>Your Subscription Plan</h1>
        <div class="card">
            <div class="card-body">
                {{-- iff no data display message --}}
                @if ($contract)
                    <h5 class="card-title">Current Plan: {{ $contract->package['name'] }}</h5>
                    <p class="card-text">Status: {{ $contract->status }}</p>
                    <p class="card-text">Payment Status: {{ $contract->payment_status }}</p>
                    <a href="{{ route('contracts.users.edit', $contract->id) }}" class="btn btn-primary">Change Plan</a>
                @else
                    <p class="card-text">You have no active subscription plan</p>
                    <a href="{{ route('contracts.users.create') }}" class="btn btn-primary">Subscribe</a>
                @endif
            </div>
        </div>
    @endsection
