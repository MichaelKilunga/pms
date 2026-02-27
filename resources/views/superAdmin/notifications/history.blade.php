@extends('superAdmin.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-gray-800">Notification Broadcast History</h2>
            <p class="text-muted">Manage and track all manual system-wide notifications.</p>
        </div>
        <a href="{{ route('superAdmin.notifications.compose') }}" class="btn btn-primary d-flex align-items-center">
            <i class="bi bi-plus-lg me-2"></i> Compose New Broadcast
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Date & Time</th>
                            <th>Title</th>
                            <th>Target Audience</th>
                            <th>Channels</th>
                            <th>Status</th>
                            <th>Sent To</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($broadcasts as $broadcast)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $broadcast->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $broadcast->created_at->format('H:i A') }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-truncate" style="max-width: 200px;">{{ $broadcast->title }}</div>
                                <small class="text-muted text-truncate d-block" style="max-width: 200px;">{{ $broadcast->body }}</small>
                            </td>
                            <td>
                                @php
                                    $criteria = $broadcast->target_criteria;
                                    $roles = $criteria['roles'] ?? [];
                                    $packages = $criteria['packages'] ?? [];
                                    $users = $criteria['users'] ?? [];
                                @endphp
                                @if(!empty($users))
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">{{ count($users) }} Specific Users</span>
                                @elseif(empty($roles) && empty($packages))
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">All Users</span>
                                @else
                                    @foreach($roles as $role)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ ucfirst($role) }}s</span>
                                    @endforeach
                                    @foreach($packages as $pkgId)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Pkg #{{ $pkgId }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @foreach($broadcast->channels as $channel)
                                    <i class="bi bi-{{ $channel == 'database' ? 'bell' : ($channel == 'mail' ? 'envelope' : ($channel == 'sms' ? 'chat-right-text' : 'whatsapp')) }} text-muted me-1" title="{{ ucfirst($channel) }}"></i>
                                @endforeach
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-secondary',
                                        'processing' => 'bg-warning',
                                        'completed' => 'bg-success',
                                        'failed' => 'bg-danger'
                                    ][$broadcast->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }} opacity-75">{{ ucfirst($broadcast->status) }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $broadcast->sent_count }}</span> recipients
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-light border" title="View Details"><i class="bi bi-eye"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x display-4 d-block mb-3 opacity-25"></i>
                                No broadcast history found. Click "Compose" to start notifying your users.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($broadcasts->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $broadcasts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
