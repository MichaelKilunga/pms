<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class Package extends Model implements Auditable
{
    use HasFactory;
    use AuditingTrait;

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',  // Soft deletes, if applicable
        'saved',     // General save event
    ];
    
    protected $fillable = [
        'name',
        'price',
        'duration',
        'status',
        'number_of_pharmacies',
        'number_of_pharmacists',
        'number_of_medicines',
        'in_app_notification',
        'email_notification',
        'sms_notifications',
        'online_support',
        'number_of_owner_accounts',
        'number_of_admin_accounts',
        'reports',
        'stock_transfer',
        'stock_management',
        'staff_management',
        'receipts',
        'analytics',
        'whatsapp_chats',
    ];
    
    public function pharmacies(){
        return $this->hasMany(Pharmacy::class);
    }
    
    public function contracts(){
        return $this->hasMany(Contract::class);
    }
}

