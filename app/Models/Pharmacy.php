<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pharmacy extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'location', 'owner_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->hasMany(Category::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function item()
    {
        return $this->hasMany(Items::class);
    }
    
    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
    
    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}
