<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Lodging
 * @package App\Models
 * Model Entitas Utama Penginapan.
 *
 * Kolom Baru (v2):
 *   photo_1                 — Foto tambahan #1 (opsional)
 *   photo_2                 — Foto tambahan #2 (opsional)
 *   ai_summary              — Ringkasan AI untuk VinoAI context
 *   listing_verified_at     — Waktu listing disetujui
 *   listing_verified_by     — Admin yang approve listing
 *   listing_rejection_reason— Alasan penolakan listing
 */
class Lodging extends Model
{
    use HasFactory;

    protected $table = 'lodgings';

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'ai_summary',
        'operational_hours',
        'check_in',
        'check_out',
        'address',
        'district',
        'village',
        'postal_code',
        'latitude',
        'longitude',
        'google_maps',
        'manager_name',
        'email',
        'phone',
        'website',
        'price_start',
        'price_end',
        // Media
        'thumbnail',
        'photo_1',
        'photo_2',
        // Status & Verifikasi
        'status',
        'listing_verified_at',
        'listing_verified_by',
        'listing_rejection_reason',
        'is_verified_official',
        'status_claim',
        'views_count',
        'maps_clicks_count',
    ];

    protected $casts = [
        'price_start'        => 'decimal:2',
        'price_end'          => 'decimal:2',
        'listing_verified_at' => 'datetime',
        'is_verified_official' => 'boolean',
    ];

    // ── Relasi ────────────────────────────────────────────────────────────────

    /**
     * Relasi BelongsTo ke Owner.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     * Admin yang melakukan Verifikasi Listing (Tingkat 2).
     */
    public function listingVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'listing_verified_by');
    }

    /**
     * Relasi Many-to-Many ke LodgingFacility.
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(LodgingFacility::class, 'lodging_place_facility', 'lodging_id', 'facility_id')
                    ->withTimestamps();
    }

    /**
     * Polymorphic relation ke VerificationLog.
     */
    public function verificationLogs(): MorphMany
    {
        return $this->morphMany(VerificationLog::class, 'verifiable');
    }

    // ── Query Scopes ──────────────────────────────────────────────────────────

    /**
     * Scope hanya menampilkan penginapan yang disetujui (Approved).
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope penginapan yang masih pending.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope penginapan yang ditolak.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // ── Helper Methods ────────────────────────────────────────────────────────

    /**
     * Dapatkan URL foto thumbnail tempat.
     * Mendukung URL eksternal (Google/Unsplash) maupun path file lokal storage upload-an owner.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if (!empty($this->thumbnail)) {
            if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
                return $this->thumbnail;
            }
            return asset('storage/' . $this->thumbnail);
        }
        // Fallback placeholder hotel berkualitas tinggi
        return 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80';
    }

    /**
     * Dapatkan URL foto pendukung #1 (opsional).
     */
    public function getPhoto1UrlAttribute(): ?string
    {
        if (!empty($this->photo_1)) {
            if (str_starts_with($this->photo_1, 'http://') || str_starts_with($this->photo_1, 'https://')) {
                return $this->photo_1;
            }
            return asset('storage/' . $this->photo_1);
        }
        return null;
    }

    /**
     * Dapatkan URL foto pendukung #2 (opsional).
     */
    public function getPhoto2UrlAttribute(): ?string
    {
        if (!empty($this->photo_2)) {
            if (str_starts_with($this->photo_2, 'http://') || str_starts_with($this->photo_2, 'https://')) {
                return $this->photo_2;
            }
            return asset('storage/' . $this->photo_2);
        }
        return null;
    }

    /**
     * Dapatkan semua URL foto galeri (thumbnail + photo_1 + photo_2).
     * Berguna untuk galeri detail halaman & VinoAI context.
     */
    public function getGalleryPhotos(): array
    {
        $formatUrl = function(?string $photo) {
            if (empty($photo)) return null;
            if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) {
                return $photo;
            }
            return asset('storage/' . $photo);
        };

        return array_values(array_filter([
            $formatUrl($this->thumbnail),
            $formatUrl($this->photo_1),
            $formatUrl($this->photo_2),
        ]));
    }

    /**
     * Format data listing untuk konsumsi VinoAI context.
     */
    public function toVinoAiContext(): array
    {
        return [
            'place_type'  => 'penginapan',
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'ai_summary'  => $this->ai_summary,
            'location'    => [
                'address'     => $this->address,
                'district'    => $this->district,
                'village'     => $this->village,
                'coordinates' => ['lat' => $this->latitude, 'lng' => $this->longitude],
            ],
            'contact'     => [
                'phone'   => $this->phone,
                'email'   => $this->email,
                'website' => $this->website,
            ],
            'operational' => [
                'hours'     => $this->operational_hours,
                'check_in'  => $this->check_in,
                'check_out' => $this->check_out,
            ],
            'pricing'     => [
                'start' => $this->price_start,
                'end'   => $this->price_end,
            ],
            'facilities'  => $this->facilities->pluck('name')->toArray(),
            'media'       => [
                'thumbnail' => $this->thumbnail_url,
                'gallery'   => $this->getGalleryPhotos(),
            ],
            'analytics'   => [
                'views'       => $this->views_count,
                'maps_clicks' => $this->maps_clicks_count,
            ],
        ];
    }
}
