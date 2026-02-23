@extends('audits.app')

@section('content')
    <div class="container-fluid py-4">
        <h2 class="text-primary fw-bold h2 mb-4">Audit Logs</h2>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('audits.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="user_id" class="form-label small fw-bold">User</label>
                        <select name="user_id" id="user_id" class="form-select form-select-sm">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="event" class="form-label small fw-bold">Event</label>
                        <select name="event" id="event" class="form-select form-select-sm">
                            <option value="">All Events</option>
                            @foreach($events as $event)
                                <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                    {{ ucfirst($event) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label small fw-bold">From</label>
                        <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label small fw-bold">To</label>
                        <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-sm px-4">Filter</button>
                        <a href="{{ route('audits.index') }}" class="btn btn-outline-secondary btn-sm px-4">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive bg-white rounded shadow-sm">
            <table class="table table-hover align-middle small mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Timestamp</th>
                        <th>User</th>
                        <th>Event</th>
                        <th>Auditable</th>
                        <th>Old Values</th>
                        <th>New Values</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($audits as $audit)
                        <tr>
                            <td class="ps-3 text-nowrap">
                                <span class="text-secondary small">{{ $audit->created_at->format('Y-m-d') }}</span><br>
                                <span class="fw-bold">{{ $audit->created_at->format('H:i:s') }}</span>
                            </td>
                            <td>
                                @php
                                    $user = \App\Models\User::find($audit->user_id);
                                @endphp
                                @if($user)
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.7rem;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-bold text-dark">{{ $user->name }}</span>
                                    </div>
                                @elseif($audit->user_id)
                                    <span class="badge bg-light text-danger border border-danger-subtle fw-normal">
                                        <i class="fas fa-user-times me-1"></i>Deleted (ID: {{ $audit->user_id }})
                                    </span>
                                @else
                                    <span class="text-muted italic"><i class="fas fa-robot me-1"></i>System</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($audit->event) {
                                        'created' => 'bg-success-subtle text-success border-success-subtle',
                                        'updated' => 'bg-info-subtle text-info border-info-subtle',
                                        'deleted' => 'bg-danger-subtle text-danger border-danger-subtle',
                                        default => 'bg-light text-secondary border-secondary-subtle'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} border px-2 py-1">
                                    {{ ucfirst($audit->event) }}
                                </span>
                            </td>
                            <td class="small">
                                <div class="text-dark fw-bold">{{ class_basename($audit->auditable_type) }}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">ID: {{ $audit->auditable_id }}</div>
                            </td>
                            <td style="max-width: 200px;">
                                @if($audit->old_values)
                                    <pre class="p-2 bg-light rounded border mb-0" style="max-height: 80px; overflow-y: auto; font-size: 0.65rem; white-space: pre-wrap;">{{ json_encode($audit->old_values, JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td style="max-width: 200px;">
                                @if($audit->new_values)
                                    <pre class="p-2 bg-light rounded border mb-0" style="max-height: 80px; overflow-y: auto; font-size: 0.65rem; white-space: pre-wrap;">{{ json_encode($audit->new_values, JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted mb-2"><i class="fas fa-search fa-2x"></i></div>
                                <div class="text-muted">No audit logs found matching your filters.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $audits->links() }}
        </div>
    </div>
@endsection
