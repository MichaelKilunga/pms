<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisposedStock extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'stock_id',
        'pharmacy_id',
        'removed_date',
        'expired_quantity',
        'removed_by',
        'approved_by',
        'status',
        'approved_at',
        'rejection_reason'
    ];

    protected $casts = [
        'removed_date' => 'datetime',
        'approved_at' => 'datetime'
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    public function removedBy()
    {
        return $this->belongsTo(User::class, 'removed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeForPharmacy($query, $pharmacyId)
    {
        return $query->where('pharmacy_id', $pharmacyId);
    }
}
