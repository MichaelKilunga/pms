<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class PrinterSetting extends Model implements Auditable
{
    use HasFactory;
    use AuditingTrait;
    
    // Schema::create('printer_settings', function (Blueprint $table) {
    //     $table->id();
    //     $table->string('name');
    //     $table->string('ip_address');
    //     $table->string('port')->nullable();
    //     $table->foreignId('pharmacy_id')->constrained('pharmacies')->onDelete('cascade');
    //     $table->timestamps();
    // });
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',  // Soft deletes, if applicable
        'saved',     // General save event
    ];
    protected $fillable = [
        'name',
        'ip_address',
        'port',
        'pharmacy_id',
        'computer_name',
        'use_printer',
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
