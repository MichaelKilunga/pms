<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
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

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}


