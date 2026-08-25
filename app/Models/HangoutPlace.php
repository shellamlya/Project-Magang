<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HangoutPlace extends Model
{
    use HasFactory;

    protected $table = 'hangout_places';

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'operational_hours',
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
        'status',
        'is_verified_official',
        'status_claim',
        'views_count',
        'maps_clicks_count',
    ];

    /**
     * Scope query untuk status disetujui (approved).
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope query untuk status pending.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope query untuk status ditolak.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Relasi ke Owner (pemilik usaha jika ada).
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    /**
     * Relasi Many-to-Many ke HangoutFacility.
     */
    public function facilities()
    {
        return $this->belongsToMany(HangoutFacility::class, 'hangout_place_facility', 'hangout_place_id', 'facility_id');
    }
}
