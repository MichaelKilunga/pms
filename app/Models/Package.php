<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration',
        'features',
        'status',
    ];

    protected $casts = [
        'features' => 'array', // Automatically cast JSON data to array
    ];
    
    public function pharmacies(){
        return $this->hasMany(Pharmacy::class);
    }
}

