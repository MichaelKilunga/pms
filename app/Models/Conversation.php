<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class Conversation extends Model implements Auditable
{
    use HasFactory;
    use  AuditingTrait;

    protected $fillable = [
        'title',
        'description',
        'status',
        'creator_id',
    ];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',  // Soft deletes, if applicable
        'saved',     // General save event
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
