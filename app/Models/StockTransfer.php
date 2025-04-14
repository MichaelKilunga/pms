<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'from_pharmacy_id',
        'to_pharmacy_id',
        'to_pharmacy_name',
        'to_pharmacy_tin',
        'quantity',
        'transfer_date',
        'notes',
        'status',
        'transferred_by',
        'received_by',
    ];

    protected $dates = ['transfer_date'];

    // Relationships
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function fromPharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'from_pharmacy_id');
    }

    public function toPharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'to_pharmacy_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
}
