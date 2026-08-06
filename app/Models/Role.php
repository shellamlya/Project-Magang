<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Role
 * @package App\Models
 * Model untuk mengelola data Role pengguna (Admin, Owner, User).
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label'];

    /**
     * Relasi One-to-Many ke User.
     * Satu Role bisa dimiliki oleh banyak User.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
