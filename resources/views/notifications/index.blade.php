@extends('notifications.app')

@section('content')
<div class="container mt-4">
    <h2>Notifications</h2>
    <ul class="list-group">
        @foreach($notifications as $notification)
        <li class="list-group-item d-flex justify-content-between align-items-center {{ $notification->status ? 'list-group-item-light' : 'list-group-item-warning' }}">
            <div>
                <h5>{{ $notification->title }}</h5>
                <p>{{ $notification->message }}</p>
                <small>{{ $notification->created_at->diffForHumans() }}</small>
            </div>
            @if(!$notification->status)
            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-success">Mark as Read</button>
            </form>
            @endif
        </li>
        @endforeach
    </ul>
</div>
@endsection
