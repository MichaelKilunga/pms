<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class SalesReturn extends Model implements Auditable
{
    use HasFactory;
    use AuditingTrait;

    protected $fillable = [
        'sale_id',
        'pharmacy_id',
        'staff_id',
        'quantity',
        'refund_amount',
        'reason',
        'return_status',
        'approved_by',
        'date',
    ];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',  // Soft deletes, if applicable
        'saved',     // General save event
    ];

    /**
     * Get the sale that owns the sales return.
     */
    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }

    /**
     * Get the staff that processed the sales return.
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get the user who approved the sales return.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the pharmacy associated with the sales return.
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}
