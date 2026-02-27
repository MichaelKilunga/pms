@extends('superAdmin.app')

@section('content')
<style>
    .channel-card {
        transition: all 0.2s;
        cursor: pointer;
        border: 2px solid transparent;
    }
    .channel-card.selected {
        border-color: #4e73df;
        background-color: #f8f9fc;
    }
    .channel-card input:checked + .card-body {
        border-color: #4e73df;
    }
</style>

<div class="container-fluid mt-4 mb-5">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('superAdmin.notifications.history') }}">Notification History</a></li>
                <li class="breadcrumb-item active" aria-current="page">Compose Broadcast</li>
            </ol>
        </nav>
        <h2 class="fw-bold text-gray-800">Compose New Broadcast</h2>
        <p class="text-muted">Draft and send a system-wide notification across multiple channels.</p>
    </div>

    <form action="{{ route('superAdmin.notifications.store') }}" method="POST" id="broadcastForm">
        @csrf
        <div class="row g-4">
            <!-- Left Column: Content -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold">Message Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Notification Title</label>
                            <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" placeholder="e.g. System Maintenance Notice" required value="{{ old('title') }}">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Message Body</label>
                            <textarea name="body" class="form-control @error('body') is-invalid @enderror" rows="8" placeholder="Enter your notification message here..." required>{{ old('body') }}</textarea>
                            @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle me-1"></i> For SMS and WhatsApp, we recommend keeping messages concise.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold">Select Channels</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @php
                                $channels = [
                                    ['id' => 'database', 'name' => 'In-App Notifications', 'icon' => 'bell-fill', 'color' => 'primary', 'desc' => 'Send to user dashboard'],
                                    ['id' => 'mail', 'name' => 'Email Broadcast', 'icon' => 'envelope-fill', 'color' => 'info', 'desc' => 'High visibility Inbox delivery'],
                                    ['id' => 'sms', 'name' => 'Push SMS', 'icon' => 'chat-right-text-fill', 'color' => 'success', 'desc' => 'Direct to mobile phone'],
                                    ['id' => 'whatsapp', 'name' => 'WhatsApp Web', 'icon' => 'whatsapp', 'color' => 'success', 'desc' => 'Meta API Official Channel'],
                                ];
                            @endphp

                            @foreach($channels as $channel)
                            <div class="col-sm-6 col-md-3">
                                <label class="card h-100 channel-card shadow-none border rounded-3 p-1">
                                    <input type="checkbox" name="channels[]" value="{{ $channel['id'] }}" class="d-none" {{ (is_array(old('channels')) && in_array($channel['id'], old('channels'))) || $channel['id'] == 'database' ? 'checked' : '' }}>
                                    <div class="card-body text-center p-3">
                                        <div class="mb-2">
                                            <i class="bi bi-{{ $channel['icon'] }} text-{{ $channel['color'] }} fs-2"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1">{{ $channel['name'] }}</h6>
                                        <small class="text-muted d-block small">{{ $channel['desc'] }}</small>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('channels') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column: Audience -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 h-100">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold">Target Audience</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Filter by Roles</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($roles as $role)
                                <div class="form-check form-check-inline bg-light border p-2 px-3 rounded-pill m-0">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="target_roles[]" value="{{ $role }}" id="role_{{ $role }}">
                                    <label class="form-check-label small fw-bold" for="role_{{ $role }}">{{ ucfirst($role) }}s</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Filter by Package</label>
                            <select name="target_packages[]" class="form-select" multiple size="3">
                                @foreach($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted small">Hold Ctrl/Cmd to select multiple</small>
                        </div>

                        <div class="border-top pt-4 mt-4">
                            <label class="form-label fw-bold">Specific Recipients (Overrides filters)</label>
                            <select name="specific_users[]" class="form-select select2" multiple>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="alert alert-info border-0 bg-info-subtle mt-5 small shadow-sm">
                            <i class="bi bi-lightbulb me-1"></i> <strong>Tip:</strong> If no filters are selected, the notification will be sent to <strong>All Registered Users</strong>.
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold py-3 shadow-sm rounded-3">
                            <i class="bi bi-send-fill me-2"></i> Dispatch Notification
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle selected class on channel cards
        const checkboxes = document.querySelectorAll('.channel-card input[type="checkbox"]');
        checkboxes.forEach(cb => {
            if(cb.checked) cb.closest('.channel-card').classList.add('selected');
            
            cb.addEventListener('change', function() {
                if(this.checked) {
                    this.closest('.channel-card').classList.add('selected');
                } else {
                    this.closest('.channel-card').classList.remove('selected');
                }
            });
        });
    });
</script>
@endsection
