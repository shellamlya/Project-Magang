<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Review
 * @package App\Models
 * Model Ulasan/Review pengunjung penginapan.
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'lodging_id',
        'reviewer_name',
        'reviewer_email',
        'rating',
        'comment',
    ];

    /**
     * Relasi BelongsTo ke Lodging.
     */
    public function lodging(): BelongsTo
    {
        return $this->belongsTo(Lodging::class);
    }
}
