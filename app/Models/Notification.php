<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class Notification extends Model implements Auditable
{
    use HasFactory;
    use AuditingTrait;

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',  // Soft deletes, if applicable
        'saved',     // General save event
    ];
}
