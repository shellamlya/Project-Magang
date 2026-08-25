<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lodging;
use App\Models\District;
use App\Models\TouristPlace;
use App\Models\HangoutPlace;

/**
 * Class PlaceController
 * @package App\Http\Controllers\User
 * Pengendali halaman kategori tempat (Penginapan, Wisata, Nongkrong) dan Halaman Detail.
 */
class PlaceController extends Controller
{
    /**
     * Menampilkan daftar Penginapan menggunakan skema database lodgings.
     */
    public function penginapan(Request $request)
    {
        $query = Lodging::with('facilities')->approved();

        // Filter Kecamatan
        if ($request->filled('district')) {
            $districtParam = $request->district;
            $query->where(function($q) use ($districtParam) {
                $q->where('district', $districtParam)
                  ->orWhere('district', 'like', "%{$districtParam}%");
            });
        }

        // Filter Kata Kunci / Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('manager_name', 'like', "%{$search}%");
            });
        }

        $places = $query->latest()->paginate(9)->withQueryString();
        $districts = District::orderBy('name', 'asc')->get();

        return view('user.penginapan', compact('places', 'districts'));
    }

    /**
     * Menampilkan detail tempat penginapan berdasarkan ID.
     */
    public function showLodging($id)
    {
        $lodging = Lodging::with('facilities')->findOrFail($id);

        // Rekomendasi penginapan lainnya
        $otherLodgings = Lodging::with('facilities')
            ->approved()
            ->where('id', '!=', $lodging->id)
            ->latest()
            ->take(3)
            ->get();

        // Rekomendasi tempat wisata terdekat
        $nearbyWisata = TouristPlace::with('facilities')
            ->approved()
            ->latest()
            ->take(3)
            ->get();

        return view('user.penginapan-detail', compact('lodging', 'otherLodgings', 'nearbyWisata'));
    }

    /**
     * Menampilkan daftar Wisata menggunakan skema database tourist_places.
     */
    public function wisata(Request $request)
    {
        $query = TouristPlace::with('facilities')->approved();

        // Filter Kecamatan
        if ($request->filled('district')) {
            $districtParam = $request->district;
            $query->where(function($q) use ($districtParam) {
                $q->where('district', $districtParam)
                  ->orWhere('district', 'like', "%{$districtParam}%");
            });
        }

        // Filter Kata Kunci / Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('manager_name', 'like', "%{$search}%");
            });
        }

        $places = $query->latest()->paginate(9)->withQueryString();
        $districts = District::orderBy('name', 'asc')->get();

        return view('user.wisata', compact('places', 'districts'));
    }

    /**
     * Menampilkan detail tempat wisata berdasarkan ID.
     */
    public function showWisata($id)
    {
        $touristPlace = TouristPlace::with('facilities')->findOrFail($id);

        // Rekomendasi tempat wisata lainnya
        $otherWisata = TouristPlace::with('facilities')
            ->approved()
            ->where('id', '!=', $touristPlace->id)
            ->latest()
            ->take(3)
            ->get();

        // Rekomendasi penginapan terdekat
        $nearbyLodgings = Lodging::with('facilities')
            ->approved()
            ->latest()
            ->take(3)
            ->get();

        return view('user.wisata-detail', compact('touristPlace', 'otherWisata', 'nearbyLodgings'));
    }

    /**
     * Menampilkan daftar Tempat Nongkrong menggunakan skema database hangout_places.
     */
    public function nongkrong(Request $request)
    {
        $query = HangoutPlace::with('facilities')->approved();

        // Filter Kecamatan
        if ($request->filled('district')) {
            $districtParam = $request->district;
            $query->where(function($q) use ($districtParam) {
                $q->where('district', $districtParam)
                  ->orWhere('district', 'like', "%{$districtParam}%");
            });
        }

        // Filter Kata Kunci / Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('manager_name', 'like', "%{$search}%");
            });
        }

        $places = $query->latest()->paginate(9)->withQueryString();
        $districts = District::orderBy('name', 'asc')->get();

        return view('user.nongkrong', compact('places', 'districts'));
    }

    /**
     * Menampilkan detail tempat nongkrong berdasarkan ID.
     */
    public function showHangout($id)
    {
        $hangoutPlace = HangoutPlace::with('facilities')->findOrFail($id);

        // Rekomendasi tempat nongkrong lainnya
        $otherNongkrong = HangoutPlace::with('facilities')
            ->approved()
            ->where('id', '!=', $hangoutPlace->id)
            ->latest()
            ->take(3)
            ->get();

        // Rekomendasi penginapan terdekat
        $nearbyLodgings = Lodging::with('facilities')
            ->approved()
            ->latest()
            ->take(3)
            ->get();

        return view('user.nongkrong-detail', compact('hangoutPlace', 'otherNongkrong', 'nearbyLodgings'));
    }

    /**
     * Fallback show handler.
     */
    public function show($id)
    {
        return $this->showLodging($id);
    }
}
