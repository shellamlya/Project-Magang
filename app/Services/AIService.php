<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Generate promotional description for business places using AI or template engine.
     *
     * @param string $name
     * @param string $category
     * @param string|null $district
     * @param array $facilities
     * @return string
     */
    public static function generateDescription(string $name, string $category = 'penginapan', ?string $district = 'Gresik', array $facilities = []): string
    {
        $apiKey = env('GEMINI_API_KEY');

        $facilitiesText = !empty($facilities) ? implode(', ', $facilities) : 'fasilitas terbaik';
        $locationText = $district ? "Kecamatan {$district}, Kabupaten Gresik" : 'Kabupaten Gresik';

        $prompt = "Buatkan deskripsi promosi yang sangat menarik, ramah, dan profesional untuk tempat usaha berikut di Gresik:\n"
            . "- Nama Tempat: {$name}\n"
            . "- Kategori: {$category}\n"
            . "- Lokasi: {$locationText}\n"
            . "- Fasilitas Utama: {$facilitiesText}\n"
            . "Buat deskripsi dalam 2-3 paragraf singkat dalam Bahasa Indonesia yang mengajak wisatawan untuk berkunjung atau menginap. Jangan sertakan judul markdown.";

        if ($apiKey) {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($aiText) {
                        return trim($aiText);
                    }
                }
            } catch (\Exception $e) {
                Log::warning('AI Service API call failed, fallback to AI template engine: ' . $e->getMessage());
            }
        }

        // AI Copywriting Smart Template Fallback
        return self::fallbackGenerate($name, $category, $locationText, $facilitiesText);
    }

    /**
     * Smart local copywriter fallback when external API key is absent or unreachable.
     */
    private static function fallbackGenerate(string $name, string $category, string $location, string $facilities): string
    {
        $categoryLabels = [
            'penginapan' => 'tempat penginapan nyaman dan strategis',
            'nongkrong'  => 'destinasi kuliner dan tempat nongkrong favorit',
            'wisata'     => 'destinasi wisata unggulan'
        ];

        $catLabel = $categoryLabels[$category] ?? 'tempat usaha pilihan';

        return "Selamat datang di {$name}, {$catLabel} yang berlokasi di {$location}. Kami hadir untuk memberikan pengalaman terbaik dan tak terlupakan bagi setiap pengunjung yang datang ke Kabupaten Gresik.\n\n"
            . "Dilengkapi dengan berbagai keunggulan dan fasilitas seperti {$facilities}, {$name} menjadi pilihan tepat bagi Anda yang ingin menikmati kenyamanan, pelayanan ramah, serta suasana yang menyenangkan.\n\n"
            . "Jangan lewatkan kesempatan untuk merencanakan kunjungan Anda bersama keluarga maupun kolega. Kami siap menyambut kedatangan Anda di {$name}!";
    }
}
