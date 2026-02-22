@extends("contracts.app")

@section("content")
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-2">
            <h1>All Contracts</h1>
            <a class="btn btn-primary" href="{{ route("contracts.admin.create") }}">Create Contract</a>
        </div>

        <table class="table-striped small mt-4 table" id="Table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Owner</th>
                    <th>Package</th>
                    <th>Package Duration</th>
                    <th>Package Amount</th>
                    <th>Status</th>
                    <th>Payment Status</th>
                    <th>Time remained</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contracts as $contract)
                    <tr class="{{ $contract->payment_notified ? 'table-info' : '' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $contract->owner->name }}</td>
                        <td>
                            {{ $contract->package->name }}
                            @if($contract->is_current_contract)
                                <span class="badge bg-success">Current</span>
                            @endif
                        </td>
                        {{-- different of enddate and startdate in days --}}
                        <td>{{ \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) }}
                            days
                            ({{ \Carbon\Carbon::parse($contract->start_date)->diffInDays(\Carbon\Carbon::parse($contract->end_date)) / 30 }}
                            Month)</td>
                        <td>{{ number_format($contract->amount) }}
                        </td>
                        <td>
                             <span class="badge bg-secondary">{{ $contract->status }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $contract->payment_status == 'payed' ? 'success' : 'warning' }}">
                                {{ $contract->payment_status }}
                            </span>
                            @if($contract->payment_notified)
                                <span class="badge bg-info text-white" title="Owner notified payment"><i class="bi bi-bell-fill"></i> Notified</span>
                            @endif
                        </td>
                        {{-- show time remained in human readable format, use time difference between now and end date, if time has passed show negative passed time --}}
                        @if ($contract->status == "active")
                            @if (\Carbon\Carbon::parse($contract->end_date) < now())
                                <td class="text-danger">Expired
                                    {{ \Carbon\Carbon::parse($contract->end_date)->diffForHumans() }}
                                </td>
                            @else
                                <td>{{ \Carbon\Carbon::parse($contract->end_date)->diffForHumans() }}</td>
                            @endif
                        @elseif ($contract->status == "inactive")
                            <td>Not started</td>
                        @elseif ($contract->status == "graced")
                            @if (\Carbon\Carbon::parse($contract->grace_end_date) < now())
                                <td class="text-danger">Grace Period Expired
                                    {{ \Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans() }}
                                </td>
                            @else
                                <td>In Grace Period
                                    {{ \Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans() }}</td>
                            @endif
                        @else
                           <td>-</td>
                        @endif

                        <td>
                            {{-- below create a a view button and a modal to view contract when view action button is clicked --}}
                            <a class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#viewModal{{ $contract->id }}"
                                href="#" title="View Details"><i class="bi bi-eye"></i></a>
                            
                            <a class="btn btn-sm btn-outline-warning" href="{{ route('contracts.admin.edit', $contract->id) }}" title="Edit Contract">
                                <i class="bi bi-pencil"></i>
                            </a>
                            
                            {{-- Action button to initiate contract (make current) - Only after payment --}}
                            @if($contract->payment_status == 'payed' && (!$contract->is_current_contract || $contract->status == 'inactive') && \Carbon\Carbon::parse($contract->end_date)->isFuture())
                                <a class="btn btn-sm btn-info" href="{{ route('contracts.admin.initiate', $contract->id) }}" title="Initiate / Activate Contract" onclick="return confirm('Initiate and Activate this contract?')">
                                    <i class="bi bi-play-fill text-white"></i>
                                </a>
                            @endif

                            {{-- action button to confirm payement --}}
                            <a class="btn btn-sm btn-success {{ $contract->payment_status == "payed" ? "disabled" : "" }}"
                                href="{{ route("contracts.admin.confirm", $contract->id) }}"
                                onclick="return confirm('Do you want to confirm payment?')" title="Confirm Payment"><i
                                    class="bi bi-cash-coin"></i></a>
                            
                            {{-- action button to grace a grace period --}}
                            <a class="btn btn-sm btn-warning {{ \Carbon\Carbon::parse($contract->end_date) < now() && !$contract->grace_end_date ? "" : "disabled" }}"
                                href="javascript:void(0);"
                                onclick="let daysToAdd = prompt('How many days do you want to add?'); 
                                     if (daysToAdd !== null && !isNaN(daysToAdd)) {
                                         window.location.href = '{{ route("contracts.admin.grace", $contract->id) }}' + '?days=' + daysToAdd;
                                     } else {
                                         alert('Please enter a valid number of days.');
                                     }" title="Add Grace Period">
                                <i class="bi bi-clock"></i>
                            </a>

                            {{-- modal --}}
                            <div aria-hidden="true" aria-labelledby="viewModalLabel" class="modal fade"
                                id="viewModal{{ $contract->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModalLabel">View Contract</h5>
                                            <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                type="button"></button>
                                        </div>
                                        <div class="modal-body text-left">
                                            <p><strong>Owner:</strong> {{ $contract->owner->name }}</p>
                                            <p><strong>Package:</strong> {{ $contract->package->name }}</p>
                                            <p><strong>Pricing Strategy:</strong> {{ $contract->pricing_strategy }}</p>
                                            <p><strong>Amount:</strong> TZS {{ number_format($contract->amount) }}</p>
                                            <p><strong>Status:</strong> {{ $contract->status }}</p>
                                            <p><strong>Payment Status:</strong> {{ $contract->payment_status }}</p>
                                            <p><strong>Payment Notified:</strong> {{ $contract->payment_notified ? 'Yes' : 'No' }}</p>
                                            <p><strong>Is Current:</strong> {{ $contract->is_current_contract ? 'Yes' : 'No' }}</p>
                                            <p><strong>Start Date:</strong> {{ $contract->start_date }}</p>
                                            <p><strong>End Date:</strong> {{ $contract->end_date }}</p>
                                            @if($contract->grace_end_date)
                                                <p><strong>Grace Period:</strong>
                                                    {{ \Carbon\Carbon::parse($contract->grace_end_date)->diffForHumans() }}</p>
                                            @endif
                                            <p><strong>Created At:</strong> {{ $contract->created_at }}</p>
                                            
                                            <hr>
                                            <h6>Details:</h6>
                                            <pre>{{ json_encode($contract->details, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal"
                                                type="button">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
