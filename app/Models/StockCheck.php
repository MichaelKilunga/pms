<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Items;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class StockCheck extends Model
{
    protected $fillable = ['item_id', 'physical_quantity', 'user_id', 'checked_at'];
    
    public function item()
    {
        return $this->belongsTo(Items::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
