@extends('agent.app')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <h2 class="h4 text-center text-primary mt-3">Quick Actions</h2>
            <div class="list-group shadow-sm mx-2">
                <button class="list-group-item list-group-item-action align-items-center" data-bs-toggle="modal"
                    data-bs-target="#addPharmacyModal">
                    <i class="bi bi-plus-circle me-2"></i> Add Pharmacy
                </button>
                <a href="{{ route('agent.packages', ['action' => 'index']) }}"
                    class="list-group-item list-group-item-action align-items-center">
                    <i class="bi bi-box-seam me-2"></i> Subscribe Package
                </a>
                <button class="list-group-item list-group-item-action align-items-center" data-bs-toggle="modal"
                    data-bs-target="#unreadMessagesModal">
                    <i class="bi bi-chat me-2"></i> Unread Messages
                </button>
                <button class="list-group-item list-group-item-action align-items-center" data-bs-toggle="modal"
                    data-bs-target="#reportCaseModal">
                    <i class="bi bi-exclamation-triangle me-2"></i> Report Case
                </button>
            </div>
        </div>
        {{-- end of quick actions --}}

        {{-- summary cards showing number of pharmacies, packages, active pharmacies, New messages, client's case filed and Inactive pharmacies --}}
        <div class="col-md-8">
            <h2 class="h2 mt-2 text-center text-primary">Summary</h2>
            <div class="text-light mx-2">
                <div class="col-md-12 row gap-3 justify-content-center">
                    <div class="bg-primary col-3 rounded shadow-md p-2 text-center text-wrapper">
                        <h2 class="font-light small mb-2">All Pharmacies</h2>
                        <p class="text-white-700">{{ $totalPharmacies }}</p>
                    </div>
                    <div class="bg-secondary col-3  rounded shadow-md p-2 text-center text-wrapper">
                        <h2 class="font-semibold mb-2">Packages</h2>
                        <p class="text-white-700">{{ $totalPackages }}</p>
                    </div>
                    <div class="bg-danger col-3  rounded shadow-md p-2 text-center text-wrapper">
                        <h2 class="font-light small mb-2">Active Pharmacies</h2>
                        <p class="text-white-700">{{ $activePharmacies }}</p>
                    </div>
                    <div class="bg-success col-3  rounded shadow-md p-2 text-center text-wrapper">
                        <h2 class="font-semibold mb-2">New Messages</h2>
                        <p class="text-white-700">{{ $totalMessages }}</p>
                    </div>
                    <div class="bg-warning  col-3 text-dark rounded shadow-md p-2 text-center text-wrap">
                        <h2 class="font-semibold mb-2">Client's Cases</h2>
                        <p class="text-gray-700">{{ $totalCases }}</p>
                    </div>
                    <div class="bg-white col-3  text-dark rounded shadow-md p-2 text-center text-wrap">
                        <h2 class="font-light small  mb-2">Inactive Pharmacies</h2>
                        <p class="text-gray-700">{{ $inactivePharmacies }}</p>
                    </div>
                </div>
            </div>
        </div>
        {{-- end of summary cards --}}
    </div>

    {{-- Draw an o-give and graph of number of pharmacies Vs the income generated --}}
    <div class="row">
        <div class="col-md-6">
            <h2 class="h2 mt-2 text-center text-primary">Pharmacies Vs Income</h2>
            <div class="text-light mx-2 p-2">
                <canvas id="bar" width="400" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <h2 class="h2 mt-2 text-center text-primary">Pharmacies Vs Income</h2>
            <div class="text-light mx-2 p-2">
                <canvas id="line" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Modal to create a new pharmacy -->
    <div class="modal fade" id="addPharmacyModal" tabindex="-1" aria-labelledby="addPharmacyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title  text-primary" id="addPharmacyModalLabel">Add a new pharmacy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('agent.pharmacies.store', ['action' => 'create']) }}" method="POST">
                        @csrf
                        <x-validation-errors class="mb-4" />
                        <div class="row">
                            <div class="col-6">
                                <h2 class="h5 text-primary">Pharmacy Details</h2>
                                <div class="mb-3">
                                    <x-label for="pharmacy_name" value="Name" class="form-label" />
                                    <x-input type="text" class="form-control rounded" id="pharmacy_name"
                                        name="pharmacy_name" placeholder="Pill Pharmacy" :value="old('pharmacy_name')" required />
                                </div>
                                <div class="mb-3">
                                    <x-label for="location" class="form-label" value="Location" />
                                    <x-input type="text" :value="old('location')" class="form-control rounded" id="location"
                                        name="location" placeholder="Morogoro" />
                                </div>
                                <div class="mb-3">
                                    <x-label for="status" class="form-label" value="Status" />
                                    <select class="form-select rounded" id="status" name="status" required>
                                        <option {{ old('status') == 'active' ? 'selected' : '' }} value="active">Active
                                        </option>
                                        <option {{ old('status') == 'inactive' ? 'selected' : '' }} value="inactive">
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <h2 class="h5 text-primary">Owner Details</h2>
                                <div>
                                    <x-label for="name" value="{{ __('Name') }}" />
                                    <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        :value="old('name')" required autofocus autocomplete="name"
                                        placeholder="Pill Point" />
                                </div>

                                <div class="mt-4">
                                    <x-label for="email" value="{{ __('Email') }}" />
                                    <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email')" required autocomplete="username"
                                        placeholder="info@pillpoint.com" />
                                </div>

                                <div class="mt-4">
                                    <x-label for="phone_number" value="{{ __('Phone Number') }}" />
                                    <x-input id="phone_number" class="block mt-1 w-full" type="tel"
                                        name="phone_number" :value="old('phone_number')" required placeholder="0742177328"
                                        autocomplete="phone_number" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-between">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Unread Messages Modal -->
    <div class="modal fade" id="unreadMessagesModal" tabindex="-1" aria-labelledby="unreadMessagesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="unreadMessagesModalLabel">
                        <i class="bi bi-envelope-open"></i> Unread Messages
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search Bar -->
                    <div class="mb-3">
                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Search conversations...">
                    </div>
                    <!-- Messages List -->
                    <div id="messagesContainer">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-primary" role="status" id="loadingSpinner">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- Report Case Modal --}}
    <div class="modal fade" id="reportCaseModal" tabindex="-1" aria-labelledby="reportCaseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                {{-- Modal Header --}}
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="reportCaseModalLabel"><i class="bi bi-exclamation-triangle me-2"></i>
                        Report a Concern</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body">
                    <form id="reportCaseForm" action="{{ route('agent.cases', ['action' => 'index']) }}" method="POST">
                        @csrf
                        <x-validation-errors class="mb-3" />

                        {{-- Subject --}}
                        <div class="mb-3">
                            <label for="subject" class="form-label fw-bold">Subject <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded" id="subject" name="subject"
                                placeholder="Enter a short title for the issue" required>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control rounded" id="description" name="description" rows="4"
                                placeholder="Describe the issue in detail..." required></textarea>
                        </div>

                        {{-- Urgency Level --}}
                        <div class="mb-3">
                            <label for="urgency" class="form-label fw-bold">Urgency Level <span
                                    class="text-danger">*</span></label>
                            <select class="form-select rounded" id="urgency" name="urgency" required>
                                <option value="low">Low - Minor Issue</option>
                                <option value="medium">Medium - Needs Attention</option>
                                <option value="high">High - Critical Problem</option>
                            </select>
                        </div>

                        {{-- File Upload (Optional) --}}
                        <div class="mb-3">
                            <label for="attachment" class="form-label fw-bold">Attach a Screenshot (Optional)</label>
                            <input type="file" class="form-control rounded" id="attachment" name="attachment"
                                accept="image/*">
                        </div>

                        {{-- Progress Bar (Hidden Initially) --}}
                        <div class="progress mt-3 d-none" id="uploadProgress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                style="width: 0%;" id="progressBar">Uploading...</div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger"><i class="bi bi-send me-1"></i> Submit
                                Report</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal for Confirmation --}}
    <script>
        document.getElementById("reportCaseForm").addEventListener("submit", function(event) {
            let fileInput = document.getElementById("attachment");
            let progressBar = document.getElementById("uploadProgress");
            let progressFill = document.getElementById("progressBar");

            if (fileInput.files.length > 0) {
                event.preventDefault();
                progressBar.classList.remove("d-none");

                let progress = 0;
                let interval = setInterval(() => {
                    progress += 10;
                    progressFill.style.width = progress + "%";

                    if (progress >= 100) {
                        clearInterval(interval);
                        event.target.submit();
                    }
                }, 300);
            }
        });
    </script>

    {{-- For Messages --}}
    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     const messagesContainer = document.getElementById("messagesContainer");
        //     const loadingSpinner = document.getElementById("loadingSpinner");
        //     const searchInput = document.getElementById("searchInput");
        //     let currentExpandedConversation = null;

        //     function fetchUnreadMessages() {
        //         fetch("{{ route('agent.messages', ['action' => 'unread']) }}")
        //             .then(response => response.json())
        //             .then(data => {
        //                 loadingSpinner.style.display = "none";

        //                 if (data.length === 0) {
        //                     messagesContainer.innerHTML =
        //                         `<div class="alert alert-info text-center">No unread messages</div>`;
        //                     return;
        //                 }

        //                 // Catch error if exists
        //                 if (data.error) {
        //                     console.log(data.error);
        //                     throw new Error(data.error);
        //                     return;
        //                 }

        //                 // Filter conversations based on search input
        //                 const searchQuery = searchInput.value.toLowerCase();
        //                 const filteredConversations = data.filter(conversation =>
        //                     conversation.title.toLowerCase().includes(searchQuery) ||
        //                     (conversation.description && conversation.description.toLowerCase().includes(
        //                         searchQuery))
        //                 );

        //                 messagesContainer.innerHTML = filteredConversations.map(conversation => {
        //                     if (conversation.messages.length === 0) {
        //                         return ''; // Skip conversations with no unread messages
        //                     }

        //                     return `
        //             <div class="card mb-3 shadow-sm">
        //                 <div class="card-body">
        //                     <h5 class="card-title text-primary">
        //                         <i class="bi bi-chat-dots"></i> ${conversation.title}
        //                     </h5>
        //                     <p class="card-text">${conversation.description || 'No description available'}</p>

        //                     <button class="btn btn-sm btn-outline-primary float-end" 
        //                         data-bs-toggle="collapse" 
        //                         data-bs-target="#messages-${conversation.id}" 
        //                         aria-expanded="false" 
        //                         aria-controls="messages-${conversation.id}">
        //                         ${currentExpandedConversation === conversation.id ? 'Collapse' : 'Expand'}
        //                     </button>

        //                     <hr>

        //                     <div id="messages-${conversation.id}" class="collapse ${currentExpandedConversation === conversation.id ? 'show' : ''}">
        //                         ${conversation.messages.map(message => `
        //                                 <div class="message">
        //                                     <h6 class="card-title text-secondary">
        //                                         <i class="bi bi-person-circle"></i> ${message.sender.name}
        //                                     </h6>
        //                                     <p class="card-text">${message.content}</p>
        //                                     <small class="text-muted">
        //                                         <i class="bi bi-clock"></i> ${new Date(message.created_at).toLocaleString()}
        //                                     </small>
        //                                     <button class="btn btn-sm btn-success float-end mark-read" data-id="${message.id}">
        //                                         <i class="bi bi-check-circle"></i> Mark as Read
        //                                     </button>
        //                                 </div>
        //                                 <hr>
        //                             `).join('')}
        //                     </div>
        //                 </div>
        //             </div>
        //         `;
        //                 }).join("");

        //                 // Event listeners for "Mark as Read" buttons
        //                 document.querySelectorAll(".mark-read").forEach(button => {
        //                     button.addEventListener("click", function() {
        //                         markAsRead(this.getAttribute("data-id"));
        //                     });
        //                 });

        //                 // Event listener for collapse/expand buttons
        //                 document.querySelectorAll('[data-toggle="collapse"]').forEach(button => {
        //                     button.addEventListener("click", function() {
        //                         const conversationId = this.closest('.card').querySelector('h5')
        //                             .innerText;
        //                         if (currentExpandedConversation === conversationId) {
        //                             currentExpandedConversation = null;
        //                         } else {
        //                             currentExpandedConversation = conversationId;
        //                         }
        //                     });
        //                 });
        //             })
        //             .catch(error => {
        //                 console.error("Error fetching messages:", error);
        //                 messagesContainer.innerHTML =
        //                     `<div class="alert alert-danger text-center">Failed to load messages</div>`;
        //             });
        //     }

        //     function markAsRead(id) {
        //         fetch(`{{ route('agent.messages', ['action' => 'markRead']) }}/${id}`, {
        //                 method: "POST"
        //             })
        //             .then(response => response.json())
        //             .then(data => {
        //                 if (data.success) {
        //                     document.querySelector(`button[data-id="${id}"]`).closest(".message").remove();
        //                     if (document.querySelectorAll(".message").length === 0) {
        //                         messagesContainer.innerHTML =
        //                             `<div class="alert alert-info text-center">No unread messages</div>`;
        //                     }
        //                 }
        //                 if (data.error) {
        //                     console.log(data.error);
        //                     throw new Error(data.error);
        //                     return;
        //                 }
        //             })
        //             .catch(error => console.error("Error marking message as read:", error));
        //     }

        //     // Search filter event listener
        //     searchInput.addEventListener("input", fetchUnreadMessages);

        //     // Fetch messages when the modal is shown
        //     document.getElementById("unreadMessagesModal").addEventListener("show.bs.modal", fetchUnreadMessages);
        // });
        document.addEventListener("DOMContentLoaded", function() {
    const messagesContainer = document.getElementById("messagesContainer");
    const loadingSpinner = document.getElementById("loadingSpinner");
    const searchInput = document.getElementById("searchInput");
    let currentExpandedConversation = null;

    function fetchUnreadMessages() {
        fetch("{{ route('agent.messages', ['action' => 'unread']) }}")
            .then(response => response.json())
            .then(data => {
                loadingSpinner.style.display = "none";

                if (data.length === 0) {
                    messagesContainer.innerHTML =
                        `<div class="alert alert-info text-center">No unread messages</div>`;
                    return;
                }

                // Catch error if exists
                if (data.error) {
                    console.log(data.error);
                    throw new Error(data.error);
                }

                // Filter conversations based on search input
                const searchQuery = searchInput.value.toLowerCase();
                const filteredConversations = data.filter(conversation =>
                    conversation.title.toLowerCase().includes(searchQuery) ||
                    (conversation.description && conversation.description.toLowerCase().includes(searchQuery))
                );

                messagesContainer.innerHTML = filteredConversations.map(conversation => `
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="bi bi-chat-dots"></i> ${conversation.title}
                            </h5>
                            <p class="card-text">${conversation.description || 'No description available'}</p>

                            <button class="btn btn-sm btn-outline-primary float-end toggle-btn" 
                                data-id="${conversation.id}">
                                ${currentExpandedConversation === conversation.id ? 'Collapse' : 'Expand'}
                            </button>

                            <hr>

                            <div id="messages-${conversation.id}" class="messages-container" 
                                 style="display: ${currentExpandedConversation === conversation.id ? 'block' : 'none'};">
                                ${conversation.messages.map(message => `
                                    <div class="message">
                                        <h6 class="card-title text-secondary">
                                            <i class="bi bi-person-circle"></i> ${message.sender.name}
                                        </h6>
                                        <p class="card-text">${message.content}</p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> ${new Date(message.created_at).toLocaleString()}
                                        </small>
                                        <button class="btn btn-sm btn-success float-end mark-read" data-id="${message.id}">
                                            <i class="bi bi-check-circle"></i> Mark as Read
                                        </button>
                                    </div>
                                    <hr>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `).join("");

                // Event listeners for "Mark as Read" buttons
                document.querySelectorAll(".mark-read").forEach(button => {
                    button.addEventListener("click", function() {
                        markAsRead(this.getAttribute("data-id"));
                    });
                });

                // Event listener for expand/collapse buttons
                document.querySelectorAll(".toggle-btn").forEach(button => {
                    button.addEventListener("click", function() {
                        const conversationId = this.getAttribute("data-id");
                        const messagesDiv = document.getElementById(`messages-${conversationId}`);

                        if (currentExpandedConversation === conversationId) {
                            messagesDiv.style.display = "none";
                            this.textContent = "Expand";
                            currentExpandedConversation = null;
                        } else {
                            // Collapse any open conversation
                            document.querySelectorAll(".messages-container").forEach(div => div.style.display = "none");
                            document.querySelectorAll(".toggle-btn").forEach(btn => btn.textContent = "Expand");

                            // Expand the selected one
                            messagesDiv.style.display = "block";
                            this.textContent = "Collapse";
                            currentExpandedConversation = conversationId;
                        }
                    });
                });
            })
            .catch(error => {
                console.error("Error fetching messages:", error);
                messagesContainer.innerHTML =
                    `<div class="alert alert-danger text-center">Failed to load messages</div>`;
            });
    }

    function markAsRead(id) {
        fetch(`{{ route('agent.messages', ['action' => 'markRead']) }}/${id}`, {
                method: "POST"
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`button[data-id="${id}"]`).closest(".message").remove();
                    if (document.querySelectorAll(".message").length === 0) {
                        messagesContainer.innerHTML =
                            `<div class="alert alert-info text-center">No unread messages</div>`;
                    }
                }
                if (data.error) {
                    console.log(data.error);
                    throw new Error(data.error);
                }
            })
            .catch(error => console.error("Error marking message as read:", error));
    }

    // Search filter event listener
    searchInput.addEventListener("input", fetchUnreadMessages);

    // Fetch messages when the modal is shown
    document.getElementById("unreadMessagesModal").addEventListener("show.bs.modal", fetchUnreadMessages);
});

    </script>

    {{-- script for id="pharmaciesVsIncomeChart" --}}
    <script>
        $(document).ready(function() {
            // Sample data for pharmacies and incomes
            var ctxbar = $('#bar')[0].getContext('2d');
            var ctxline = $('#line')[0].getContext('2d');

            data = [2200, 2900, 4000, 6000, 3000];
            labels = ['June', 'July', 'Aug', 'Sept', 'Nov'];

            var bar = new Chart(ctxbar, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Transactions',
                        data: data,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });

            // convert above to line graph
            var line = new Chart(ctxline, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Transactions',
                        data: data,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        });
    </script>
@endsection
