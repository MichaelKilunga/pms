<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{

    protected $fillable = [
        'expense_date','pharmacy_id','category_id','vendor_id',
        'amount','payment_method','payment_reference','mobile_wallet','bank_account',
        'status','created_by','approved_by','approved_at',
        'reference_no','description','meta'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'approved_at' => 'datetime',
        'meta' => 'array',
        'amount' => 'decimal:2',
    ];

        public function pharmacy() {
            return $this->belongsTo(Pharmacy::class, 'pharmacy_id'); // adjust model name if different
        }

    public function category() {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver() {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function attachments() {
        return $this->hasMany(ExpenseAttachment::class);
    }

    
}
