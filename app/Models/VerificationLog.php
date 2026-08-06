<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class VerificationLog
 * @package App\Models
 * Model Log histori verifikasi oleh Admin.
 */
class VerificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'lodging_id',
        'admin_id',
        'status',
        'notes',
    ];

    /**
     * Relasi BelongsTo ke Lodging.
     */
    public function lodging(): BelongsTo
    {
        return $this->belongsTo(Lodging::class);
    }

    /**
     * Relasi BelongsTo ke User (Admin).
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
