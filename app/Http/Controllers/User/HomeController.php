<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lodging;
use App\Models\District;
use App\Models\TouristPlace;
use App\Models\HangoutPlace;
use App\Models\WebsiteVisitor;

/**
 * Class HomeController
 * @package App\Http\Controllers\User
 * Pengendali utama Landing Page Lokavino.
 */
class HomeController extends Controller
{
    /**
     * Menampilkan Landing Page dengan Kotak Pencarian Utama, Deskripsi Sistem,
     * Fitur Lokavino, Alur Cara Kerja, dan Rekomendasi Populer (Penginapan, Wisata, Nongkrong).
     */
    public function index(Request $request)
    {
        $searchKeyword  = $request->get('keyword');
        $searchService  = $request->get('service'); // penginapan, wisata, nongkrong, or empty
        $searchDistrict = $request->get('district');

        $searchResults = null;
        if ($request->filled('keyword') || $request->filled('service') || $request->filled('district')) {
            if ($searchService === 'wisata') {
                $query = TouristPlace::with('facilities')->approved();

                if ($request->filled('keyword')) {
                    $kw = $request->keyword;
                    $query->where(function($q) use ($kw) {
                        $q->where('name', 'like', "%{$kw}%")
                          ->orWhere('description', 'like', "%{$kw}%")
                          ->orWhere('manager_name', 'like', "%{$kw}%");
                    });
                }

                if ($request->filled('district')) {
                    $districtObj = District::find($request->district);
                    $districtName = $districtObj ? $districtObj->name : $request->district;
                    $query->where('district', 'like', "%{$districtName}%");
                }

                $searchResults = $query->latest()->paginate(9)->withQueryString();
            } elseif ($searchService === 'nongkrong') {
                $query = HangoutPlace::with('facilities')->approved();

                if ($request->filled('keyword')) {
                    $kw = $request->keyword;
                    $query->where(function($q) use ($kw) {
                        $q->where('name', 'like', "%{$kw}%")
                          ->orWhere('description', 'like', "%{$kw}%")
                          ->orWhere('manager_name', 'like', "%{$kw}%");
                    });
                }

                if ($request->filled('district')) {
                    $districtObj = District::find($request->district);
                    $districtName = $districtObj ? $districtObj->name : $request->district;
                    $query->where('district', 'like', "%{$districtName}%");
                }

                $searchResults = $query->latest()->paginate(9)->withQueryString();
            } else {
                // Default / Penginapan
                $query = Lodging::with('facilities')->approved();

                if ($request->filled('keyword')) {
                    $kw = $request->keyword;
                    $query->where(function($q) use ($kw) {
                        $q->where('name', 'like', "%{$kw}%")
                          ->orWhere('description', 'like', "%{$kw}%")
                          ->orWhere('manager_name', 'like', "%{$kw}%")
                          ->orWhere('address', 'like', "%{$kw}%");
                    });
                }

                if ($request->filled('district')) {
                    $districtObj = District::find($request->district);
                    $districtName = $districtObj ? $districtObj->name : $request->district;
                    $query->where('district', 'like', "%{$districtName}%");
                }

                $searchResults = $query->latest()->paginate(9)->withQueryString();
            }
        }

        // Rekomendasi Populer dari masing-masing Kategori (4 per kategori)
        $popularPenginapan = Lodging::with('facilities')
            ->approved()
            ->latest()
            ->take(4)
            ->get();

        $popularWisata = TouristPlace::with('facilities')
            ->approved()
            ->latest()
            ->take(4)
            ->get();

        $popularNongkrong = HangoutPlace::with('facilities')
            ->approved()
            ->latest()
            ->take(4)
            ->get();

        $districts = District::orderBy('name', 'asc')->get();

        // Total Usaha Terdaftar (Penginapan + Wisata + Nongkrong yang disetujui / aktif publik)
        $totalLodgings = Lodging::approved()->count();
        $totalTouristPlaces = TouristPlace::approved()->count();
        $totalHangoutPlaces = HangoutPlace::approved()->count();
        $totalPlaces = $totalLodgings + $totalTouristPlaces + $totalHangoutPlaces;

        // Total Pengunjung Unik Website
        $totalVisitors = WebsiteVisitor::count();

        return view('user.home', compact(
            'searchResults',
            'popularPenginapan',
            'popularWisata',
            'popularNongkrong',
            'districts',
            'searchKeyword',
            'searchService',
            'searchDistrict',
            'totalPlaces',
            'totalVisitors'
        ));
    }
}
