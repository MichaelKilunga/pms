<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'creator_id',
    ];

    // Relationship: One conversation has many messages
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Users participating in the conversation
    public function participants()
    {
        return $this->belongsToMany(User::class, 'conversations_users', 'conversation_id', 'user_id')
            ->withTimestamps();
    }

    // Relationship: One conversation has one creator
    public function creator(){
        return $this->belongsTo(User::class, 'creator_id');
    }
}
