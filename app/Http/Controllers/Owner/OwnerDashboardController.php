<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lodging;
use App\Models\HangoutPlace;
use App\Models\TouristPlace;
use Illuminate\Support\Facades\DB;

/**
 * Class OwnerDashboardController
 * @package App\Http\Controllers\Owner
 * Pengendali Dashboard statistik, analitik pengunjung, dan klaim tempat usaha.
 */
class OwnerDashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Owner dengan Statistik & Analitik.
     */
    public function index()
    {
        $user = Auth::user();
        $owner = $user->owner;

        if (!$owner) {
            return redirect()->route('home')->with('error', 'Profil Owner tidak ditemukan.');
        }

        $allPlaces = $owner->allPlacesCollection();

        $totalPlaces   = $allPlaces->count();
        $totalApproved = $allPlaces->where('status', 'approved')->count();
        $totalPending  = $allPlaces->where('status', 'pending')->count();
        $totalRejected = $allPlaces->where('status', 'rejected')->count();

        // Total Analitik Views & Map Clicks (Bulan Ini)
        $totalViews = $allPlaces->sum('views_count');
        $totalMapClicks = $allPlaces->sum('maps_clicks_count');

        // Data Grafik Analitik Per Tempat Usaha
        $analyticsLabels = [];
        $analyticsViewsData = [];
        $analyticsMapsData = [];

        foreach ($allPlaces as $place) {
            $analyticsLabels[] = $place->name;
            $analyticsViewsData[] = (int) ($place->views_count ?? 0);
            $analyticsMapsData[] = (int) ($place->maps_clicks_count ?? 0);
        }

        return view('owner.dashboard', compact(
            'owner',
            'totalPlaces',
            'totalApproved',
            'totalPending',
            'totalRejected',
            'totalViews',
            'totalMapClicks',
            'allPlaces',
            'analyticsLabels',
            'analyticsViewsData',
            'analyticsMapsData'
        ));
    }
}

