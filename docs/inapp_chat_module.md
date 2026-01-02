# In-App Chat Module Documentation

This document describes the implementation of the In-App Chat Module, designed to simplify messaging between agents, owners, and system administrators.

## Features
- **Real-time-like Messaging**: Polling-based architecture ensures messages appear within seconds.
- **File Attachments**: Support for images and documents.
- **Responsive Design**: Bootstrap 5 based mobile-friendly interface.
- **Group Chats**: Ability to create conversations with multiple participants.

## Technical Implementation

### Controller
**`App\Http\Controllers\MessageController`**
Handles the core logic for:
- Fetching conversations (`fetchConversations`).
- Retrieving message history (`fetchMessages`).
- Handling file uploads and storage (`store`).
- Creating new chat threads (`createConversation`).

### Routes
Located in `routes/web.php` under `agent/messages` prefix:
```php
Route::get('agent/messages', [\App\Http\Controllers\MessageController::class, 'index']);
Route::prefix('agent/messages/api')->group(function () {
    // API endpoints for AJAX interactions
});
```

### Database Schema
The module relies on the following tables:
1. `conversations`: Stores thread metadata (title, creator_id).
2. `conversation_user`: Pivot table for participants.
3. `messages`: Stores content, attachments, and timestamps.
4. `message_user_read`: Pivot table for read receipts.

### Frontend
- **View**: `resources/views/agent/messages.blade.php`
- **Logic**: jQuery-based AJAX calls for smoother interactivity without full page reloads.

## How to Port to Another Project

1. **Copy Migrations**: Ensure you have the migrations for `conversations` and `messages`.
2. **Copy Controller**: Move `MessageController.php` to `App\Http\Controllers`.
3. **Copy Models**: Move `Message.php` and `Conversation.php` to `App\Models`.
4. **Update Routes**: Add the routes snippet (above) to `web.php`.
5. **Copy View**: Move `messages.blade.php` to your desired view folder.
6. **Frontend Dependencies**: Ensure the layout includes Bootstrap 5, Bootstrap Icons (`bi-*`), and jQuery.

## Configuration
- **File Uploads**: Ensure `php.ini` allows sufficient `upload_max_filesize`.
- **Storage**: Run `php artisan storage:link` to enable public access to attachments.
