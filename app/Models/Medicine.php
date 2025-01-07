<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand_name',
        'generic_name',
        'description',
        'status',
        'category',
        'class',
        'dosage_form',
        'strength',
        'manufacturer',
        'manufacturing_country',
    ];
}
