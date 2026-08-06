<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Facility
 * @package App\Models
 * Model Fasilitas Penginapan (WiFi, AC, Kolam Renang, Parkir, dll).
 */
class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
    ];

    /**
     * Relasi Many-to-Many ke Lodging.
     */
    public function lodgings(): BelongsToMany
    {
        return $this->belongsToMany(Lodging::class, 'lodging_facility')
                    ->withTimestamps();
    }
}
