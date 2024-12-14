<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id', 'pharmacy_id', 'item_id', 'quantity', 'total_price', 'date',
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
        return $this->belongsTo(Items::class,'item_id');
    }
}
