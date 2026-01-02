@extends("agent.app")

@section("content")
    <div class="container">

        <div class="d-flex justify-content-between mt-4">
            <h1 class="h5 text-primary">Manage Pharmacies</h1>
            <a class="btn btn-success m-1" data-bs-target="#addPharmacyModal" data-bs-toggle="modal" href="#">Add
                Pharmacy</a>
        </div>

        <div class="table-responsive mt-4">
            <table class="table-striped table" id="Table">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Owner</th>
                        <th>Package</th>
                        <th>Remain</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pharmacies as $pharmacy)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pharmacy->name }}</td>
                            <td>{{ $pharmacy->location }}</td>
                            <td>{{ $pharmacy->owner->name }}</td>
                            <td>{{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->name : "No Package" }}
                            </td>
                            <td><small class="text-danger smaller countdown"
                                    id="countdown{{ $pharmacy->id }}">{{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->end_date : "No package" }}</small>
                            </td>
                            <td>{{ $pharmacy->status }}</td>
                            <td>
                                <a class="btn btn-primary btn-sm" data-bs-target="#viewPharmacy{{ $pharmacy->id }}"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-eye"></i></a>
                                {{-- modal to show  pharmacy details --}}
                                <div aria-hidden="true" aria-labelledby="viewPharmacy{{ $pharmacy->id }}Label"
                                    class="modal fade" id="viewPharmacy{{ $pharmacy->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-primary"
                                                    id="viewPharmacy{{ $pharmacy->id }}Label">{{ $pharmacy->name }}</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-6 border-right border-danger">
                                                        <h2 class="h5 text-primary">Pharmacy Details</h2>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Name</h5>
                                                            <p class="text-secondary">{{ $pharmacy->name }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Location</h5>
                                                            <p class="text-secondary">{{ $pharmacy->location }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Status</h5>
                                                            <p class="text-secondary">{{ $pharmacy->status }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <h2 class="h5 text-primary">Owner Details</h2>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Name</h5>
                                                            <p class="text-secondary">{{ $pharmacy->owner->name }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Email</h5>
                                                            <p class="text-secondary">{{ $pharmacy->owner->email }}</p>
                                                        </div>
                                                        <div class="mb-3">
                                                            <h5 class="text-primary">Phone</h5>
                                                            <p class="text-secondary">{{ $pharmacy->owner->phone }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row border-top border-danger">
                                                    <h2 class="h5 text-primary mt-3">Package Details</h2>
                                                    <div class="d-flex">
                                                        @if ($pharmacy->owner->ownerCurrentContract)
                                                            <div class="row">
                                                                <div class="mb-3">
                                                                    <h5 class="text-primary">Name</h5>
                                                                    <p class="text-secondary">
                                                                        {{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->name : "No Package" }}
                                                                    </p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <h5 class="text-primary">Duration</h5>
                                                                    <p class="text-secondary">
                                                                        {{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->duration : "No Package" }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="mb-3">
                                                                    <h5 class="text-primary">Price</h5>
                                                                    <p class="text-secondary">
                                                                        {{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->price : "No Package" }}
                                                                    </p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <h5 class="text-primary">Start Date</h5>
                                                                    <p class="text-secondary">
                                                                        {{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->start_date : "No Package" }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <h5 class="text-primary">End Date</h5>
                                                                <p class="text-secondary">
                                                                    {{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->end_date : "No Package" }}
                                                                </p>
                                                            </div>
                                                        @else
                                                            <p class="text-secondary">No Package</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal"
                                                    type="button">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a class="btn btn-secondary btn-sm" data-bs-target="#editPharmacy{{ $pharmacy->id }}"
                                    data-bs-toggle="modal" href="#"><i class="bi bi-pencil"></i></a>
                                {{-- Modal to edit pharmacy details --}}
                                <div aria-hidden="true" aria-labelledby="editPharmacy{{ $pharmacy->id }}Label"
                                    class="modal fade" id="editPharmacy{{ $pharmacy->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-primary"
                                                    id="editPharmacy{{ $pharmacy->id }}Label">Edit Pharmacy</h5>
                                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"
                                                    type="button"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form
                                                    action="{{ route("agent.pharmacies.update", ["id" => $pharmacy->id, "action" => "update"]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method("PUT")
                                                    <x-validation-errors class="mb-4" />
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <h2 class="h5 text-primary">Pharmacy Details</h2>
                                                            <div class="mb-3">
                                                                <x-label class="form-label" for="pharmacy_name"
                                                                    value="Name" />
                                                                <x-input :value="old("pharmacy_name") ?? $pharmacy->name" class="form-control rounded"
                                                                    id="pharmacy_name" name="pharmacy_name"
                                                                    placeholder="Pill Pharmacy" required type="text" />
                                                            </div>
                                                            <div class="mb-3">
                                                                <x-label class="form-label" for="location"
                                                                    value="Location" />
                                                                <x-input :value="old("location") ?? $pharmacy->location" class="form-control rounded"
                                                                    id="location" name="location" placeholder="Morogoro"
                                                                    type="text" />
                                                            </div>
                                                            <div class="mb-3">
                                                                <x-label class="form-label" for="status"
                                                                    value="Status" />
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <x-label class="form-label" for="agent_extra_charge"
                                                                    value="Agent Extra Charge (Top-up)" />
                                                                <x-input :value="old("agent_extra_charge") ??
                                                                    $pharmacy->agent_extra_charge" class="form-control rounded"
                                                                    id="agent_extra_charge" name="agent_extra_charge"
                                                                    placeholder="0.00" step="0.01" type="number" />
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <h2 class="h5 text-primary">Owner Details</h2>
                                                            <div class="mb-3">
                                                                <h5 class="text-primary">Name</h5>
                                                                <p class="text-secondary">{{ $pharmacy->owner->name }}</p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <h5 class="text-primary">Email</h5>
                                                                <p class="text-secondary">{{ $pharmacy->owner->email }}
                                                                </p>
                                                            </div>
                                                            <div class="mb-3">
                                                                <h5 class="text-primary">Phone</h5>
                                                                <p class="text-secondary">{{ $pharmacy->owner->phone }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row border-top border-danger">
                                                        <h2 class="h5 text-primary mt-3">Package Details</h2>
                                                        <div class="d-flex">
                                                            @if ($pharmacy->owner->ownerCurrentContract)
                                                                <div class="row">
                                                                    <div class="mb-3">
                                                                        <h5 class="text-primary">Name</h5>
                                                                        <p class="text-secondary">
                                                                            {{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->name : "No Package" }}
                                                                        </p>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <h5 class="text-primary">Duration</h5>
                                                                        <p class="text-secondary">
                                                                            {{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->duration : "No Package" }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="mb-3">
                                                                        <h5 class="text-primary">Price</h5>
                                                                        <p class="text-secondary">
                                                                            {{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->package->price : "No Package" }}
                                                                        </p>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <h5 class="text-primary">Start Date</h5>
                                                                        <p class="text-secondary">
                                                                            {{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->start_date : "No Package" }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <h5 class="text-primary">End Date</h5>
                                                                    <p class="text-secondary">
                                                                        {{ $pharmacy->owner->ownerCurrentContract ? $pharmacy->owner->ownerCurrentContract->end_date : "No Package" }}
                                                                    </p>
                                                                </div>
                                                            @else
                                                                <p class="text-secondary">No Package</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal"
                                                            type="button">Close</button>
                                                        <button class="btn btn-primary" type="submit">Save
                                                            changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form
                                    action="{{ route("agent.pharmacies.destroy", ["id" => $pharmacy->id, "action" => "delete"]) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method("DELETE")
                                    <button {{-- onclick="return confirm('Do you want to delete this pharmacy?')" --}} class="btn btn-danger btn-sm" type="submit"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal to create a new pharmacy -->
    <div aria-hidden="true" aria-labelledby="addPharmacyModalLabel" class="modal fade" id="addPharmacyModal"
        tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="addPharmacyModalLabel">Add a new pharmacy</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route("agent.pharmacies.store", ["action" => "create"]) }}" method="POST">
                        @csrf
                        <x-validation-errors class="mb-4" />
                        <div class="row">
                            <div class="col-6">
                                <h2 class="h5 text-primary">Pharmacy Details</h2>
                                <div class="mb-3">
                                    <x-label class="form-label" for="pharmacy_name" value="Name" />
                                    <x-input :value="old("pharmacy_name")" class="form-control rounded" id="pharmacy_name"
                                        name="pharmacy_name" placeholder="Pill Pharmacy" required type="text" />
                                </div>
                                <div class="mb-3">
                                    <x-label class="form-label" for="location" value="Location" />
                                    <x-input :value="old("location")" class="form-control rounded" id="location"
                                        name="location" placeholder="Morogoro" type="text" />
                                </div>
                                <div class="mb-3">
                                    <x-label class="form-label" for="status" value="Status" />
                                    <select class="form-select rounded" id="status" name="status" required>
                                        <option {{ old("status") == "active" ? "selected" : "" }} value="active">Active
                                        </option>
                                        <option {{ old("status") == "inactive" ? "selected" : "" }} value="inactive">
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <h2 class="h5 text-primary">Owner Details</h2>
                                <div>
                                    <x-label for="name" value="{{ __("Name") }}" />
                                    <x-input :value="old("name")" autocomplete="name" autofocus class="mt-1 block w-full"
                                        id="name" name="name" placeholder="Pill Point" required
                                        type="text" />
                                </div>

                                <div class="mt-4">
                                    <x-label for="email" value="{{ __("Email") }}" />
                                    <x-input :value="old("email")" autocomplete="username" class="mt-1 block w-full"
                                        id="email" name="email" placeholder="info@pillpoint.com" required
                                        type="email" />
                                </div>

                                <div class="mt-4">
                                    <x-label for="phone_number" value="{{ __("Phone Number") }}" />
                                    <x-input :value="old("phone_number")" autocomplete="phone_number" class="mt-1 block w-full"
                                        id="phone_number" name="phone_number" placeholder="0742177328" required
                                        type="tel" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-between">
                            <button class="btn btn-primary" type="submit">Submit</button>
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            console.log('Countdown script running.');

            $('.countdown').each(function() {
                var countdownElement = $(this);
                var endDateString = countdownElement.text().trim();

                if (endDateString === 'No package') {
                    countdownElement.text('No package');
                    return;
                }

                var countDownDate = new Date(endDateString).getTime();

                if (isNaN(countDownDate)) {
                    console.error('Invalid date:', endDateString);
                    countdownElement.text('Invalid date');
                    return;
                }

                var interval = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = countDownDate - now;

                    if (distance < 0) {
                        clearInterval(interval);
                        countdownElement.text('EXPIRED').addClass('text-danger fw-bold');
                        return;
                    }

                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    countdownElement.text(`${days}d ${hours}h ${minutes}m ${seconds}s`);
                }, 1000);
            });
        });
    </script>

    {{-- // if there are error inputs returned from the server, show the modal --}}
    @if ($errors->any())
        <script>
            $(document).ready(function() {
                $('#addPharmacyModal').modal('show');
            });
        </script>
    @endif
@endsection
