@extends('audits.app')

@section('content')
    <div class="table-responsive container">
        <h2 class="text-primary fw-bold h2 mb-2">Audit Logs</h2>
        <table class="table-bordered table-striped small table" id="Table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>Old Values</th>
                    <th>New Values</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($audits as $audit)
                    <tr>
                        <td>{{ $audit->created_at }}</td>
                        <td>{{ \App\Models\User::find($audit->user_id)?->name ?? 'System' }}</td>
                        <td>{{ $audit->event }}</td>
                        <td>{{ json_encode($audit->old_values) }}</td>
                        <td>{{ json_encode($audit->new_values) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $audits->links() }}
        </div>
    </div>
@endsection
