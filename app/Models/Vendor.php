<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'tin',
        'address',
        'city',
        'country',
        'is_active',
        'pharmacy_id'
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }
}
