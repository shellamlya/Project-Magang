<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Lodging
 * @package App\Models
 * Model Entitas Utama Penginapan (Modul Shella).
 */
class Lodging extends Model
{
    use HasFactory;

    protected $table = 'lodgings';

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'operational_hours',
        'check_in',
        'check_out',
        'address',
        'district',
        'village',
        'postal_code',
        'latitude',
        'longitude',
        'google_maps',
        'manager_name',
        'email',
        'phone',
        'website',
        'price_start',
        'price_end',
        'thumbnail',
        'status',
    ];

    protected $casts = [
        'price_start' => 'decimal:2',
        'price_end'   => 'decimal:2',
    ];

    /**
     * Relasi BelongsTo ke Owner.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     * Relasi Many-to-Many ke LodgingFacility.
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(LodgingFacility::class, 'lodging_place_facility', 'lodging_id', 'facility_id')
                    ->withTimestamps();
    }

    /**
     * Relasi HasMany ke VerificationLog.
     */
    public function verificationLogs(): HasMany
    {
        return $this->hasMany(VerificationLog::class);
    }

    /**
     * Scope Query untuk hanya menampilkan penginapan yang disetujui (Approved).
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope Query untuk penginapan yang masih pending.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope Query untuk penginapan yang ditolak.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
