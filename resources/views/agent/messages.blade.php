@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <!-- Conversations Sidebar -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-chat-left-dots"></i> Conversations</span>
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#createConversationModal">
                            <i class="bi bi-plus-circle"></i> New
                        </button>
                    </div>
                    <div class="card-body p-2">
                        <input type="text" id="searchConversations" class="form-control mb-2"
                            placeholder="Search conversations...">
                        <div id="conversationsList" class="list-group">
                            <!-- Conversations will be dynamically loaded here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Panel -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 id="conversationTitle" class="mb-0">Select a conversation</h5>
                    </div>
                    <div class="card-body" id="chatContainer" style="height: 400px; overflow-y: auto;">
                        <!-- Messages will be loaded here -->
                    </div>
                    <div class="card-footer">
                        <div class="d-flex">
                            <input type="text" id="newMessage" class="form-control" placeholder="Type a message...">
                            <button class="btn btn-primary ms-2" id="sendMessage">
                                <i class="bi bi-send"></i> Send
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Conversation Modal -->
    <div class="modal fade" id="createConversationModal" tabindex="-1" aria-labelledby="createConversationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createConversationModalLabel">New Conversation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createConversationForm">
                        <div class="mb-3">
                            <label for="conversationTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="conversationTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="conversationRecipients" class="form-label">Select Recipients</label>
                            <div id="recipientsList" class="form-check">
                                <!-- Dynamically populated recipient checkboxes -->
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let currentConversationId = null;

            // Load Conversations
            function loadConversations() {
                $.ajax({
                    url: "{{ route('agent.messages', ['action' => 'getConversations']) }}",
                    type: "GET",
                    success: function(data) {
                        let listHTML = "";
                        data.forEach(convo => {
                            listHTML += `
                        <a href="#" class="list-group-item list-group-item-action conversation-item" data-id="${convo.id}">
                            <i class="bi bi-chat-left"></i> ${convo.title}
                        </a>`;
                        });
                        $("#conversationsList").html(listHTML);
                    }
                });
            }

            // Load Messages when a conversation is clicked
            $(document).on("click", ".conversation-item", function() {
                currentConversationId = $(this).data("id");
                $("#conversationTitle").text($(this).text().trim());
                loadMessages(currentConversationId);
            });

            function loadMessages(conversationId) {
                $.ajax({
                    url: `{{ route('agent.messages', ['action' => 'getMessages']) }}/${conversationId}`,
                    type: "GET",
                    success: function(data) {
                        let messagesHTML = "";
                        data.messages.forEach(msg => {
                            messagesHTML += `
                        <div class="message p-2 border-bottom">
                            <strong>${msg.sender.name}:</strong>
                            <p>${msg.content}</p>
                            <small class="text-muted">${msg.created_at}</small>
                            <button class="btn btn-sm btn-warning edit-message" data-id="${msg.id}"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger delete-message" data-id="${msg.id}"><i class="bi bi-trash"></i></button>
                        </div>`;
                        });
                        $("#chatContainer").html(messagesHTML);
                    }
                });
            }

            // Send Message
            $("#sendMessage").on("click", function() {
                let message = $("#newMessage").val().trim();
                if (!message || !currentConversationId) return;

                $.ajax({
                    url: "{{ route('agent.messages', ['action' => 'sendMessage']) }}",
                    type: "POST",
                    data: {
                        conversation_id: currentConversationId,
                        content: message,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        $("#newMessage").val("");
                        loadMessages(currentConversationId);
                    }
                });
            });

            // Edit Message
            $(document).on("click", ".edit-message", function() {
                let messageId = $(this).data("id");
                let newContent = prompt("Edit your message:");
                if (!newContent) return;

                $.ajax({
                    url: `{{ route('agent.messages', ['action' => 'editMessage']) }}/${messageId}`,
                    type: "POST",
                    data: {
                        content: newContent,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        loadMessages(currentConversationId);
                    }
                });
            });

            // Delete Message
            $(document).on("click", ".delete-message", function() {
                let messageId = $(this).data("id");
                if (!confirm("Are you sure you want to delete this message?")) return;

                $.ajax({
                    url: `{{ route('agent.messages', ['action' => 'deleteMessage']) }}/${messageId}`,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        loadMessages(currentConversationId);
                    }
                });
            });

            // Create a New Conversation
            $("#createConversationForm").on("submit", function(e) {
                e.preventDefault();
                let title = $("#conversationTitle").val();
                let recipients = $("input[name='recipients[]']:checked").map(function() {
                    return this.value;
                }).get();

                $.ajax({
                    url: "{{ route('agent.messages', ['action' => 'createConversation']) }}",
                    type: "POST",
                    data: {
                        title: title,
                        recipients: recipients,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        $("#createConversationModal").modal("hide");
                        loadConversations();
                    }
                });
            });

            // Load Conversations on Page Load
            loadConversations();
        });
    </script>
@endsection
