<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class Pharmacy extends Model implements Auditable
{
    use HasFactory;
    use AuditingTrait;
    
    protected $fillable = [
        'user_id',
        'name',
        'location',
        'status',
        'owner_id',
        'package_id',
        'agent_id',
        'config',
        'agent_extra_charge',
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',  // Soft deletes, if applicable
        'saved',     // General save event
    ];

    protected $casts = [
        'config' => 'array',
        'agent_extra_charge' => 'decimal:2',
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
