<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LodgingFacility extends Model
{
    use HasFactory;

    protected $table = 'lodging_facilities';

    protected $fillable = [
        'facility_name',
    ];

    /**
     * Relasi Many-to-Many ke Lodging (Penginapan).
     */
    public function lodgings(): BelongsToMany
    {
        return $this->belongsToMany(Lodging::class, 'lodging_place_facility', 'facility_id', 'lodging_id')
                    ->withTimestamps();
    }
}
