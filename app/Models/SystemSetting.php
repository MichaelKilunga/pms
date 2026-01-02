<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'description', 'pharmacy_id'];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
}
