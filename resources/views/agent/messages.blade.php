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
                                    data-id="{{ $conversation->id }}" data-auth-user-id="{{ Auth::user()->id }}">
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
                            <div class="alert alert-info text-center scroll-to-bottom">
                                Select a conversation to view messages
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-none" id="messageInputArea">
                        <div class="input-group flex justify-content-end">
                            <input type="text" id="messageInput" class="form-control summernote">
                            <button class="btn btn-primary" id="sendMessage">
                                <i class="bi bi-send"> Send</i>
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
            // capture login user id
            // let AuthUserId = "{{ Auth::user()->id }}";

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
                // add active class to the clicked conversation
                $(".conversation-item").removeClass("active");
                $(this).addClass("active");

                currentConversationId = $(this).data("id");
                AuthUserId = $(this).data("auth-user-id");
                replyToMessageId = null; // Reset reply state
                $("#messagesPanel").html(
                    '<div class="text-center my-3"><div class="spinner-border"></div></div>');
                $("#messageInputArea, #leaveConversation").removeClass("d-none");

                $("#messagesPanel")
                    .css({
                        "overflowY": "auto",
                        "maxHeight": "50vh",
                    });

                $.ajax({
                    url: `/agent/messages`,
                    type: "GET",
                    data: {
                        action: "getMessages",
                        conversation_id: currentConversationId
                    },
                    success: function(response) {
                        if (response.success && response.messages.length > 0) {
                            console.log(response.messages);
                            let messagesHtml = response.messages
                                .map(msg => {
                                    let parentMessageHtml = "";

                                    // Check if the message has a parent
                                    if (msg.parent_message) {
                                        parentMessageHtml = `
                                            <div class="p-1 rounded bg-secondary text-light small overflow-hidden" style="max-width: 100%; max-height: 50%; overflow-y: hidden; cursor: pointer;">
                                                <small class="smallest"><strong class="smallest">${msg.parent_message.sender.name}</strong></small>: ${msg.parent_message.content}
                                            </div>
                                        `;
                                    }

                                    return `
                                        <div class="d-flex ${msg.sender_id == AuthUserId ? 'justify-content-end' : 'justify-content-start'} mb-2 message-item">
                                            <div class="p-2 rounded ${msg.sender_id == AuthUserId ? 'bg-light text-dark' : 'bg-info'}" style="max-width: 90%;">
                                                ${parentMessageHtml}
                                                <small class="smallest"><strong>${msg.sender.name}</strong></small><br>
                                                ${msg.content}
                                                <div class="d-flex justify-content-end">
                                                    <button ${msg.sender_id == AuthUserId ? '' : 'hidden'} class="btn mt-1 btn-sm text-danger delete-btn" data-id="${msg.id}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                    &nbsp;
                                                    <div class="text-muted mt-2 small text-end">${new Date(msg.created_at).toLocaleTimeString()}</div>
                                                    &nbsp;
                                                    <button class="btn btn-sm text-primary reply-btn" data-sender_id="${msg.sender.id}" data-sender_name="${msg.sender.name}" data-id="${msg.id}" data-content="${msg.content}">
                                                        <i class="bi bi-reply"></i> Reply
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                })
                                .join("");

                            $("#messagesPanel").html(messagesHtml);

                            // Scroll by animation to the bottom of the messages panel
                            $("#messagesPanel").animate({
                                scrollTop: $("#messagesPanel")[0].scrollHeight
                            }, 1000);

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
                    url: `/agent/messages`,
                    type: "POST",
                    data: {
                        conversation_id: conversationId,
                        _token: "{{ csrf_token() }}",
                        action: "mark-read"
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
                let messageContent = 'Replying to ' + $(this).data("sender_name") + ": " + $(this).data(
                    "content");

                // remove summernote class from the textarea
                $('#messageInput').removeClass('summernote');
                $('#messageInput').summernote('destroy');
                // clear the textarea  
                $('#messageInput').val('');

                //Initialize summernot and set the value of the textarea at the same time
                $('#messageInput').summernote({
                    // initialize textarea with the message content
                    placeholder: messageContent,
                    tabsize: 2,
                    height: 100,
                    toolbar: [
                        // Add the "reply" button to the toolbar
                        ['reply', ['reply']],
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                        // Add more buttons as needed
                    ],
                    // set focus on the textarea
                    focus: true,
                });
            });

            // Send Message
            $("#sendMessage").on("click", function() {
                let content = $("#messageInput").summernote("code");
                // alert(content);
                if (!content || !currentConversationId) return;

                $.ajax({
                    url: "/agent/messages",
                    type: "POST",
                    data: {
                        content: content,
                        action: replyToMessageId ? "sendReply" : "sendMessage",
                        conversation_id: currentConversationId,
                        reply_to: replyToMessageId, // Send reply-to message ID
                        _token: $('meta[name="csrf-token"]').attr(
                            "content"), // Ensure CSRF token is included
                        conversationId: currentConversationId,
                        parentMessageId: replyToMessageId ? replyToMessageId : null,
                        message: content
                    },
                    success: function(response) {
                        if (response.success) {
                            // alert(response.message);

                            // Auto-click the conversation to reload messages
                            $(".conversation-item[data-id='" + currentConversationId + "']")
                                .click();

                            // Mark as active
                            $(".conversation-item[data-id='" + currentConversationId + "']")
                                .addClass("active");

                            // Clear the input field
                            $("#messageInput").summernote('destroy');
                            $("#messageInput").val('');
                            $("#messageInput").summernote();

                            replyToMessageId = null; // Reset reply state
                        } else {
                            alert("Message failed: " + response.message);
                        }
                    },
                    error: function(xhr) {
                        console.error("Error sending message:", xhr.responseText);
                        alert("An error occurred. Check the console for details.");
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

            // delete a message
            $("#messagesPanel").on("click", ".delete-btn", function() {
                const messageId = $(this).data("id");
                // alert("Deleting message with ID: " + messageId);
                if (!confirm("Are you sure you want to delete this message?")) return;

                $.ajax({
                    url: "/agent/messages",
                    type: "POST", // Use POST because DELETE is hard to send with query params
                    data: {
                        action: "delete",
                        id: messageId,
                        _token: $('meta[name="csrf-token"]').attr(
                            "content")
                    },
                    success: function(response) {
                        if (response.success) {
                            // console.log(response.message);
                            // alert(response.message);
                            $(`button[data-id="${messageId}"]`).closest(".message-item")
                                .remove();
                        } else {
                            // console.log(response.message);
                            alert("You can\'t delete this message!");
                            // alert("Failed to delete message: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.error("Error deleting message:", error);
                        alert("Error deleting message:", error);
                    }
                });
            });

        });
    </script>
@endsection
