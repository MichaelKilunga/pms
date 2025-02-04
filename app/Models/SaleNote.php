<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleNote extends Model
{
    /* Schema::create('sale_notes', function (Blueprint $table) {
        $table->id();
        //name
        $table->string('name');
        $table->string('quantity');
        $table->string('unit_price');
        //status
        $table->enum('status', ['promoted', 'Unpromoted','rejected'])->default('Unpromoted');
        //description
        $table->string('description')->nullable();
        //foreign key for pharmacy id
        $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
        //foreign key for staff id
        $table->foreignId('staff_id')->constrained('users')->onDelete('cascade'); // User making the sale
        $table->timestamps();
    });
    */
    protected $fillable = [
        'name',
        'quantity',
        'unit_price',
        'status',
        'description',
        'pharmacy_id',
        'staff_id',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
