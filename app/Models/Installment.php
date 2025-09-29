<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = ['pharmacy_id','debt_id', 'amount','description'];
      public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id'); // adjust model name if different
    }
    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }
}
