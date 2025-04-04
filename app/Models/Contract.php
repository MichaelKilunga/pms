<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class Contract extends Model implements Auditable
{
    use HasFactory;
    use AuditingTrait;


    protected $fillable = [
        'owner_id',
        'package_id',
        'start_date',
        'end_date',
        'status',
        'grace_end_date',
        'payment_status',
        'is_current_contract',
    ];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',  // Soft deletes, if applicable
        'saved',     // General save event
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}


