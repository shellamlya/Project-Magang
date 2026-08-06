<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Village
 * @package App\Models
 * Model Kelurahan/Desa di Kabupaten Gresik.
 */
class Village extends Model
{
    use HasFactory;

    protected $fillable = ['district_id', 'name'];

    /**
     * Relasi BelongsTo ke District.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Relasi HasMany ke Lodging.
     */
    public function lodgings(): HasMany
    {
        return $this->hasMany(Lodging::class);
    }
}
