<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class Message extends Model implements Auditable
{
    use HasFactory;
    use AuditingTrait;

    protected $fillable = [
        'conversation_id', // Foreign key to conversations
        'sender_id',       // User who sent the message
        'content',         // The actual message text
        'status',          // 'unread', 'read', 'deleted'
        'subject',         // Optional subject of the message
        'attachment',      // Optional attachment ( file path )
        'message_type',    // 'text', 'image', 'file', 'voice'
        'is_urgent',       // Boolean indicating if the message is urgent
        'parent_message_id', // Optional parent message ID
    ];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',  // Soft deletes, if applicable
        'saved',     // General save event
    ];

    // Relationship with Conversation
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // Relationship with Sender
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function usersWhoRead()
    {
        return $this->belongsToMany( User::class, 'message_user_read', 'message_id', 'user_id' )
            ->withPivot('read_at')
            ->withTimestamps();
    }

    // Relationship with Parent Message
    public function parentMessage(){
        return $this->belongsTo(Message::class, 'parent_message_id');
    }
}
