@extends("agent.app")

@section("content")

    {{-- check if user is agent --}}
    @hasrole("Agent")
        <h1 class="text-primary h4 mt-4 text-center">Registration Pannel</h1>
        <!-- Progress Bar -->
        @if (in_array($me->registration_status, ["step_1", "incomplete", "step_3"]))
            <div class="progress mx-auto mb-2" style="width: 70%;">
                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="0"
                    class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="progressBar" role="progressbar"
                    style="width: 0%;">
                    0%
                </div>
            </div>
        @endif

        @php
            $compulsoryDocuments = ["Passport Copy", "Driving License", "National ID"];
            $optionalDocuments = array_merge(["Utility Bill", "Bank Statement", "Other"], $compulsoryDocuments);
        @endphp

        {{-- if is in step_1 --}}
        @if ($me == null || $me->registration_status == "step_1")
            <div class="container mt-2">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="text-center"> Step 1</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route("agent.completeRegistration") }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <input name="action" type="hidden" value="store">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="country">Nationality</label>
                                    <input class="form-control required-field" id="country" name="country"
                                        placeholder="Tanzania" required type="text"
                                        value="{{ old("country", $agent->country ?? "") }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="NIN">National Identification Number (NIN)</label>
                                    <input class="form-control required-field" id="NIN" name="NIN"
                                        placeholder="19981107303750000227" required type="text"
                                        value="{{ old("NIN", $agent->NIN ?? "") }}">
                                </div>

                                <!-- Compulsory Document -->
                                <div class="col-md-6">
                                    <label class="form-label" for="document_attachment_1_name">Compulsory Document</label>
                                    <select class="required-field form-select" id="document_attachment_1_name"
                                        name="document_attachment_1_name" required>
                                        <option disabled selected>Select Document</option>
                                        @foreach ($compulsoryDocuments as $option)
                                            <option
                                                {{ old("document_attachment_1_name", $agent->document_attachment_1_name ?? "") == $option ? "selected" : "" }}
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
                                            class="text-primary">{{ isset($agent_code) ? $agent_code : "not assigned" }}</span>
                                    </label>
                                    <br>
                                    <label class="form-label" for="agent_code">Verification Status: <span
                                            class="text-primary">{{ isset($agent_code) ? $agent_code : "not verified" }}</span>
                                    </label>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="address">Address</label>
                                    <textarea class="form-control required-field" id="address" name="address"
                                        placeholder="Tanzania, Morogoro, Morogoro municipal, Chief Kingalu market, A115." rows="2">{{ old("address", $agent->address ?? "") }}</textarea>
                                    <small class="text-danger d-none" id="addressError">Address must follow this format:
                                        Country,
                                        Region, District, Ward, Street, House number.</small>
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
        @elseif($me->registration_status == "step_3")
            {{-- Show a place to download aggreement document and a place to upload the signed agreement document --}}
            {{-- <div class="container m-3 w-50 row"> --}}
            <center>
                <div class="card mx-2">
                    <div class="card-header">
                        <h3>Step 2: Upload Agreement</h3>
                    </div>
                    <div class="card-body text-start">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label" for="agreement">Agreement</label> <br>
                                <a class="btn btn-primary" href="{{ asset("storage/agreement_forms/agreement_form.pdf") }}"
                                    target="_blank">Download Agreement</a>
                                <br>
                                <small class="text-danger">Please download the agreement and sign it before uploading
                                    it.</small>
                                <br>
                            </div>

                            {{-- draw vertical line --}}
                            <div class="col-md-1 border-start border-primary"></div>

                            <div class="col-md-6">
                                <form action="{{ route("agent.completeRegistration") }}" enctype="multipart/form-data"
                                    id="uploadSignedAgreement" method="POST">
                                    @csrf
                                    <input hidden name="action" type="text" value="upload_agreement_form">
                                    {{-- agent is --}}
                                    <input hidden name="agent_id" type="text" value="{{ $me->id }}">
                                    <div class="col-md-12 row">
                                        <div class="col-md-8">
                                            <label class="form-label" for="signed_agreement_form">Signed Agreement
                                                Form</label>
                                            <input class="form-control required-field" id="signed_agreement_form"
                                                name="signed_agreement_form" required type="file">
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <label for="form-label"> </label>
                                            <button class="btn btn-success mt-2" disabled="true"
                                                type="submit">Upload</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </center>
        @elseif($me->registration_status == "incomplete")
            {{-- display Agent informations in a table --}}
            <center>
                <div class="card mx-2">
                    <div class="card-header bg-danger w-100# text-light">
                        <h3 class="">Step 3: Verification Process is in Progress</h3>
                    </div>
                    <div class="card-body text-start">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-center">
                                    Your registration has been rejected!
                                </p>
                                {{-- check if not verified yet --}}
                                @if ($me->status == "unverified")
                                    <div class="my-4 text-center">
                                        <div class="text-success" role="status">
                                            verification status: 100%
                                        </div> <br>
                                        <span class="text-danger"><i class="bi bi-sad"> Rejected! </i></span> <br>
                                        <span class="text-danger"><i class="bi bi-sad"> Reason: </i> Incorect Data</span>
                                    </div>
                                    {{-- set route to go to next step --}}
                                    <div class="my-4 text-center">
                                        <a class="btn btn-primary"
                                            href="{{ route("agent.completeRegistration", ["action" => "restart_steps"]) }}">Re-Apply</a>
                                    </div>
                                @endif
                                <p class="text-warning text-center">
                                    We apologize for the inconvenience.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </center>
        @elseif($me->registration_status == "step_2")
            {{-- Display a message and a loader to ask client to wait while her documents are being verified --}}
            <center>
                <div class="card mx-2">
                    <div class="card-header bg-success w-100# text-light">
                        <h3 class="">Step 3: Verification Process is in Progress</h3>
                    </div>
                    <div class="card-body text-start">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-center">
                                    Please wait while we verify your documents.
                                    This may take a few minutes to 24 hours.
                                </p>
                                {{-- check if not verified yet --}}
                                @if ($me->status == "unverified")
                                    <div class="my-4 text-center">
                                        <div class="spinner-border text-primary" role="status">
                                        </div> <br>
                                        <span class="visually-hidden#">Verifying...</span>
                                    </div>
                                    {{-- set route to go to next step --}}
                                    <div class="my-4 text-center">
                                        <a class="btn btn-danger"
                                            href="{{ route("agent.completeRegistration", ["action" => "restart_steps"]) }}"
                                            onclick="confirm('Are you sure you want to cancel?')">Cancel to Process</a>
                                    </div>
                                @endif
                                @if ($me->status == "verified")
                                    {{-- set route to go to next step --}}
                                    <div class="my-4 text-center">
                                        <h1 class="h5 text-success"><i class="bi bi-person-check"> Verified!</i></h1>
                                        <a class="btn btn-primary"
                                            href="{{ route("agent.completeRegistration", ["action" => "next_step"]) }}">Next</a>
                                    </div>
                                @endif
                                <p class="text-success text-center">
                                    Please You can close this page and come back later.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </center>
        @elseif($me->registration_status == "complete")
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
                                    class="text-{{ $me->agent_code == null ? "warning" : "success fw-bold" }}">{{ $me->agent_code ?? "Waiting..." }}</span>
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
                                    href="mailto:{{ App\Models\User::where("role", "super")->first()->email }}">{{ App\Models\User::where("role", "super")->first()->email }}</a>

                                <strong>Phone:</strong> <a class="text-success"
                                    href="tel:{{ App\Models\User::where("role", "super")->first()->phone }}">{{ App\Models\User::where("role", "super")->first()->phone }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <script>
            $(document).ready(function() {
                let maxOptionalDocs = 2; // Maximum number of optional documents
                let currentDocs = 0; // Track added documents
                let totalRequiredFields = $(".required-field").length;

                function updateProgress() {
                    let filledFields = $(".required-field").filter(function() {
                        // Check if the field is the address field
                        if ($(this).attr("id") === "address") {
                            return validateAddress();
                        }
                        // Check if the field is a checkbox
                        if ($(this).attr("type") === "checkbox") {
                            return $(this).is(":checked");
                        }
                        return $(this).val() !== "";
                    }).length;

                    let percentage = Math.round((filledFields / totalRequiredFields) * 100);
                    percentage = percentage > 100 ? 100 : percentage;

                    if (percentage == 100) {
                        // Enable all submit buttons
                        $("button[type='submit']").prop("disabled", false);
                    } else {
                        // Disable all submit buttons
                        $("button[type='submit']").prop("disabled", true);
                    }

                    $("#progressBar").css("width", percentage + "%").attr("aria-valuenow", percentage).text(percentage +
                        "%");
                }

                // Track input changes
                $(".required-field").on("input change", function() {
                    updateProgress();
                });

                // Track checkbox changes
                $(".form-check-input").on("change", function() {
                    updateProgress();
                });

                // Add More Documents functionality
                $("#addMoreDocuments").on("click", function() {
                    if (currentDocs < maxOptionalDocs) {
                        let docIndex = currentDocs + 2; // Start from document_attachment_2

                        let docHtml = `
                        <div class="col-md-6 mt-4">
                            <label for="document_attachment_${docIndex}_name" class="form-label">Optional Document ${currentDocs + 1} Name</label>
                            <select class="form-select optional-document" id="document_attachment_${docIndex}_name" name="document_attachment_${docIndex}_name">
                                <option selected disabled value="">Select Document</option>
                                @foreach ($optionalDocuments as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mt-4">
                            <label for="document_attachment_${docIndex}" class="form-label">Upload Optional Document ${currentDocs + 1}</label>
                            <input type="file" class="form-control optional-document" id="document_attachment_${docIndex}" name="document_attachment_${docIndex}">
                        </div>
                    `;

                        $("#optionalDocumentsContainer").append(docHtml);
                        currentDocs++;

                        if (currentDocs >= maxOptionalDocs) {
                            $("#addMoreDocuments").prop("disabled", true).text("Max Documents Reached");
                        }

                        updateProgress();
                    }
                });

                // Update file input label when a file is selected
                $(document).on("change", "input[type='file']", function() {
                    let fileName = $(this).val().split("\\").pop();
                    $(this).siblings("label").text(fileName || "Choose file...");
                    updateProgress();
                });

                updateProgress();

                function validateAddress() {
                    let address = $("#address").val().trim();
                    let pattern =
                        /^[A-Za-z\s]+,\s*[A-Za-z\s]+,\s*[A-Za-z\s]+,\s*[A-Za-z\s]+,\s*[A-Za-z\s]+,\s*\S+$/;
                    let isValid = pattern.test(address);

                    if (address === "") {
                        $("#addressError").addClass("d-none");
                        return false;
                    } else if (!isValid) {
                        $("#addressError").removeClass("d-none");
                        return false;
                    } else {
                        $("#addressError").addClass("d-none");
                        return true;
                    }
                }

                // Update progress when address is typed
                $("#address").on("keyup", function() {
                    validateAddress();
                    updateProgress();
                });

                $("form").on("submit", function(e) {
                    e.preventDefault();
                    // check if the form is id="uploadSignedAgreement" and if so, submit it
                    if (this.id === "uploadSignedAgreement") {
                        this.submit();
                    }

                    validateAddress();
                    if (!$("#addressError").hasClass("d-none")) {
                        e.preventDefault();
                        alert("Please enter a valid address.");
                    } else {
                        this.submit(); // If all is valid, submit the form
                    }
                });
            });
        </script>
    @endhasrole
    {{-- check if user is admin --}}
    @hasrole("Superadmin")
        {{-- List all agents data in a table, alow super admin to preview their documents and approve/reject them --}}

        <div class="container mt-4">
            <div class="table-responsive">
                <table class="table-bordered table-striped table" id="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Documents</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agents as $agent)
                            <tr class="small">
                                <td>{{ $agent->name }} <br> <span class="text-danger">
                                        {{ $agent->isAgent && $agent->isAgent->agent_code != null ? "Code: " . $agent->isAgent->agent_code : "" }}</span>
                                </td>
                                <td>{{ $agent->email }}</td>
                                <td>{{ $agent->phone }}</td>
                                <td>{{ $agent->isAgent != null ? $agent->isAgent->address : "-" }}</td>
                                <td>{{ $agent->isAgent != null ? ($agent->isAgent->registration_status == "incomplete" ? "Rejected" : $agent->isAgent->status) : "unverified" }}
                                </td>
                                <td>
                                    @if ($agent->isAgent != null)
                                        {{-- display a document name as a link to open the document in a new tab --}}
                                        <a href="{{ asset("storage/" . $agent->isAgent->document_attachment_1) }}"
                                            target="_blank">
                                            <small
                                                class="text-primary smallest">{{ $agent->isAgent->document_attachment_1 ? $agent->isAgent->document_attachment_1_name : "" }}</small>
                                        </a><br>
                                        <a href="{{ asset("storage/" . $agent->isAgent->signed_agreement_form) }}"
                                            target="_blank">
                                            <small
                                                class="text-primary smallest">{{ $agent->isAgent->signed_agreement_form ? "Agreement Form" : "" }}</small>
                                        </a> <br>
                                        <a href="{{ asset("storage/" . $agent->isAgent->document_attachment_2) }}"
                                            target="_blank">
                                            <small
                                                class="text-primary smallest">{{ $agent->isAgent->document_attachment_2 ? $agent->isAgent->document_attachment_2_name : "" }}</small>
                                        </a> <br>
                                        <a href="{{ asset("storage/" . $agent->isAgent->document_attachment_3) }}"
                                            target="_blank">
                                            <small
                                                class="text-primary smallest">{{ $agent->isAgent->document_attachment_3 ? $agent->isAgent->document_attachment_3_name : "" }}</small>
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="">
                                    <div class="d-flex justify-content-center">
                                        @if ($agent->isAgent != null)
                                            <a class="{{ $agent->isAgent->registration_status == "step_2" ? "" : "hidden" }} text-success"
                                                href="{{ route("agent.completeRegistration", ["action" => "verify", "set_status" => "accepted", "id" => $agent->isAgent->id]) }}"><small
                                                    class="smallest"><i class="bi bi-check"> Verify</i></small></a> <br>
                                            <a class="{{ $agent->isAgent->registration_status == "step_2" ? "" : "hidden" }} text-danger"
                                                href="{{ route("agent.completeRegistration", ["action" => "verify", "set_status" => "rejected", "id" => $agent->isAgent->id]) }}"><small
                                                    class="smallest"><i class="bi bi-x"> Reject</i></small></a>
                                            <a class="{{ $agent->isAgent->registration_status == "complete" ? "" : "hidden" }} {{ $agent->isAgent->agent_code != null ? "hidden" : "" }} text-primary"
                                                href="{{ route("agent.completeRegistration", ["action" => "generateAgentCode", "id" => $agent->isAgent->id]) }}"><small
                                                    class="smallest"><i class="bi bi-gear"> Generate code</small></i></a>
                                            {{-- <form action="" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form> --}}
                                    </div>
                                @else
                                    N/A
                        @endif
                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // initialize dataTable
                $('#Table').DataTable({
                    "lengthMenu": [10, 25, 50, "All"]
                    "pageLength": 10,
                    "order": [0, "asc"]
                });
            });
        </script>
    @endhasrole
@endsection
