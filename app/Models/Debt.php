<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = ['stock_id', 'debtAmount', 'status'];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    // Helper: total paid
    public function totalPaid()
    {
        return $this->installments()->sum('amount');
    }
}
