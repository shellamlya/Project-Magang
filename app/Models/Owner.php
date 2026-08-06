<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Owner
 * @package App\Models
 * Model untuk data Profil Pemilik Penginapan (Owner).
 */
class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nik',
        'company_name',
        'address',
        'phone',
        'status',
    ];

    /**
     * Relasi BelongsTo ke User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi HasMany ke Lodging (Penginapan milik owner ini).
     */
    public function lodgings(): HasMany
    {
        return $this->hasMany(Lodging::class);
    }
}
