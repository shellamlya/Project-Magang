<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class District
 * @package App\Models
 * Model Kecamatan di Kabupaten Gresik.
 */
class District extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relasi HasMany ke Village.
     */
    public function villages(): HasMany
    {
        return $this->hasMany(Village::class);
    }

    /**
     * Relasi HasMany ke Lodging.
     */
    public function lodgings(): HasMany
    {
        return $this->hasMany(Lodging::class);
    }
}
