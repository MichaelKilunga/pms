<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id', // Foreign key to conversations
        'sender_id',       // User who sent the message
        'content',         // The actual message text
        'status',          // 'unread', 'read', 'deleted'
        'subject',         // Optional subject of the message
        'attachment',      // Optional attachment ( file path )
        'message_type',    // 'text', 'image', 'file', 'voice'
        'is_urgent',       // Boolean indicating if the message is urgent
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
}
