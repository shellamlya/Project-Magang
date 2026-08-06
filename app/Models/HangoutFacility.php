<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HangoutFacility extends Model
{
    use HasFactory;

    protected $table = 'hangout_facilities';

    protected $fillable = [
        'facility_name',
    ];

    /**
     * Relasi Many-to-Many ke HangoutPlace.
     */
    public function hangoutPlaces()
    {
        return $this->belongsToMany(HangoutPlace::class, 'hangout_place_facility', 'facility_id', 'hangout_place_id');
    }
}
