<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class User
 * @package App\Models
 * Model Autentikasi Pengguna Sistem (Admin, Owner, User Publik).
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi BelongsTo ke Role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relasi HasOne ke Owner (jika user bertipe owner).
     */
    public function owner(): HasOne
    {
        return $this->hasOne(Owner::class);
    }

    /**
     * Relasi HasMany ke VerificationLog (sebagai Admin yang melakukan verifikasi).
     */
    public function verificationLogs(): HasMany
    {
        return $this->hasMany(VerificationLog::class, 'admin_id');
    }

    /**
     * Helper check role Admin
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    /**
     * Helper check role Owner
     */
    public function isOwner(): bool
    {
        return $this->role && $this->role->name === 'owner';
    }

    /**
     * Helper check role User Publik
     */
    public function isUser(): bool
    {
        return $this->role && $this->role->name === 'user';
    }
}
