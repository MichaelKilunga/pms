<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class Items extends Model implements Auditable
{
    use HasFactory;
    use AuditingTrait;

    protected $fillable = [
        'category_id', 'pharmacy_id', 'name',
    ];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',  // Soft deletes, if applicable
        'saved',     // General save event
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
    
    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    // Get the latest stock of this item added to the stock table
    public function lastStock()
    {
        return $this->hasOne(Stock::class, 'item_id')->latestOfMany('created_at');
    }

        // 🔹 Stock check relationship (NEW)
    public function stockChecks()
    {
        return $this->hasMany(Stock::class, 'item_id');
    }

    // 🔹 Latest stock check entry (NEW)
    public function latestStockCheck()
    {
        return $this->hasOne(Stock::class, 'item_id')->latestOfMany('checked_at');
    }

}
