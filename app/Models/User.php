<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingTrait;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens;
    use  AuditingTrait;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'phone',
        'password',
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',  // Soft deletes, if applicable
        'saved',     // General save event
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pharmaciesOwned()
    {
        return $this->hasMany(Pharmacy::class, 'owner_id');
    }

    public function manage()
    {
        return $this->hasMany(Pharmacy::class, 'owner_id');
    }

    public function pharmacies()
    {
        return $this->hasMany(Pharmacy::class, 'owner_id');
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'owner_id');
    }

    public function saleNote()
    {
        return $this->hasMany(SaleNote::class, 'staff_id');
    }

    public function ownerCurrentContract()
    {
        return $this->hasOne(Contract::class, 'owner_id')->where('is_current_contract', true);
    }

    public function agent()
    {
        return $this->hasMany(Pharmacy::class, 'agent_id');
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversations_users', 'user_id', 'conversation_id')
            ->withTimestamps();  // Optional, if you want to store created_at and updated_at
    }

    public function readMessages(){
        return $this->belongsToMany(Message::class, 'message_user_read', 'user_id', 'message_id')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    public function isAgent()
    {
        return $this->hasOne(Agent::class, 'user_id');
    }
}
