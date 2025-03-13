@extends('agent.app')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Sidebar: Conversations List -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between">
                        <h5 class="mb-0"><i class="bi bi-chat-dots"></i> Conversations</h5>
                        <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#createConversationModal">
                            <i class="bi bi-plus-circle"></i> New
                        </button>
                    </div>
                    <div class="card-body p-2">
                        <input type="text" id="searchConversations" class="form-control mb-2" placeholder="Search...">

                        <ul class="list-group" id="conversationsList">
                            @foreach ($conversations as $conversation)
                                <li class="list-group-item d-flex justify-content-between align-items-center conversation-item"
                                    data-id="{{ $conversation->id }}">
                                    <span><i class="bi bi-chat-left-text"></i> {{ $conversation->title }}</span>
                                    <span
                                        class="badge bg-danger">{{ count($conversation->messages->whereNull('read_at')) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Chat Panel -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between">
                        <h5 class="mb-0"><i class="bi bi-envelope"></i> Messages</h5>
                        <button id="leaveConversation" class="btn btn-sm btn-danger d-none"><i class="bi bi-x-circle"></i>
                            Leave</button>
                    </div>
                    <div class="card-body" id="messagesPanel">
                        <div class="alert alert-info text-center">Select a conversation to view messages</div>
                    </div>
                    <div class="card-footer d-none" id="messageInputArea">
                        <div class="input-group">
                            <input type="text" id="messageInput" class="form-control" placeholder="Type a message...">
                            <button class="btn btn-primary" id="sendMessage"><i class="bi bi-send"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Conversation Modal -->
    <div class="modal fade" id="createConversationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-chat-square-dots"></i> Create Conversation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="createConversationForm">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <!-- Description -->
                        <div class="mb-3">
                            <label for="conversationDescription" class="form-label">Description</label>
                            <textarea class="form-control summernote" id="conversationDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Participants</label>
                            <select class="form-select" id="conversationParticipants" name="recipients[]" multiple
                                live-search="true" data-live-search-style="contains"
                                data-live-search-placeholder="Search...">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let currentConversationId = null;

            // Search Conversations
            $("#searchConversations").on("input", function() {
                let query = $(this).val().toLowerCase();
                $(".conversation-item").each(function() {
                    $(this).toggle($(this).text().toLowerCase().includes(query));
                });
            });

            // Load Messages when clicking a conversation
            $(".conversation-item").on("click", function() {
                currentConversationId = $(this).data("id");
                $("#messagesPanel").html(
                    '<div class="text-center my-3"><div class="spinner-border"></div></div>');
                $("#messageInputArea, #leaveConversation").removeClass("d-none");

                $.get(`/messages/${currentConversationId}`, function(data) {
                    let messagesHtml = data.messages.map(msg =>
                        `<div class="mb-2">
                    <strong>${msg.sender.name}</strong>: ${msg.content}
                    <small class="text-muted float-end">${new Date(msg.created_at).toLocaleString()}</small>
                </div><hr>`
                    ).join("");

                    $("#messagesPanel").html(messagesHtml ||
                        '<div class="alert alert-warning">No messages</div>');
                });
            });

            // Send Message
            $("#sendMessage").on("click", function() {
                let content = $("#messageInput").val().trim();
                if (!content || !currentConversationId) return;

                $.post(`/messages/${currentConversationId}`, {
                    content,
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    if (response.success) {
                        $("#messagesPanel").append(
                            `<div class="mb-2">
                        <strong>You</strong>: ${content}
                        <small class="text-muted float-end">${new Date().toLocaleString()}</small>
                    </div><hr>`
                        );
                        $("#messageInput").val("");
                    } else {
                        alert("Message failed!");
                    }
                });
            });

            // Create Conversation
            $("#createConversationForm").on("submit", function(event) {
                event.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('agent.messages', ['action' => 'createConversation']) }}", // Update with actual route
                    type: "POST",
                    data: formData,
                    processData: false, // Prevent jQuery from processing data
                    contentType: false, // Prevent jQuery from setting content type
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.success) {
                            alert("Conversation created successfully!");
                            $("#createConversationForm")[0].reset(); // Reset form
                            // refresh the page
                            window.location.reload();
                            $("#recipientsList").html(""); // Clear recipients list

                            // Close modal
                            $("#createConversationModal").modal("hide");
                        } else {
                            alert("Failed to create conversation: " + data.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        // refresh the page
                        window.location.reload();
                        console.error("Error creating conversation:", error);
                        alert("An error occurred. Please try again.");
                    }
                });
            });

            // Leave Conversation
            $("#leaveConversation").on("click", function() {
                currentConversationId = null;
                $("#messagesPanel").html(
                    '<div class="alert alert-info text-center">Select a conversation</div>');
                $("#messageInputArea, #leaveConversation").addClass("d-none");
            });
        });
    </script>
@endsection
