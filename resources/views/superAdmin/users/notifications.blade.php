@extends("layouts.app")

@section("content")
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">Notification Preferences: {{ $user->name }}</h3>
                    <div class="card-toolbar">
                        <a class="btn btn-sm btn-secondary" href="{{ route("superadmin.users") }}">
                            <i class="bi bi-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>
                <form action="{{ route("superAdmin.users.notifications.update", $user->id) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if (session("success"))
                            <div class="alert alert-success">{{ session("success") }}</div>
                        @endif

                        <p class="text-muted mb-4">Control which notification channels are enabled for this user. If a
                            channel is disabled here, the user will not receive notifications via that channel regardless of
                            system-wide settings.</p>

                        <div class="form-group mb-3">
                            <div class="custom-control custom-switch">
                                <input {{ $user->wantsNotificationChannel("whatsapp") ? "checked" : "" }}
                                    class="custom-control-input" id="whatsapp" name="whatsapp" type="checkbox"
                                    value="true">
                                <label class="custom-control-label" for="whatsapp">Enable WhatsApp Notifications</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="custom-control custom-switch">
                                <input {{ $user->wantsNotificationChannel("sms") ? "checked" : "" }}
                                    class="custom-control-input" id="sms" name="sms" type="checkbox"
                                    value="true">
                                <label class="custom-control-label" for="sms">Enable SMS Notifications</label>
                            </div>
                        </div>

                        {{-- Mail is usually essential, but allowed to toggle if needed --}}
                        <div class="form-group mb-3">
                            <div class="custom-control custom-switch">
                                <input {{ $user->wantsNotificationChannel("mail") ? "checked" : "" }}
                                    class="custom-control-input" id="mail" name="mail" type="checkbox"
                                    value="true">
                                <label class="custom-control-label" for="mail">Enable Email Notifications</label>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="submit">Update Preferences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
