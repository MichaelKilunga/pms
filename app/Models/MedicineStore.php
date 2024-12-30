<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineStore extends Model
{
    protected $fillable = [
        'name','status'
    ];
    public function stocks(){
        return $this->hasMany(Stock::class);
    }
}
