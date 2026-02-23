@extends('agent.app')

@section('content')

    {{-- check if user is agent --}}
    @hasrole('Agent')
        <h1 class="text-primary h4 mt-4 text-center">Registration Pannel</h1>
        <!-- Progress Bar -->
        @if ($me && in_array($me->registration_status, ['step_1', 'incomplete', 'step_3']))
            <div class="container mb-4">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="progress" style="height: 10px; border-radius: 5px;">
                            <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="0"
                                class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="progressBar" role="progressbar"
                                style="width: 0%;">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 small text-muted">
                            <span>Step 1: Basic Info</span>
                            <span>Step 2: Verification</span>
                            <span>Step 3: Agreement</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @php
            $compulsoryDocuments = ['Passport Copy', 'Driving License', 'National ID'];
            $optionalDocuments = array_merge(['Utility Bill', 'Bank Statement', 'Other'], $compulsoryDocuments);
        @endphp

        {{-- if is in step_1 --}}
        @if ($me == null || $me->registration_status == 'step_1')
            <div class="container mt-2">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="text-center"> Step 1</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('agent.completeRegistration') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <input name="action" type="hidden" value="store">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="country">Nationality</label>
                                    <input class="form-control required-field" id="country" name="country"
                                        placeholder="Tanzania" required type="text"
                                        value="{{ old('country', $agent->country ?? '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="NIN">National Identification Number (NIN)</label>
                                    <input class="form-control required-field" id="NIN" name="NIN"
                                        placeholder="19981107303750000227" required type="text"
                                        value="{{ old('NIN', $agent->NIN ?? '') }}">
                                </div>

                                <!-- Compulsory Document -->
                                <div class="col-md-6">
                                    <label class="form-label" for="document_attachment_1_name">Compulsory Document</label>
                                    <select class="required-field form-select" id="document_attachment_1_name"
                                        name="document_attachment_1_name" required>
                                        <option disabled selected>Select Document</option>
                                        @foreach ($compulsoryDocuments as $option)
                                            <option
                                                {{ old('document_attachment_1_name', $agent->document_attachment_1_name ?? '') == $option ? 'selected' : '' }}
                                                value="{{ $option }}">
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="document_attachment_1">Upload Compulsory Document</label>
                                    <input class="form-control required-field" id="document_attachment_1"
                                        name="document_attachment_1" required type="file">
                                </div>

                                <!-- Additional Documents Container -->
                                <div class="row" id="optionalDocumentsContainer"></div>

                                <div class="col-md-6">
                                    <!-- Add More Documents Button -->
                                    <div class="col-md-12# mt-3 text-start">
                                        <button class="btn btn-info" id="addMoreDocuments" type="button">+ Add
                                            Documents</button>
                                    </div>
                                    <label class="form-label" for="agent_code">Agent Code: <span
                                            class="text-primary">{{ isset($agent_code) ? $agent_code : 'not assigned' }}</span>
                                    </label>
                                    <br>
                                    <label class="form-label" for="agent_code">Verification Status: <span
                                            class="text-primary">{{ isset($agent_code) ? $agent_code : 'not verified' }}</span>
                                    </label>
                                </div>

                                <div class="col-md-12">
                                    <h5 class="mt-4 mb-3 border-bottom pb-2 text-primary"><i class="bi bi-geo-alt"></i> Your Address Location</h5>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label" for="region">Region</label>
                                            <select class="form-select required-field" id="region" name="region" required>
                                                <option disabled selected value="">Select Region</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="district">District</label>
                                            <select class="form-select required-field" disabled id="district" name="district" required>
                                                <option disabled selected value="">Select District</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="ward">Ward</label>
                                            <select class="form-select required-field" disabled id="ward" name="ward" required>
                                                <option disabled selected value="">Select Ward</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label" for="street">Street</label>
                                            <select class="form-select required-field" disabled id="street" name="street" required>
                                                <option disabled selected value="">Select Street</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="address_details">Full Address Details (House No, Building etc)</label>
                                            <textarea class="form-control required-field" id="address_details" name="address_details"
                                                placeholder="e.g. Chief Kingalu market, House A115." required rows="2">{{ old('address_details', $agent->address ?? '') }}</textarea>
                                            <input id="combined_address" name="address" type="hidden">
                                        </div>
                                    </div>
                                </div>

                                {{-- checkbox to accept that the endered documents are correct and accurate and accept the terms and conditions and is ready to be 
                        aswerable before the rule of law  for any action taken by the government or any other institution concerning the provided information --}}
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input required-field" id="verifyInformations"
                                            name="verifyInformations" required type="checkbox">
                                        <label class="form-check-label" for="verifyInformations">
                                            <small class="smallest text-danger">
                                                I confirm that the information I have provided are mine, are correct and are
                                                accurate.
                                            </small>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input required-field" id="ruleOfLaw" name="ruleOfLaw" required
                                            type="checkbox">
                                        <label class="form-check-label" for="ruleOfLaw">
                                            <small class="smallest text-danger">
                                                I am ready to be answerable before the rule of law for any action taken by
                                                the
                                                government or any other institution concerning the provided
                                                information.
                                            </small>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input required-field" id="acceptTerms" name="acceptTerms"
                                            required type="checkbox">
                                        <label class="form-check-label" for="acceptTerms">
                                            <small class="smallest text-danger">
                                                I accept the terms conditions.
                                            </small>
                                        </label>
                                    </div>
                                    <button class="btn btn-success w-30 mt-4" disabled="true" type="submit">Save
                                        Details</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @elseif($me->registration_status == 'step_3')
            <center>
                <div class="card mx-auto shadow-sm" style="max-width: 800px;">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Step 3: Signed Agreement</h4>
                    </div>
                    <div class="card-body text-start p-4">
                        <div class="row align-items-center">
                            <div class="col-md-5 text-center">
                                <h6 class="fw-bold mb-3">1. Download Agreement</h6>
                                <a class="btn btn-outline-primary btn-lg mb-2" href="{{ asset('storage/agreement_forms/agreement_form.pdf') }}" target="_blank">
                                    <i class="bi bi-download"></i> Download PDF
                                </a>
                                <p class="small text-muted mt-2">Please print, sign, and scan the agreement before proceeding to upload.</p>
                            </div>

                            <div class="col-md-1 d-none d-md-block">
                                <div class="vr h-100 mx-auto"></div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">2. Upload Signed Copy</h6>
                                <form action="{{ route('agent.completeRegistration') }}" enctype="multipart/form-data" id="uploadSignedAgreement" method="POST">
                                    @csrf
                                    <input name="action" type="hidden" value="upload_agreement_form">
                                    <div class="mb-3">
                                        <input class="form-control required-field" id="signed_agreement_form" name="signed_agreement_form" required type="file">
                                    </div>
                                    <button class="btn btn-success w-100 py-2 mt-2" disabled type="submit">
                                        <i class="bi bi-cloud-upload"></i> Complete Registration
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </center>
        @elseif($me->registration_status == 'incomplete')
            <center>
                <div class="card mx-auto shadow-sm border-danger" style="max-width: 600px;">
                    <div class="card-header bg-danger text-white py-3">
                        <h4 class="mb-0"><i class="bi bi-x-circle"></i> Application Rejected</h4>
                    </div>
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold text-danger">Verification Failed</h5>
                        <p class="text-muted mt-3">We regret to inform you that your application has been rejected due to incorrect or incomplete information provided. Please review your details and try again.</p>
                        
                        <div class="mt-4">
                            <a class="btn btn-primary btn-lg px-5" href="{{ route('agent.completeRegistration', ['action' => 'restart_steps']) }}">
                                <i class="bi bi-arrow-repeat"></i> Re-Apply Now
                            </a>
                        </div>
                    </div>
                </div>
            </center>
        @elseif($me->registration_status == 'step_2')
            <center>
                <div class="card mx-auto shadow-sm" style="max-width: 600px;">
                    <div class="card-header bg-warning text-dark py-3">
                        <h4 class="mb-0"><i class="bi bi-hourglass-split"></i> Step 2: Verification Pending</h4>
                    </div>
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <h5>Processing Your Application</h5>
                        <p class="text-muted mt-3">Please wait while our team verifies your documents. This process typically takes between 2 to 24 hours.</p>
                        
                        @if ($me->status == 'verified')
                            <div class="alert alert-success mt-4">
                                <h5 class="alert-heading text-success mb-2"><i class="bi bi-check-circle-fill"></i> Account Verified!</h5>
                                <p class="mb-3">Your documents have been successfully verified. Click the button below to proceed to the next step.</p>
                                <a class="btn btn-success btn-lg px-5" href="{{ route('agent.completeRegistration', ['action' => 'next_step']) }}">
                                    Proceed to Agreement <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @else
                            <div class="mt-4">
                                <a class="btn btn-outline-danger" href="{{ route('agent.completeRegistration', ['action' => 'restart_steps']) }}" 
                                   onclick="return confirm('Are you sure you want to cancel and edit your details?')">
                                    <i class="bi bi-pencil-square"></i> Edit Details
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </center>
        @elseif($me->registration_status == 'complete')
            {{-- display Agent information with code and with a button to download the code --}}
            <center>
                <div class="mx-2">
                    <div class="card">
                        <h3 class="card-body row gap-3">
                            <span class="col-md-4 text-success fw-bold">Registered successfully!</span>

                            {{-- verification status --}}
                            <span class= "col-md-3">Verification Status: <span
                                    class="text-success fw-bold">{{ $me->status }}</span>
                            </span>

                            {{-- agent code --}}
                            <span class="col-md-4">Your Agent Code: <span
                                    class="text-{{ $me->agent_code == null ? 'warning' : 'success fw-bold' }}">{{ $me->agent_code ?? 'Waiting...' }}</span>
                        </h3>
                    </div>
                </div>
            </center>
        @endif

        {{-- ALWAYS DISPLAY CONTACT INFORMATION AT THE BOTTOM OF THE PAGE --}}
        <footer class="bg-light text-lg-end text-center" style="position: relative; bottom: 0; width: 100%;">
            <div class="card mx-2">
                <div class="card-body text-start">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center">
                                Please contact us if you have any questions or concerns.
                                <br>
                                <strong>Email:</strong>
                                <a class="text-primary"
                                    href="mailto:{{ App\Models\User::where('role', 'super')->first()->email }}">{{ App\Models\User::where('role', 'super')->first()->email }}</a>

                                <strong>Phone:</strong> <a class="text-success"
                                    href="tel:{{ App\Models\User::where('role', 'super')->first()->phone }}">{{ App\Models\User::where('role', 'super')->first()->phone }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <script>
            $(document).ready(function() {
                let maxOptionalDocs = 2;
                let currentDocs = 0;

                // Initialize Select2 for address fields
                $('#region, #district, #ward, #street').select2({
                    width: '100%',
                });

                // Load initial regions
                $.get("{{ route('address.regions') }}", function(data) {
                    let regionSelect = $('#region');
                    data.forEach(function(region) {
                        regionSelect.append(`<option value="${region}">${region}</option>`);
                    });
                    // Trigger change for select2 if it's already initialized
                    if (regionSelect.data('select2')) {
                        regionSelect.trigger('change');
                    }
                });

                // Cascading logic
                $('#region').on('change', function() {
                    let region = $(this).val();
                    let districtSelect = $('#district');
                    districtSelect.prop('disabled', false).html('<option disabled selected value="">Select District</option>');
                    $('#ward').prop('disabled', true).html('<option disabled selected value="">Select Ward</option>');
                    $('#street').prop('disabled', true).html('<option disabled selected value="">Select Street</option>');
                    
                    $.get(`/address/districts/${region}`, function(data) {
                        data.forEach(function(district) {
                            districtSelect.append(`<option value="${district}">${district}</option>`);
                        });
                        if (districtSelect.data('select2')) {
                            districtSelect.trigger('change');
                        }
                        updateProgress();
                    });
                });

                $('#district').on('change', function() {
                    let region = $('#region').val();
                    let district = $(this).val();
                    let wardSelect = $('#ward');
                    wardSelect.prop('disabled', false).html('<option disabled selected value="">Select Ward</option>');
                    $('#street').prop('disabled', true).html('<option disabled selected value="">Select Street</option>');
                    
                    $.get(`/address/wards/${region}/${district}`, function(data) {
                        data.forEach(function(ward) {
                            wardSelect.append(`<option value="${ward}">${ward}</option>`);
                        });
                        if (wardSelect.data('select2')) {
                            wardSelect.trigger('change');
                        }
                        updateProgress();
                    });
                });

                $('#ward').on('change', function() {
                    let region = $('#region').val();
                    let district = $('#district').val();
                    let ward = $(this).val();
                    let streetSelect = $('#street');
                    streetSelect.prop('disabled', false).html('<option disabled selected value="">Select Street</option>');
                    
                    $.get(`/address/streets/${region}/${district}/${ward}`, function(data) {
                        data.forEach(function(street) {
                            streetSelect.append(`<option value="${street}">${street}</option>`);
                        });
                        if (streetSelect.data('select2')) {
                            streetSelect.trigger('change');
                        }
                        updateProgress();
                    });
                });

                $('#street').on('change', function() {
                    updateProgress();
                });

                function updateProgress() {
                    let totalRequired = $(".required-field").length;
                    let filledFields = $(".required-field").filter(function() {
                        if ($(this).attr("type") === "checkbox") {
                            return $(this).is(":checked");
                        }
                        if ($(this).is("select")) {
                            return $(this).val() !== null && $(this).val() !== "";
                        }
                        return $(this).val().trim() !== "";
                    }).length;

                    let percentage = Math.round((filledFields / totalRequired) * 100);
                    percentage = Math.min(percentage, 100);

                    $("#progressBar")
                        .css("width", percentage + "%")
                        .attr("aria-valuenow", percentage)
                        .text(percentage + "%");
                    
                    if (percentage === 100) {
                        $("button[type='submit']").prop("disabled", false);
                    } else {
                        $("button[type='submit']").prop("disabled", true);
                    }
                }

                // Track input changes
                $(document).on("input change", ".required-field, .form-check-input", function() {
                    updateProgress();
                });

                // Add More Documents
                $("#addMoreDocuments").on("click", function() {
                    if (currentDocs < maxOptionalDocs) {
                        let docIndex = currentDocs + 2;
                        let docHtml = `
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Optional Document ${currentDocs + 1} Name</label>
                            <select class="form-select" name="document_attachment_${docIndex}_name">
                                <option selected disabled value="">Select Document</option>
                                @foreach ($optionalDocuments as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Upload Optional Document ${currentDocs + 1}</label>
                            <input type="file" class="form-control" name="document_attachment_${docIndex}">
                        </div>`;
                        $("#optionalDocumentsContainer").append(docHtml);
                        currentDocs++;
                        if (currentDocs >= maxOptionalDocs) {
                            $(this).prop("disabled", true).text("Max Documents Reached");
                        }
                    }
                });

                $("form").on("submit", function(e) {
                    if (this.id === "uploadSignedAgreement") return;
                    
                    // Combine address components
                    let region = $('#region').val();
                    let district = $('#district').val();
                    let ward = $('#ward').val();
                    let street = $('#street').val();
                    let details = $('#address_details').val();
                    let country = $('#country').val();
                    
                    let fullAddress = `${country}, ${region}, ${district}, ${ward}, ${street}, ${details}`;
                    $('#combined_address').val(fullAddress);
                });

                updateProgress();
            });
        </script>
    @endhasrole
    {{-- check if user is admin --}}
    @hasrole('Superadmin')
        <div class="container mt-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0 text-primary fw-bold"><i class="bi bi-people"></i> Agent Applications</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="agentTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Agent Information</th>
                                    <th>Address & Location</th>
                                    <th>Documents</th>
                                    <th>Progress Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agents as $agent)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $agent->name }}</div>
                                            <div class="small text-muted"><i class="bi bi-envelope"></i> {{ $agent->email }}</div>
                                            <div class="small text-muted"><i class="bi bi-telephone"></i> {{ $agent->phone }}</div>
                                            @if($agent->isAgent && $agent->isAgent->agent_code)
                                                <span class="badge bg-info mt-1">Code: {{ $agent->isAgent->agent_code }}</span>
                                            @endif
                                        </td>
                                        <td class="small">
                                            @if($agent->isAgent)
                                                {{ $agent->isAgent->address }}
                                                <div class="text-muted mt-1 small"><i class="bi bi-geo"></i> {{ $agent->isAgent->country }}</div>
                                            @else
                                                <span class="text-muted italic">No address provided</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($agent->isAgent)
                                                <div class="d-flex flex-column gap-1">
                                                    @if($agent->isAgent->document_attachment_1)
                                                        <a href="{{ asset('storage/' . $agent->isAgent->document_attachment_1) }}" target="_blank" class="btn btn-sm btn-outline-primary text-start">
                                                            <i class="bi bi-file-earmark-pdf"></i> {{ Str::limit($agent->isAgent->document_attachment_1_name, 15) }}
                                                        </a>
                                                    @endif
                                                    @if($agent->isAgent->signed_agreement_form)
                                                        <a href="{{ asset('storage/' . $agent->isAgent->signed_agreement_form) }}" target="_blank" class="btn btn-sm btn-outline-success text-start">
                                                            <i class="bi bi-file-check"></i> Agreement Form
                                                        </a>
                                                    @endif
                                                    @if($agent->isAgent->document_attachment_2)
                                                        <a href="{{ asset('storage/' . $agent->isAgent->document_attachment_2) }}" target="_blank" class="btn btn-sm btn-outline-secondary text-start">
                                                            <i class="bi bi-file-earmark"></i> {{ Str::limit($agent->isAgent->document_attachment_2_name, 15) }}
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="badge bg-light text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($agent->isAgent)
                                                @php
                                                    $statusClass = [
                                                        'step_1' => 'bg-secondary',
                                                        'step_2' => 'bg-warning text-dark',
                                                        'step_3' => 'bg-info',
                                                        'complete' => 'bg-success',
                                                        'incomplete' => 'bg-danger'
                                                    ][$agent->isAgent->registration_status] ?? 'bg-secondary';
                                                    
                                                    $statusLabel = [
                                                        'step_1' => 'Incomplete Form',
                                                        'step_2' => 'Pending Verification',
                                                        'step_3' => 'Pending Agreement',
                                                        'complete' => 'Active Agent',
                                                        'incomplete' => 'Rejected'
                                                    ][$agent->isAgent->registration_status] ?? $agent->isAgent->registration_status;
                                                @endphp
                                                <span class="badge {{ $statusClass }} px-3 py-2">{{ ucfirst($statusLabel) }}</span>
                                                <div class="mt-1 small">
                                                    @if($agent->isAgent->status == 'verified')
                                                        <span class="text-success"><i class="bi bi-patch-check-fill"></i> Documents Verified</span>
                                                    @else
                                                        <span class="text-muted"><i class="bi bi-clock"></i> Not Verified</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="badge bg-light text-muted">No Record</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($agent->isAgent)
                                                <div class="btn-group">
                                                    @if ($agent->isAgent->registration_status == 'step_2' && $agent->isAgent->status != 'verified')
                                                        <a class="btn btn-sm btn-success" href="{{ route('agent.completeRegistration', ['action' => 'verify', 'set_status' => 'accepted', 'id' => $agent->isAgent->id]) }}" title="Approve Verification">
                                                            <i class="bi bi-check-lg"></i>
                                                        </a>
                                                        <a class="btn btn-sm btn-danger" href="{{ route('agent.completeRegistration', ['action' => 'verify', 'set_status' => 'rejected', 'id' => $agent->isAgent->id]) }}" title="Reject Application">
                                                            <i class="bi bi-x-lg"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if ($agent->isAgent->registration_status == 'complete' && !$agent->isAgent->agent_code)
                                                        <a class="btn btn-sm btn-primary" href="{{ route('agent.completeRegistration', ['action' => 'generateAgentCode', 'id' => $agent->isAgent->id]) }}" title="Generate Agent Code">
                                                            <i class="bi bi-gear-fill"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted small">No actions</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#agentTable').DataTable({
                    "lengthMenu": [10, 25, 50, "All"],
                    "pageLength": 10,
                    "order": [[3, "desc"]],
                    "responsive": true
                });
            });
        </script>
    @endhasrole
@endsection
