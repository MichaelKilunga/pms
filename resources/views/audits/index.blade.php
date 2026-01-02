@extends("audits.app")

@section("content")
    <div class="table-responsive container">
        <h2>Audit Logs</h2>
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
                        <td>{{ $audit->user->name ?? "N/A" }}</td>
                        <td>{{ $audit->event }}</td>
                        <td>{{ json_encode($audit->old_values) }}</td>
                        <td>{{ json_encode($audit->new_values) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                {{ $audit->index }}
            </tfoot>
        </table>

        {{ $audits->links() }}
    </div>
@endsection
