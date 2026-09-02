<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class VerificationLog
 * @package App\Models
 * Model Log Histori Verifikasi oleh Admin — versi Polymorphic (v2).
 *
 * Satu tabel ini mencatat semua aksi verifikasi:
 *   - Verifikasi Akun Owner  (verifiable_type = 'App\Models\Owner')
 *   - Verifikasi Listing Penginapan   (verifiable_type = 'App\Models\Lodging')
 *   - Verifikasi Listing Wisata       (verifiable_type = 'App\Models\TouristPlace')
 *   - Verifikasi Listing Kafe         (verifiable_type = 'App\Models\HangoutPlace')
 *
 * Kolom lama (lodging_id, status) tetap dipertahankan untuk backward compatibility.
 */
class VerificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        // Polymorphic fields (baru)
        'verifiable_type',
        'verifiable_id',
        'action',
        // Fields lama (backward compatible)
        'lodging_id',
        'admin_id',
        'status',
        'notes',
    ];

    // ── Relasi ────────────────────────────────────────────────────────────────

    /**
     * Polymorphic: Relasi ke entitas yang diverifikasi
     * (Owner, Lodging, TouristPlace, atau HangoutPlace).
     */
    public function verifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relasi BelongsTo ke User (Admin yang melakukan verifikasi).
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relasi BelongsTo ke Lodging (backward compat — kolom lodging_id lama).
     */
    public function lodging(): BelongsTo
    {
        return $this->belongsTo(Lodging::class);
    }

    // ── Helper Methods ────────────────────────────────────────────────────────

    /**
     * Label human-readable untuk jenis verifikasi ini.
     */
    public function getVerificationTypeLabel(): string
    {
        return match (true) {
            str_contains($this->verifiable_type, 'Owner')        => 'Verifikasi Akun Owner',
            str_contains($this->verifiable_type, 'Lodging')      => 'Verifikasi Listing Penginapan',
            str_contains($this->verifiable_type, 'TouristPlace') => 'Verifikasi Listing Wisata',
            str_contains($this->verifiable_type, 'HangoutPlace') => 'Verifikasi Listing Kafe/Nongkrong',
            default                                               => 'Verifikasi',
        };
    }

    /**
     * Badge CSS class untuk aksi verifikasi (digunakan di Admin Panel view).
     */
    public function getActionBadgeClass(): string
    {
        return $this->action === 'approve' ? 'bg-success' : 'bg-danger';
    }
}
