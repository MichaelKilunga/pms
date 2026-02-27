<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BroadcastNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'channels',
        'target_criteria',
        'status',
        'user_id',
        'sent_count',
    ];

    protected $casts = [
        'channels' => 'array',
        'target_criteria' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
