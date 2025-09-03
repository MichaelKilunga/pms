<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseAttachment extends Model
{
    protected $fillable = [
        'expense_id','file_path','original_name','mime_type','size_bytes'
    ];

    public function expense() {
        return $this->belongsTo(Expense::class);
    }
}
