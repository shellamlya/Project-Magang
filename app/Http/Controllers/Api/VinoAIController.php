<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Lodging;
use App\Models\TouristPlace;
use App\Models\HangoutPlace;

/**
 * Class VinoAIController
 * @package App\Http\Controllers\Api
 *
 * Endpoint API khusus untuk VinoAI Chatbot.
 * Menyediakan data kontekstual terstruktur dari semua listing aktif
 * dalam format JSON yang siap dikonsumsi oleh Large Language Model (LLM).
 *
 * Keamanan: Endpoint ini diamankan dengan API Key header (X-VinoAI-Key).
 * Konfigurasi: Atur VINO_AI_SECRET_KEY di .env
 *
 * Contoh request:
 *   GET /api/vino/context?type=all&limit=50
 *   GET /api/vino/context/penginapan/1
 *   GET /api/vino/search?q=kafe+murah&district=Kecamatan+X
 */
class VinoAIController extends Controller
{
    /**
     * Ambil semua listing aktif untuk context data VinoAI.
     * Digunakan untuk initial load / full context fetch oleh chatbot.
     *
     * Query params:
     *   type  = all | penginapan | wisata | kafe  (default: all)
     *   limit = jumlah record per tipe (default: 100)
     */
    public function index(Request $request): JsonResponse
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['error' => 'Unauthorized. API Key tidak valid.'], 401);
        }

        $type  = $request->query('type', 'all');
        $limit = (int) $request->query('limit', 100);

        $data = [];

        if ($type === 'all' || $type === 'penginapan') {
            $data['penginapan'] = Lodging::with(['facilities'])
                ->where('status', 'approved')
                ->latest()
                ->limit($limit)
                ->get()
                ->map(fn($l) => $l->toVinoAiContext())
                ->values();
        }

        if ($type === 'all' || $type === 'wisata') {
            $data['wisata'] = TouristPlace::with(['facilities'])
                ->where('status', 'approved')
                ->latest()
                ->limit($limit)
                ->get()
                ->map(fn($t) => $t->toVinoAiContext())
                ->values();
        }

        if ($type === 'all' || $type === 'kafe') {
            $data['kafe'] = HangoutPlace::with(['facilities'])
                ->where('status', 'approved')
                ->latest()
                ->limit($limit)
                ->get()
                ->map(fn($h) => $h->toVinoAiContext())
                ->values();
        }

        $meta = [
            'generated_at'  => now()->toIso8601String(),
            'type_filter'   => $type,
            'total_records' => collect($data)->flatten(1)->count(),
        ];

        return response()->json([
            'success' => true,
            'meta'    => $meta,
            'data'    => $data,
        ]);
    }

    /**
     * Ambil context satu listing spesifik berdasarkan tipe dan ID.
     *
     * GET /api/vino/context/{type}/{id}
     * type: penginapan | wisata | kafe
     */
    public function show(Request $request, string $type, int $id): JsonResponse
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        $place = match ($type) {
            'wisata'    => TouristPlace::with(['facilities'])->where('status', 'approved')->find($id),
            'kafe'      => HangoutPlace::with(['facilities'])->where('status', 'approved')->find($id),
            'penginapan'=> Lodging::with(['facilities'])->where('status', 'approved')->find($id),
            default     => null,
        };

        if (!$place) {
            return response()->json(['error' => 'Listing tidak ditemukan atau belum aktif.'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $place->toVinoAiContext(),
        ]);
    }

    /**
     * Pencarian full-text berbasis query teks & filter lokasi.
     * VinoAI menggunakan endpoint ini untuk menjawab query user.
     *
     * GET /api/vino/search?q=kafe+cozy&district=Kecamatan+Pacitan&type=kafe
     *
     * Query params:
     *   q        = kata kunci pencarian (nama, deskripsi, alamat)
     *   type     = penginapan | wisata | kafe | all (default: all)
     *   district = nama kecamatan (filter opsional)
     *   village  = nama desa/kelurahan (filter opsional)
     */
    public function search(Request $request): JsonResponse
    {
        if (!$this->isAuthorized($request)) {
            return response()->json(['error' => 'Unauthorized.'], 401);
        }

        $query    = $request->query('q', '');
        $type     = $request->query('type', 'all');
        $district = $request->query('district');
        $village  = $request->query('village');

        $results = collect();

        // Helper closure untuk query pencarian
        $searchQuery = function ($model) use ($query, $district, $village) {
            $q = $model::with(['facilities'])->where('status', 'approved');

            if ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%")
                        ->orWhere('address', 'LIKE', "%{$query}%")
                        ->orWhere('ai_summary', 'LIKE', "%{$query}%");
                });
            }

            if ($district) {
                $q->where('district', 'LIKE', "%{$district}%");
            }
            if ($village) {
                $q->where('village', 'LIKE', "%{$village}%");
            }

            return $q->limit(20)->get();
        };

        if ($type === 'all' || $type === 'penginapan') {
            $results = $results->concat($searchQuery(Lodging::class)->map(fn($l) => $l->toVinoAiContext()));
        }
        if ($type === 'all' || $type === 'wisata') {
            $results = $results->concat($searchQuery(TouristPlace::class)->map(fn($t) => $t->toVinoAiContext()));
        }
        if ($type === 'all' || $type === 'kafe') {
            $results = $results->concat($searchQuery(HangoutPlace::class)->map(fn($h) => $h->toVinoAiContext()));
        }

        return response()->json([
            'success' => true,
            'meta'    => [
                'query'        => $query,
                'type_filter'  => $type,
                'district'     => $district,
                'village'      => $village,
                'total_results'=> $results->count(),
                'generated_at' => now()->toIso8601String(),
            ],
            'data'    => $results->values(),
        ]);
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    /**
     * Validasi API Key dari request header.
     * VinoAI harus mengirim header: X-VinoAI-Key: {nilai dari .env VINO_AI_SECRET_KEY}
     */
    private function isAuthorized(Request $request): bool
    {
        $expectedKey = config('app.vino_ai_secret_key', env('VINO_AI_SECRET_KEY'));

        // Jika tidak ada key yang dikonfigurasi, tolak semua request
        if (empty($expectedKey)) {
            return false;
        }

        $providedKey = $request->header('X-VinoAI-Key');

        return $providedKey === $expectedKey;
    }
}
