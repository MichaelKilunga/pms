<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

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
}

