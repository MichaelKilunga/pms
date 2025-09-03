<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'pharmacy_id','name','description','is_active',
    ];

    public function expenses() {
        return $this->hasMany(Expense::class, 'category_id');
    }

      public function pharmacy() {
            return $this->belongsTo(Pharmacy::class, 'pharmacy_id'); // adjust model name if different
        }
}
