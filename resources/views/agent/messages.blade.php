@extends('agent.app')

@section('content')
    <style>
        /* Custom Scrollbar */
        .chat-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }

        .chat-scroll::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }

        /* Conversation Items */
        .conversation-item {
            cursor: pointer;
            transition: background-color 0.2s;
            border-left: 4px solid transparent;
        }

        .conversation-item:hover {
            background-color: #f8f9fa;
        }

        .conversation-item.active {
            background-color: #e9ecef;
            border-left-color: #0d6efd;
        }

        /* Message Bubbles */
        .message-bubble {
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 15px;
            position: relative;
            font-size: 0.95rem;
        }

        .message-sent {
            background-color: #0d6efd;
            /* Primary Blue */
            color: white;
            border-bottom-right-radius: 2px;
            float: right;
        }

        .message-received {
            background-color: #f1f0f0;
            /* Light Gray */
            color: #333;
            border-bottom-left-radius: 2px;
            float: left;
        }

        .message-meta {
            font-size: 0.75rem;
            opacity: 0.8;
            margin-top: 4px;
            text-align: right;
        }

        /* Attachment Preview */
        .attachment-preview img {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 5px;
            cursor: pointer;
        }
    </style>

    <div class="container-fluid py-3">
        <div class="row g-3" style="height: 85vh;">

            <!-- Sidebar: Conversations -->
            <div class="col-md-4 col-lg-3 d-flex flex-column h-100">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header border-bottom bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-primary mb-0">Messages</h5>
                            <button class="btn btn-sm btn-primary rounded-pill shadow-sm"
                                data-bs-target="#createConversationModal" data-bs-toggle="modal">
                                <i class="bi bi-pencil-square"></i> New
                            </button>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input class="form-control bg-light border-start-0" id="searchConversations"
                                placeholder="Search chats..." type="text">
                        </div>
                    </div>

                    <div class="card-body chat-scroll overflow-auto p-0" id="conversationsListContainer">
                        <div class="text-muted py-5 text-center" id="loadingConversations">
                            <div class="spinner-border text-primary spinner-border-sm" role="status"></div> Loading...
                        </div>
                        <ul class="list-group list-group-flush" id="conversationsList">
                            <!-- Conversations will be loaded here -->
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="col-md-8 col-lg-9 d-flex flex-column h-100">
                <div class="card h-100 border-0 shadow-sm">

                    <!-- Chat Header -->
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center bg-white py-3"
                        id="chatHeader" style="display: none !important;">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 45px; height: 45px; font-size: 1.2rem;">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0" id="activeConversationTitle">Select a conversation</h6>
                                <small class="text-muted" id="activeConversationParticipants"></small>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-danger border-0" id="closeChatBtn" title="Close Chat">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <!-- Chat Messages -->
                    <div class="card-body bg-light chat-scroll d-flex flex-column overflow-auto" id="messagesPanel">
                        <div class="align-self-center text-muted my-auto p-5 text-center" id="emptyState">
                            <i class="bi bi-chat-square-text display-1 text-secondary opacity-25"></i>
                            <p class="lead mt-3">Select a conversation to start chatting</p>
                        </div>
                    </div>

                    <!-- Reply / Parent Message Preview -->
                    <div class="bg-secondary-subtle border-top d-none px-3 py-2" id="replyPreview">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-dark">Replying to <b id="replyToUser">User</b>: <span
                                    class="text-muted text-truncate d-inline-block" id="replyToPreview"
                                    style="max-width: 300px;"></span></small>
                            <button class="btn-close btn-sm" id="cancelReply" type="button"></button>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="card-footer border-top bg-white py-3" id="inputArea" style="display: none !important;">
                        <form id="sendMessageForm">
                            <div class="input-group">
                                <button class="btn btn-light text-secondary border" id="attachFileBtn" title="Attach File"
                                    type="button">
                                    <i class="bi bi-paperclip"></i>
                                </button>
                                <input class="d-none" id="attachmentInput" name="attachment" type="file">
                                <input autocomplete="off" class="form-control border-start-0 border-end-0" id="messageInput"
                                    placeholder="Type a message..." type="text">
                                <button class="btn btn-primary px-4" id="sendBtn" type="submit">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                            <small class="text-muted d-none ms-2 mt-1" id="filePreviewName"><i
                                    class="bi bi-file-earmark-check"></i> <span></span> <i
                                    class="bi bi-x text-danger cursor-pointer" id="removeFile"></i></small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Conversation Modal -->
    <div class="modal fade" id="createConversationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-chat-plus"></i> New Conversation</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="createConversationForm">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Title / Topic</label>
                            <input class="form-control" name="title" placeholder="e.g., Project Update" required
                                type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Description</label>
                            <textarea class="form-control" name="description" placeholder="Brief description..." required rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Participants</label>
                            <select class="form-select" id="conversationParticipants" multiple name="recipients[]"
                                required size="5">
                                @foreach ($potentialRecipients as $u)
                                    @if ($u->id !== Auth::id())
                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="form-text">Hold Ctrl/Cmd to select multiple users.</div>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" type="submit">Create Chat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Re-initialize Select2 specifically for this modal to ensure proper width and behavior
            $('#createConversationModal').on('shown.bs.modal', function() {
                $('#conversationParticipants').select2({
                    dropdownParent: $('#createConversationModal'),
                    width: '100%',
                    placeholder: "Select participants...",
                    allowClear: true
                });
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // State
            let currentConversationId = null;
            let pollingInterval = null;
            let replyToMessageId = null;
            const currentUserId = {{ Auth::id() }};

            // --- 1. Load Conversations ---
            function loadConversations() {
                $.get("{{ route('messages.conversations') }}", function(data) {
                    if (data.success) {
                        renderConversations(data.conversations);
                        $("#loadingConversations").remove();
                    }
                });
            }

            loadConversations();
            // Poll for list updates occassionally (every 30s)
            setInterval(loadConversations, 30000);

            function renderConversations(conversations) {
                const list = $("#conversationsList");
                const currentCount = list.children().length;

                // Only re-render if count changed or to update unread status
                // For simplicity in this v1, we re-render but try to keep active state
                list.empty();

                if (conversations.length === 0) {
                    list.html('<div class="text-center p-4 text-muted small">No conversations yet.</div>');
                    return;
                }

                conversations.forEach(c => {
                    const isActive = c.id === currentConversationId ? 'active' : '';
                    const unreadBadge = c.unread_count > 0 ?
                        `<span class="badge bg-danger rounded-pill ms-2">${c.unread_count}</span>` : '';
                    const lastMsg = c.messages.length > 0 ? c.messages[0].content || 'Attachment' :
                        'No messages yet';
                    const date = new Date(c.updated_at).toLocaleDateString([], {
                        month: 'short',
                        day: 'numeric'
                    });

                    const deleteBtn = (c.creator_id === currentUserId) ? `
                        <div class="position-absolute top-0 end-0 p-2">
                            <button class="btn btn-sm text-danger delete-conversation-btn" data-id="${c.id}" title="Delete Chat" style="z-index: 10;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    ` : '';

                    const item = `
                    <li class="list-group-item conversation-item ${isActive} p-3 border-0 border-bottom position-relative" data-id="${c.id}" data-title="${c.title}">
                         ${deleteBtn}
                        <div class="d-flex justify-content-between align-items-start mb-1 pe-4">
                            <h6 class="mb-0 text-truncate" style="max-width: 70%;">${c.title}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">${date}</small>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted text-truncate d-block" style="max-width: 80%;">${lastMsg}</small>
                            ${unreadBadge}
                        </div>
                    </li>
                `;
                    list.append(item);
                });
            }

            // Delete Conversation Handler
            $(document).on('click', '.delete-conversation-btn', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent triggering the conversation selection

                if (!confirm(
                        "Are you sure you want to delete this conversation? This will remove you from the participant list."
                    )) return;

                const id = $(this).data('id');
                const $item = $(this).closest('.conversation-item');

                $.ajax({
                    url: `/agent/messages/api/delete-conversation/${id}`,
                    type: 'DELETE',
                    success: function(resp) {
                        if (resp.success) {
                            $item.remove();
                            if (currentConversationId == id) {
                                $("#closeChatBtn").click(); // Close if active
                            }
                        } else {
                            alert("Error: " + resp.error);
                        }
                    }
                });
            });

            // --- 2. Switch Conversation / Close Chat ---
            $("#closeChatBtn").click(function() {
                $("#chatHeader, #inputArea").attr("style", "display: none !important");
                $("#messagesPanel").html(`
                    <div class="align-self-center text-muted my-auto p-5 text-center" id="emptyState">
                        <i class="bi bi-chat-square-text display-1 text-secondary opacity-25"></i>
                        <p class="lead mt-3">Select a conversation to start chatting</p>
                    </div>
                `);

                $(".conversation-item").removeClass("active");
                currentConversationId = null;
                if (pollingInterval) clearInterval(pollingInterval);
            });

            $(document).on('click', '.conversation-item', function() {
                const id = $(this).data('id');
                const title = $(this).data('title');

                if (currentConversationId === id) return;

                currentConversationId = id;
                $(".conversation-item").removeClass("active");
                $(this).addClass("active");

                // UI Updates
                $("#emptyState").hide();
                $("#chatHeader").attr("style", "display: flex !important"); // Force flex
                $("#inputArea").attr("style", "display: block !important");
                $("#activeConversationTitle").text(title);
                $("#messagesPanel").html(
                    '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>'
                );

                // Find participants from pre-loaded data or just generic text for now
                // Adding a small delay to simulate loading for UX smoothness if needed
                fetchMessages();

                // Start polling for this chat
                if (pollingInterval) clearInterval(pollingInterval);
                pollingInterval = setInterval(fetchMessages, 5000);
            });

            function fetchMessages() {
                if (!currentConversationId) return;

                $.get(`/agent/messages/api/conversation/${currentConversationId}`, function(data) {
                    if (data.success) {
                        renderMessages(data.messages);
                    }
                });
            }

            function renderMessages(messages) {
                const panel = $("#messagesPanel");
                panel.empty(); // Simple clear & redraw (Optimization: Append only new in v2)

                if (messages.length === 0) {
                    panel.html('<div class="text-center text-muted mt-5">No messages yet. Say hello! 👋</div>');
                    return;
                }

                let lastDate = null;

                messages.forEach(msg => {
                    const isMe = msg.sender_id === currentUserId;
                    const alignClass = isMe ? 'message-sent' : 'message-received';
                    const alignFlex = isMe ? 'justify-content-end' : 'justify-content-start';
                    const prettyTime = new Date(msg.created_at).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    // Date Divider
                    const msgDate = new Date(msg.created_at).toDateString();
                    if (msgDate !== lastDate) {
                        panel.append(
                            `<div class="text-center small text-muted my-3"><span class="bg-white px-2 rounded-pill border">${msgDate}</span></div>`
                        );
                        lastDate = msgDate;
                    }

                    // Attachments
                    let attachmentHtml = '';
                    if (msg.attachment) {
                        if (msg.message_type === 'image') {
                            attachmentHtml =
                                `<div class="attachment-preview"><a href="/storage/${msg.attachment}" target="_blank"><img src="/storage/${msg.attachment}" alt="Image"></a></div>`;
                        } else {
                            attachmentHtml =
                                `<div class="mt-2"><a href="/storage/${msg.attachment}" target="_blank" class="btn btn-sm btn-light border"><i class="bi bi-file-earmark-arrow-down"></i> Download File</a></div>`;
                        }
                    }

                    // Reply Reference
                    let replyHtml = '';
                    if (msg.parent_message) {
                        replyHtml = `
                        <div class="small bg-white bg-opacity-25 p-1 rounded mb-1 border-start border-4 border-warning">
                             <b>${msg.parent_message.sender.name}:</b> ${msg.parent_message.content || 'Attachment'}
                        </div>
                    `;
                    }

                    // Delete Button (Only for me)
                    const deleteBtn = isMe ?
                        `<span class="ms-2 text-danger cursor-pointer delete-msg-btn" data-id="${msg.id}" title="Delete" style="cursor:pointer;">&times;</span>` :
                        '';

                    // Reply Button
                    const replyAction =
                        `<i class="bi bi-reply-fill ms-2 cursor-pointer reply-msg-btn text-muted" data-id="${msg.id}" data-user="${msg.sender.name}" data-content="${msg.content || 'Attachment'}" title="Reply" style="cursor:pointer; font-size: 0.9rem;"></i>`;

                    const html = `
                    <div class="d-flex w-100 ${alignFlex} mb-3">
                        <div class="message-bubble ${alignClass} shadow-sm" style="min-width: 150px;">
                            ${replyHtml}
                            <div class="fw-bold mb-1 small">${isMe ? 'You' : msg.sender.name}</div>
                            <div class="message-content" style="white-space: pre-wrap;">${msg.content || ''}</div>
                            ${attachmentHtml}
                            <div class="message-meta d-flex justify-content-end align-items-center">
                                ${prettyTime}
                                ${replyAction}
                                ${deleteBtn}
                            </div>
                        </div>
                    </div>
                `;
                    panel.append(html);
                });

                // Scroll to bottom (only if wasn't scrolled up - enhancement for later, for now auto-scroll)
                // panel.scrollTop(panel[0].scrollHeight);
            }

            // --- 3. Send Message ---
            $("#sendMessageForm").on("submit", function(e) {
                e.preventDefault();
                const content = $("#messageInput").val().trim();
                const file = $("#attachmentInput")[0].files[0];

                if (!content && !file) return;

                const formData = new FormData();
                formData.append('conversation_id', currentConversationId);
                formData.append('content', content);
                if (file) formData.append('attachment', file);
                if (replyToMessageId) formData.append('parent_message_id', replyToMessageId);

                // Disable button & Show Loader
                const $btn = $("#sendBtn");
                const originalContent = $btn.html();
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                );

                $.ajax({
                    url: "{{ route('messages.send') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $("#messageInput").val('');
                            resetAttachment();
                            resetReply();
                            fetchMessages(); // Refresh immediately
                        } else {
                            alert("Error: " + response.error);
                        }
                    },
                    error: function(xhr) {
                        alert("Request failed: " + (xhr.statusText || "Unknown error"));
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('<i class="bi bi-send-fill"></i>');
                    }
                });
            });

            // --- 4. Attachments ---
            $("#attachFileBtn").click(function() {
                $("#attachmentInput").click();
            });

            $("#attachmentInput").change(function() {
                if (this.files.length > 0) {
                    $("#filePreviewName span").text(this.files[0].name);
                    $("#filePreviewName").removeClass("d-none");
                }
            });

            $("#removeFile").click(function() {
                resetAttachment();
            });

            function resetAttachment() {
                $("#attachmentInput").val('');
                $("#filePreviewName").addClass("d-none");
            }

            // --- 5. Replies ---
            $(document).on('click', '.reply-msg-btn', function() {
                replyToMessageId = $(this).data('id');
                const user = $(this).data('user');
                const snippet = $(this).data('content');

                $("#replyToUser").text(user);
                $("#replyToPreview").text(snippet);
                $("#replyPreview").removeClass('d-none');
                $("#messageInput").focus();
            });

            $("#cancelReply").click(function() {
                resetReply();
            });

            function resetReply() {
                replyToMessageId = null;
                $("#replyPreview").addClass('d-none');
            }

            // --- 6. Create Conversation ---
            $("#createConversationForm").on('submit', function(e) {
                e.preventDefault();
                const data = $(this).serialize();

                $.post("{{ route('messages.create') }}", data, function(response) {
                    if (response.success) {
                        $("#createConversationModal").modal('hide');
                        $("#createConversationForm")[0].reset();
                        loadConversations();
                    } else {
                        alert("Error: " + response.error);
                    }
                });
            });

            // --- 7. Search ---
            $("#searchConversations").on("keyup", function() {
                const val = $(this).val().toLowerCase();
                $("#conversationsList li").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
                });
            });

            // --- 8. Delete Message ---
            $(document).on('click', '.delete-msg-btn', function() {
                if (!confirm("Delete this message?")) return;
                const id = $(this).data('id');

                $.ajax({
                    url: `/agent/messages/api/delete/${id}`,
                    type: 'DELETE',
                    success: function(resp) {
                        if (resp.success) fetchMessages();
                    }
                });
            });
        });
    </script>
@endsection
