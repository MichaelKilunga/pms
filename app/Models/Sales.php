<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class Sales extends Model implements Auditable
{
    use HasFactory;
    use AuditingTrait;

    protected $fillable = [
        'staff_id', 'pharmacy_id', 'item_id', 'quantity', 'total_price', 'date','stock_id',
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
        return $this->belongsTo(Staff::class,'staff_id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class,'stock_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class,'pharmacy_id');
    }

    public function item()
    {
        return $this->belongsTo(Items::class,'item_id');
    }

    public function salesReturn()
    {
        return $this->hasOne(SalesReturn::class, 'sale_id');
    }
}
