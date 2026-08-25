<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lodging;
use App\Models\TouristPlace;
use App\Models\HangoutPlace;
use Illuminate\Support\Facades\DB;

class PlaceApiController extends Controller
{
    /**
     * Helper to retrieve model class based on category string.
     */
    private function getModelClass(?string $category)
    {
        switch (strtolower($category ?? '')) {
            case 'wisata':
            case 'tourist_place':
                return TouristPlace::class;
            case 'nongkrong':
            case 'hangout_place':
                return HangoutPlace::class;
            case 'penginapan':
            case 'lodging':
            default:
                return Lodging::class;
        }
    }

    /**
     * GET /api/places
     * Menampilkan daftar tempat usaha dengan filter kategori, kecamatan, dan pencarian.
     */
    public function index(Request $request)
    {
        $category = $request->query('category');
        $district = $request->query('district');
        $search   = $request->query('search');

        if ($category && in_array(strtolower($category), ['penginapan', 'wisata', 'nongkrong'])) {
            $modelClass = $this->getModelClass($category);
            $query = $modelClass::with('facilities')->approved();

            if ($district) {
                $query->where('district', 'like', "%{$district}%");
            }
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $places = $query->latest()->paginate(15);
            return response()->json([
                'status'  => 'success',
                'category' => $category,
                'data'    => $places
            ]);
        }

        // Jika kategori tidak dispesifikasikan, gabungkan dari ketiga tabel
        $lodgings = Lodging::with('facilities')->approved()->latest()->get()->map(function ($item) {
            $item->category = 'penginapan';
            return $item;
        });

        $tourists = TouristPlace::with('facilities')->approved()->latest()->get()->map(function ($item) {
            $item->category = 'wisata';
            return $item;
        });

        $hangouts = HangoutPlace::with('facilities')->approved()->latest()->get()->map(function ($item) {
            $item->category = 'nongkrong';
            return $item;
        });

        $allPlaces = $lodgings->concat($tourists)->concat($hangouts)->sortByDesc('created_at')->values();

        return response()->json([
            'status' => 'success',
            'data'   => $allPlaces
        ]);
    }

    /**
     * POST /api/places
     * Menambahkan tempat usaha baru via REST API.
     */
    public function store(Request $request)
    {
        $category = $request->input('category', 'penginapan');
        $modelClass = $this->getModelClass($category);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'required|string',
            'operational_hours' => 'nullable|string',
            'address'           => 'nullable|string',
            'district'          => 'nullable|string',
            'village'           => 'nullable|string',
            'google_maps'       => 'nullable|string',
            'manager_name'      => 'nullable|string',
            'phone'             => 'nullable|string',
            'email'             => 'nullable|email',
            'price_start'       => 'nullable|numeric',
            'price_end'         => 'nullable|numeric',
            'ticket_price'      => 'nullable|string',
        ]);

        $place = $modelClass::create(array_merge($validated, [
            'status' => 'pending',
        ]));

        return response()->json([
            'status'  => 'success',
            'message' => 'Tempat usaha berhasil ditambahkan via API.',
            'data'    => $place
        ], 201);
    }

    /**
     * GET /api/places/{id}
     */
    public function show(Request $request, $id)
    {
        $category = $request->query('category', 'penginapan');
        $modelClass = $this->getModelClass($category);

        $place = $modelClass::with('facilities')->find($id);

        if (!$place) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tempat tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $place
        ]);
    }

    /**
     * PUT /api/places/{id}
     */
    public function update(Request $request, $id)
    {
        $category = $request->input('category', 'penginapan');
        $modelClass = $this->getModelClass($category);

        $place = $modelClass::find($id);

        if (!$place) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tempat tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'name'              => 'sometimes|string|max:255',
            'description'       => 'sometimes|string',
            'operational_hours' => 'nullable|string',
            'address'           => 'nullable|string',
            'district'          => 'nullable|string',
            'phone'             => 'nullable|string',
        ]);

        $place->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data tempat berhasil diperbarui.',
            'data'    => $place
        ]);
    }

    /**
     * DELETE /api/places/{id}
     */
    public function destroy(Request $request, $id)
    {
        $category = $request->query('category', 'penginapan');
        $modelClass = $this->getModelClass($category);

        $place = $modelClass::find($id);

        if (!$place) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tempat tidak ditemukan.'
            ], 404);
        }

        $place->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data tempat berhasil dihapus.'
        ]);
    }

    /**
     * POST /api/places/{id}/track-view
     */
    public function trackView(Request $request, $id)
    {
        $category = $request->input('category', 'penginapan');
        $modelClass = $this->getModelClass($category);

        $place = $modelClass::find($id);
        if (!$place) {
            return response()->json(['status' => 'error', 'message' => 'Place not found'], 404);
        }

        $place->increment('views_count');

        // Log analytics event
        DB::table('place_analytics_logs')->insert([
            'place_type' => strtolower(class_basename($modelClass)),
            'place_id'   => $id,
            'event_type' => 'view',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status'      => 'success',
            'views_count' => $place->views_count
        ]);
    }

    /**
     * POST /api/places/{id}/track-map-click
     */
    public function trackMapClick(Request $request, $id)
    {
        $category = $request->input('category', 'penginapan');
        $modelClass = $this->getModelClass($category);

        $place = $modelClass::find($id);
        if (!$place) {
            return response()->json(['status' => 'error', 'message' => 'Place not found'], 404);
        }

        $place->increment('maps_clicks_count');

        // Log analytics event
        DB::table('place_analytics_logs')->insert([
            'place_type' => strtolower(class_basename($modelClass)),
            'place_id'   => $id,
            'event_type' => 'map_click',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status'            => 'success',
            'maps_clicks_count' => $place->maps_clicks_count
        ]);
    }
}
