<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'pharmacy_id',
        'item_id',
        'staff_id',
        'quantity',
        'refund_amount',
        'reason',
        'return_status',
        'approved_by',
        'date',
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

    /**
     * Get the item associated with the sales return.
     */
    public function item()
    {
        return $this->belongsTo(Items::class);
    }
}
