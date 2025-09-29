<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class Stock extends Model implements Auditable
{
    use HasFactory;
    use AuditingTrait;

    protected $fillable = [
        'staff_id',
        'pharmacy_id',
        'item_id',
        'quantity',
        'remain_Quantity',
        'low_stock_percentage',
        'buying_price',
        'selling_price',
        'in_date',
        'expire_date',
        'batch_number',
        'supplier',
    ];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',  // Soft deletes, if applicable
        'saved',     // General save event
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }
    public function installments()
    {
        return $this->hasManyThrough(Installment::class, Debt::class);
    }
}
