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
                        <button id="leaveConversation" class="btn btn-sm btn-danger d-none">
                            <i class="bi bi-x-circle"></i> Leave
                        </button>
                    </div>
                    <div class="card-body chat-container">
                        <div id="messagesPanel" class="chat-box">
                            <div class="alert alert-info text-center">Select a conversation to view messages</div>
                        </div>
                    </div>
                    <div class="card-footer d-none" id="messageInputArea">
                        <div class="input-group">
                            <input type="text" id="messageInput" class="form-control" placeholder="Type a message...">
                            <button class="btn btn-primary" id="sendMessage">
                                <i class="bi bi-send"></i>
                            </button>
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
            let replyToMessageId = null;

            // ✅ 1. Fix Search Function
            $("#searchConversations").on("keyup", function() {
                const query = $(this).val().toLowerCase();

                $(".conversation-item").each(function() {
                    const label = $(this).find(".conversation-title").text().toLowerCase();
                    $(this).toggle(label.includes(query));
                });
            });

            // Load Messages when clicking a conversation
            $(document).on("click", ".conversation-item", function() {
                currentConversationId = $(this).data("id");
                replyToMessageId = null; // Reset reply state
                $("#messagesPanel").html(
                    '<div class="text-center my-3"><div class="spinner-border"></div></div>');
                $("#messageInputArea, #leaveConversation").removeClass("d-none");

                $.ajax({
                    url: `/agent/messages`,
                    type: "GET",
                    data: {
                        action: "getMessages",
                        conversation_id: currentConversationId
                    },
                    success: function(response) {
                        if (response.success && response.messages.length > 0) {
                            let messagesHtml = response.messages
                                .map(msg => `
                        <div class="d-flex ${msg.isMine ? 'justify-content-end' : 'justify-content-start'} mb-2">
                            <div class="p-2 rounded ${msg.isMine ? 'bg-primary text-white' : 'bg-light'}" style="max-width: 75%;">
                                <strong>${msg.sender.name}</strong><br>
                                ${msg.content}
                                <div class="text-muted small text-end">${new Date(msg.created_at).toLocaleTimeString()}</div>
                                <button class="btn btn-sm btn-outline-secondary reply-btn" data-id="${msg.id}" data-content="${msg.content}">
                                    <i class="bi bi-reply"></i> Reply
                                </button>
                            </div>
                        </div>
                    `)
                                .join("");

                            $("#messagesPanel").html(messagesHtml);
                            markMessagesAsRead(currentConversationId);
                        } else {
                            $("#messagesPanel").html(
                                '<div class="alert alert-warning">No messages found.</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching messages:", error);
                        alert("Failed to load messages. Please try again.");
                    }
                });
            });

            // Mark Messages as Read
            function markMessagesAsRead(conversationId) {
                $.ajax({
                    url: `/agent/messages/mark-read`,
                    type: "POST",
                    data: {
                        conversation_id: conversationId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log("Messages marked as read.");
                    },
                    error: function() {
                        console.log("Failed to mark messages as read.");
                    }
                });
            }

            // Reply to a Message
            $(document).on("click", ".reply-btn", function() {
                replyToMessageId = $(this).data("id");
                let messageContent = $(this).data("content");
                $("#messageInput").val(`@"${messageContent}" - `).css({
                    "background-color": "#e0f7fa", // Light blue background
                    "font-weight": "bold"
                }).focus();
            });

            // Send Message (with Reply Feature)
            $("#sendMessage").on("click", function() {
                let content = $("#messageInput").val().trim();
                if (!content || !currentConversationId) return;

                $.post(`/messages/${currentConversationId}`, {
                    content,
                    reply_to: replyToMessageId, // Send reply-to message ID
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    if (response.success) {
                        let newMessage = `
                    <div class="d-flex justify-content-end mb-2">
                        <div class="p-2 rounded bg-primary text-white" style="max-width: 75%;">
                            <strong>You</strong><br>${content}
                            <div class="text-muted small text-end">${new Date().toLocaleTimeString()}</div>
                        </div>
                    </div>
                `;
                        $("#messagesPanel").append(newMessage);
                        $("#messageInput").val("");
                        replyToMessageId = null; // Reset reply state
                    } else {
                        alert("Message failed!");
                    }
                });
            });

            // ✅ Fix Create Conversation
            $("#createConversationForm").on("submit", function(event) {
                event.preventDefault();

                const formData = new FormData(this);

                $.ajax({
                    url: "{{ route('agent.messages', ['action' => 'createConversation']) }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data.success) {
                            alert("Conversation created successfully!");
                            $("#createConversationForm")[0].reset();
                            window.location.reload(); // ✅ Refresh Page After Creation
                            $("#recipientsList").html("");
                            $("#createConversationModal").modal("hide");
                        } else {
                            alert("Failed to create conversation: " + data.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        window.location.reload();
                        console.error("Error creating conversation:", error);
                        alert("An error occurred. Please try again.");
                    },
                });
            });

            // ✅ Fix Leave Conversation
            $("#leaveConversation").on("click", function() {
                currentConversationId = null;
                $("#messagesPanel").html(
                    '<div class="alert alert-info text-center">Select a conversation</div>'
                );
                $("#messageInputArea, #leaveConversation").addClass("d-none");
            });
        });
    </script>
@endsection
