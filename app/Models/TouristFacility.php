<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristFacility extends Model
{
    use HasFactory;

    protected $table = 'tourist_facilities';

    protected $fillable = [
        'facility_name',
    ];

    /**
     * Relasi Many-to-Many ke TouristPlace.
     */
    public function touristPlaces()
    {
        return $this->belongsToMany(TouristPlace::class, 'tourist_place_facility', 'facility_id', 'tourist_place_id');
    }
}
