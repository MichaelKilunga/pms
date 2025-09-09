@extends('notifications.app')

@section('content')
    <div class="container mt-4">
        {{-- <p>{{'Unread: '.Auth::user()->unreadNotifications->count() }}</p> --}}
        @if (Auth::user()->unreadNotifications->count() == 0)
            <div class="p-5 rounded-4 shadow-lg text-center w-100 bg-light bg-gradient">
                <div class="mb-3">
                    <i class="bi bi-bell-slash text-primary display-4 bell-shake"></i>
                </div>

                <h4 class="fw-bold text-primary mb-2">No New Notifications</h4>
                <p class="text-muted mb-0">You’re fully up to date — wishing you a smooth and productive day! with pillpointone.</p>

            </div>
        @endif

        <style>
            @keyframes vibrate {

                0%,
                100% {
                    transform: rotate(0deg);
                }

                20% {
                    transform: rotate(-10deg);
                }

                40% {
                    transform: rotate(10deg);
                }

                60% {
                    transform: rotate(-8deg);
                }

                80% {
                    transform: rotate(8deg);
                }
            }

            .bell-shake {
                display: inline-block;
                animation: vibrate 1s infinite;
                animation-delay: 5s;
                /* kila sekunde moja inaanza tena */
            }
        </style>



        @if (Auth::user()->unreadNotifications->count() > 0)
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
