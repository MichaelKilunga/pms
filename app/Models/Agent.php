<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'country',
        'NIN',
        'document_attachment_1_name',
        'document_attachment_2_name',
        'document_attachment_3_name',
        'document_attachment_1',
        'document_attachment_2',
        'document_attachment_3',
        'signed_agreement_form',
        'agent_code',
        'request_date',
        'registration_status',
        'status',
        'verified_by',
        'verified_date',
        'address',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Verifier (User)
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
