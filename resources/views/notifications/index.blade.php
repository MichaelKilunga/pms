@extends("notifications.app")

@section("content")
    <div class="container mt-4">
        @if (Auth::user()->unreadNotifications->count() == 0 && Auth::user()->notifications->count() == 0)
            <div class="rounded-4 w-100 bg-light bg-gradient p-5 text-center shadow-lg">
                <div class="mb-3">
                    <i class="bi bi-bell-slash text-primary display-4 bell-shake"></i>
                </div>
                <h4 class="fw-bold text-primary mb-2">No Notifications</h4>
                <p class="text-muted small mb-0">You’re fully up to date!</p>
            </div>
        @else
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold text-primary">Your Notifications</h4>
                @if (Auth::user()->unreadNotifications->count() > 0)
                    <a class="btn btn-sm btn-outline-success rounded-pill px-3" href="{{ route("notifications.readAll") }}">
                        <i class="bi bi-check-all me-1"></i> Mark all Read
                    </a>
                @endif
            </div>

            <div class="list-group list-group-flush rounded-3 overflow-hidden shadow-sm">
                @foreach (Auth::user()->notifications as $notification)
                    <div
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-start {{ !$notification->read_at ? "bg-primary-subtle" : "" }} p-3">
                        <div class="me-auto ms-2">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="fw-bold {{ !$notification->read_at ? "text-primary" : "text-dark" }} mb-1">
                                    {{ $notification->data["title"] ?? ($notification->data["type"] ?? "Notification") }}
                                </h6>
                                <small class="text-muted"
                                    style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="small text-muted mb-1">
                                {{ $notification->data["body"] ?? ($notification->data["message"] ?? "") }}</p>

                            @if (isset($notification->data["action_url"]) || isset($notification->data["action"]))
                                <a class="btn btn-sm btn-link text-decoration-none small p-0"
                                    href="{{ $notification->data["action_url"] ?? ($notification->data["action"] ?? "#") }}">
                                    View Details <i class="bi bi-arrow-right"></i>
                                </a>
                            @endif
                        </div>

                        @if (!$notification->read_at)
                            <form action="{{ route("notifications.read", $notification->id) }}" class="ms-3"
                                method="POST">
                                @csrf
                                <button class="btn btn-sm btn-light text-primary rounded-circle shadow-sm"
                                    title="Mark as Read">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-muted ms-3"><i class="bi bi-check2-all"></i></span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
