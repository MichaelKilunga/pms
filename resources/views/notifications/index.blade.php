@extends('notifications.app')

@section('content')
    <div class="container mt-4">
        @if ($unreadNotifications == 0)
            <p class="text-center text-primary italic">no new notification!!</p>
        @endif
        @if ($unreadNotifications > 0)
            <div class="d-flex justify-content-between">
                <h1>Notifications</h1>
                <a class="btn btn-success" href="{{ route('notifications.readAll') }}">Mark all Read</a>
            </div>
        @endif
        <ul class="list-group">
            @foreach (Auth::user()->notifications as $notification)
            {{-- @foreach (Auth::user()->notifications as $notification) --}}
                @if (!$notification->read_at)
                    <li
                        class="mt-2 list-group-item-{{ $notification->data['type'] }} list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="list-group-item-danger">{{ $notification->data['type'] }}</h5>
                            <p>{{ $notification->data['message'] }}</p>
                            <small>{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        @if (!$notification->read_at)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-success"><i class="bi bi-book"> mark read</i></button>
                            </form>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
@endsection
