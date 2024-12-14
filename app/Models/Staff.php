<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'pharmacy_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class,'pharmacy_id');
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
