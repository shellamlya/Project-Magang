<?php

namespace App\Models\Traits;

trait HasInstagram
{
    /**
     * Dapatkan handle Instagram dengan format @username (contoh: @kopisenja).
     * Jika field kosong, mengembalikan null.
     */
    public function getInstagramHandleAttribute(): ?string
    {
        if (empty($this->instagram) || trim($this->instagram) === '') {
            return null;
        }

        $username = $this->extractInstagramUsername($this->instagram);
        return $username ? '@' . $username : null;
    }

    /**
     * Dapatkan URL link Instagram yang valid dan dapat diklik (contoh: https://www.instagram.com/kopisenja/).
     * Jika field kosong, mengembalikan null.
     */
    public function getInstagramUrlAttribute(): ?string
    {
        if (empty($this->instagram) || trim($this->instagram) === '') {
            return null;
        }

        $username = $this->extractInstagramUsername($this->instagram);
        if ($username) {
            return "https://www.instagram.com/{$username}/";
        }

        $raw = trim($this->instagram);
        if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
            return $raw;
        }

        $clean = ltrim($raw, '@/ ');
        return $clean !== '' ? 'https://www.instagram.com/' . $clean . '/' : null;
    }

    /**
     * Ekstrak username Instagram dari berbagai kemungkinan variasi input Owner:
     * - @contohusaha
     * - contohusaha
     * - https://www.instagram.com/contohusaha
     * - https://www.instagram.com/contohusaha/
     * - https://instagram.com/contohusaha
     * - http://instagram.com/contohusaha/
     * - instagram.com/contohusaha
     * - www.instagram.com/contohusaha/
     * - https://www.instagram.com/contohusaha/?igsh=...
     */
    public function extractInstagramUsername(?string $input): ?string
    {
        if (empty($input)) {
            return null;
        }

        $input = trim($input);

        // Jika berbentuk URL Instagram lengkap (dengan query params opsional)
        if (preg_match('#(?:https?://)?(?:www\.)?instagram\.com/([a-zA-Z0-9_.]+)/?#i', $input, $matches)) {
            return trim($matches[1], '/');
        }

        // Jika berbentuk @username atau username langsung
        $clean = trim(ltrim($input, '@'), '/ ');

        return $clean !== '' ? $clean : null;
    }
}
