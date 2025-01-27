<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrinterSetting extends Model
{
    // Schema::create('printer_settings', function (Blueprint $table) {
    //     $table->id();
    //     $table->string('name');
    //     $table->string('ip_address');
    //     $table->string('port')->nullable();
    //     $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
    //     $table->timestamps();
    // });

    protected $fillable = [
        'name',
        'ip_address',
        'port',
        'pharmacy_id',
    ];

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function getPortAttribute($value)
    {
        return $value ?? 9100;
    }

    public function setPortAttribute($value)
    {
        $this->attributes['port'] = $value ?? 9100;
    }
}
