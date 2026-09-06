<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Owner
 * @package App\Models
 * Model untuk data Profil Pemilik Tempat Usaha (Owner).
 *
 * Kolom Baru (v2 — Double Verification):
 *   ktp_photo               — Foto KTP (privat, hanya Admin)
 *   business_phone          — No. WA Bisnis (tampil publik di listing)
 *   account_status          — Status verifikasi akun (Verifikasi Tingkat 1)
 *   account_verified_at     — Waktu akun diverifikasi
 *   account_verified_by     — Admin yang verifikasi akun
 *   account_rejection_reason— Alasan penolakan akun
 */
class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nik',
        'company_name',
        'address',
        'phone',
        'status',
        // v2: Verifikasi Akun (Tingkat 1)
        'ktp_photo',
        'business_phone',
        'account_status',
        'verification_status',
        'account_verified_at',
        'account_verified_by',
        'account_rejection_reason',
    ];

    protected $casts = [
        'account_verified_at' => 'datetime',
    ];

    // ── Konstanta Status Akun ──────────────────────────────────────────────────

    const ACCOUNT_PENDING  = 'pending_account';
    const ACCOUNT_VERIFIED = 'account_verified';
    const ACCOUNT_REJECTED = 'rejected';

    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';

    // ── Relasi Inti ───────────────────────────────────────────────────────────

    /**
     * Relasi BelongsTo ke User (akun login owner).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi BelongsTo ke Admin yang melakukan Verifikasi Akun (Tingkat 1).
     */
    public function accountVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_verified_by');
    }

    /**
     * Relasi HasMany ke Lodging (Penginapan milik owner ini).
     */
    public function lodgings(): HasMany
    {
        return $this->hasMany(Lodging::class);
    }

    /**
     * Relasi HasMany ke TouristPlace (Tempat Wisata milik owner ini).
     */
    public function touristPlaces(): HasMany
    {
        return $this->hasMany(TouristPlace::class);
    }

    /**
     * Relasi HasMany ke HangoutPlace (Tempat Nongkrong milik owner ini).
     */
    public function hangoutPlaces(): HasMany
    {
        return $this->hasMany(HangoutPlace::class);
    }

    /**
     * Polymorphic relation ke VerificationLog untuk log verifikasi akun owner ini.
     */
    public function verificationLogs(): MorphMany
    {
        return $this->morphMany(VerificationLog::class, 'verifiable');
    }

    // ── Helper & Accessor Methods ─────────────────────────────────────────────

    /**
     * Accessor untuk mempermudah akses email owner (via user relation).
     */
    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }

    /**
     * Accessor untuk mempermudah akses nama penanggung jawab owner (via user relation).
     */
    public function getNameAttribute(): ?string
    {
        return $this->user?->name;
    }

    /**
     * Accessor untuk verification_status agar selalu konsisten dengan account_status.
     */
    public function getVerificationStatusAttribute($value): string
    {
        if (!empty($value)) {
            return $value;
        }

        return match ($this->account_status) {
            self::ACCOUNT_VERIFIED => self::STATUS_APPROVED,
            self::ACCOUNT_REJECTED => self::STATUS_REJECTED,
            default => self::STATUS_PENDING,
        };
    }

    /**
     * Cek apakah akun owner sudah disetujui (approved).
     */
    public function isApproved(): bool
    {
        return $this->verification_status === self::STATUS_APPROVED || $this->account_status === self::ACCOUNT_VERIFIED;
    }

    /**
     * Cek apakah akun owner masih berstatus pending.
     */
    public function isPending(): bool
    {
        return $this->verification_status === self::STATUS_PENDING || $this->account_status === self::ACCOUNT_PENDING;
    }

    /**
     * Cek apakah akun owner ditolak (rejected).
     */
    public function isRejected(): bool
    {
        return $this->verification_status === self::STATUS_REJECTED || $this->account_status === self::ACCOUNT_REJECTED;
    }

    /**
     * Cek apakah akun owner sudah terverifikasi (bisa daftar listing).
     */
    public function isAccountVerified(): bool
    {
        return $this->isApproved();
    }

    /**
     * Cek apakah akun owner masih menunggu verifikasi.
     */
    public function isAccountPending(): bool
    {
        return $this->isPending();
    }

    /**
     * Cek apakah akun owner ditolak.
     */
    public function isAccountRejected(): bool
    {
        return $this->isRejected();
    }

    /**
     * Mendapatkan gabungan seluruh tempat usaha milik owner (Penginapan, Wisata, Nongkrong).
     * Digunakan di Owner Dashboard.
     */
    public function allPlacesCollection()
    {
        $lodgings = $this->lodgings()->get()->map(function ($item) {
            $item->place_category      = 'penginapan';
            $item->category_label      = 'Penginapan';
            $item->category_badge_class = 'bg-primary';
            return $item;
        });

        $tourists = $this->touristPlaces()->get()->map(function ($item) {
            $item->place_category      = 'wisata';
            $item->category_label      = 'Tempat Wisata';
            $item->category_badge_class = 'bg-info text-dark';
            return $item;
        });

        $hangouts = $this->hangoutPlaces()->get()->map(function ($item) {
            $item->place_category      = 'nongkrong';
            $item->category_label      = 'Kafe / Nongkrong';
            $item->category_badge_class = 'bg-warning text-dark';
            return $item;
        });

        return $lodgings->concat($tourists)->concat($hangouts)->sortByDesc('created_at')->values();
    }
}
