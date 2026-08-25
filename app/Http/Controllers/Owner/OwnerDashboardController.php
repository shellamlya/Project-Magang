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

    /**
     * Menampilkan daftar Tempat Usaha yang belum diklaim (Unclaimed) dari data awal Disparekrafbudpora.
     */
    public function claimIndex(Request $request)
    {
        $search = $request->query('search');

        $lodgings = Lodging::where('status_claim', 'unclaimed')->whereNull('owner_id');
        $tourists = TouristPlace::where('status_claim', 'unclaimed')->whereNull('owner_id');
        $hangouts = HangoutPlace::where('status_claim', 'unclaimed')->whereNull('owner_id');

        if ($search) {
            $lodgings->where('name', 'like', "%{$search}%");
            $tourists->where('name', 'like', "%{$search}%");
            $hangouts->where('name', 'like', "%{$search}%");
        }

        $unclaimedLodgings = $lodgings->get()->map(function ($item) {
            $item->place_category = 'penginapan';
            $item->category_label = 'Penginapan';
            return $item;
        });

        $unclaimedTourists = $tourists->get()->map(function ($item) {
            $item->place_category = 'wisata';
            $item->category_label = 'Tempat Wisata';
            return $item;
        });

        $unclaimedHangouts = $hangouts->get()->map(function ($item) {
            $item->place_category = 'nongkrong';
            $item->category_label = 'Kafe / Nongkrong';
            return $item;
        });

        $unclaimedPlaces = $unclaimedLodgings->concat($unclaimedTourists)->concat($unclaimedHangouts);

        return view('owner.claim', compact('unclaimedPlaces', 'search'));
    }

    /**
     * Memproses pengajuan klaim tempat usaha oleh Owner.
     */
    public function claimSubmit(Request $request, $category, $id)
    {
        $owner = Auth::user()->owner;
        if (!$owner) {
            return redirect()->route('home')->with('error', 'Profil Owner tidak ditemukan.');
        }

        if ($category === 'nongkrong') {
            $place = HangoutPlace::where('id', $id)->where('status_claim', 'unclaimed')->firstOrFail();
        } elseif ($category === 'wisata') {
            $place = TouristPlace::where('id', $id)->where('status_claim', 'unclaimed')->firstOrFail();
        } else {
            $place = Lodging::where('id', $id)->where('status_claim', 'unclaimed')->firstOrFail();
        }

        $place->update([
            'owner_id'     => $owner->id,
            'status_claim' => 'claimed',
            'status'       => 'pending', // Perlu verifikasi admin
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Klaim tempat usaha berhasil diajukan! Status pengajuan kini Pending dan menunggu verifikasi Admin.');
    }
}
