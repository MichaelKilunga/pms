<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id', 'pharmacy_id', 'item_id', 'quantity','remain_Quantity','low_stock_percentage', 'buying_price', 'selling_price', 'in_date','expire_date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class,'staff_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class,'pharmacy_id');
    }

    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }
    
    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}
