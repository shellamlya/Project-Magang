<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Lodging;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminDashboardController
 * @package App\Http\Controllers\Admin
 * Pengendali Dashboard Statistik dan Grafik untuk Administrator GREX.
 */
class AdminDashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Admin beserta Statistik & Visualisasi Grafik Chart.js.
     */
    public function index()
    {
        // 1. Total statistik utama
        $totalOwners   = Owner::count();
        $totalLodgings = Lodging::count();
        $totalPending  = Lodging::pending()->count();
        $totalApproved = Lodging::approved()->count();
        $totalRejected = Lodging::rejected()->count();

        // 2. Data Grafik Pengajuan per Bulan (6 bulan terakhir)
        $monthlyLabels = [];
        $monthlyCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyLabels[] = $month->translatedFormat('F Y');
            $monthlyCounts[] = Lodging::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // 3. Data Grafik Distribusi Kecamatan Penginapan
        $districtStats = Lodging::select('district', DB::raw('count(*) as total'))
            ->whereNotNull('district')
            ->groupBy('district')
            ->get();

        $categoryLabels = $districtStats->pluck('district')->toArray();
        $categoryCounts = $districtStats->pluck('total')->toArray();

        // 4. Data Penginapan Terbaru (5 data terakhir)
        $recentLodgings = Lodging::with('owner.user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOwners',
            'totalLodgings',
            'totalPending',
            'totalApproved',
            'totalRejected',
            'monthlyLabels',
            'monthlyCounts',
            'categoryLabels',
            'categoryCounts',
            'recentLodgings'
        ));
    }
}
