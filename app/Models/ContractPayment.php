<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'amount_to_pay',
        'discount',
        'discount_percentage',
        'paid_amount',
        'payment_date',
    ];

    protected $casts = [
        'amount_to_pay' => 'decimal:2',
        'discount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
