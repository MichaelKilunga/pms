<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pharmacy extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'location',
        'owner_id',
        'package_id',
        'status',
        'agent_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
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

    public function printerSetting()
    {
        return $this->hasOne(PrinterSetting::class);
    }

    public function saleNote()
    {
        return $this->hasMany(SaleNote::class, 'pharmacy_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
