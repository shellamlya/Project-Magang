<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Lodging;
use App\Models\HangoutPlace;
use App\Models\TouristPlace;
use App\Models\WebsiteVisitor;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Total statistik utama
        $totalOwners   = Owner::count();
        $totalHangouts = HangoutPlace::count();
        $totalLodgings = Lodging::count();
        $totalTours    = TouristPlace::count();
        $totalViews    = WebsiteVisitor::count();
        
        $totalPending  = Lodging::where('status', 'pending')->count() + HangoutPlace::where('status', 'pending')->count() + TouristPlace::where('status', 'pending')->count();
        $totalApproved = Lodging::where('status', 'approved')->count() + HangoutPlace::where('status', 'approved')->count() + TouristPlace::where('status', 'approved')->count();
        $totalRejected = Lodging::where('status', 'rejected')->count() + HangoutPlace::where('status', 'rejected')->count() + TouristPlace::where('status', 'rejected')->count();
// 2. Data Grafik Batang 12 Bulan Penuh (Tahun Berjalan)
        $monthlyLabels = [];
        $lodgingMonthlyCounts = [];
        $hangoutMonthlyCounts = [];
        $touristMonthlyCounts = [];

        for ($i = 1; $i <= 12; $i++) {
            // Membuat objek tanggal untuk setiap bulan di tahun ini
            $date = \Carbon\Carbon::create(date('Y'), $i, 1);
            
            $monthlyLabels[] = $date->translatedFormat('F Y'); // Contoh: Januari 2026, Februari 2026, dst.
            
            $lodgingMonthlyCounts[] = Lodging::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
            $hangoutMonthlyCounts[] = HangoutPlace::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
            $touristMonthlyCounts[] = TouristPlace::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
        }

        // 3. Data 3 Doughnut Chart Sebaran Kecamatan per Kategori
        // A. Penginapan per Kecamatan
        $lodgingDistrict = Lodging::select('district', DB::raw('count(*) as total'))
            ->whereNotNull('district')->groupBy('district')->pluck('total', 'district');
        $lodgingDistrictLabels = $lodgingDistrict->keys()->toArray();
        $lodgingDistrictCounts = $lodgingDistrict->values()->toArray();

        // B. Tempat Nongkrong per Kecamatan
        $hangoutDistrict = HangoutPlace::select('district', DB::raw('count(*) as total'))
            ->whereNotNull('district')->groupBy('district')->pluck('total', 'district');
        $hangoutDistrictLabels = $hangoutDistrict->keys()->toArray();
        $hangoutDistrictCounts = $hangoutDistrict->values()->toArray();

        // C. Wisata per Kecamatan
        $touristDistrict = TouristPlace::select('district', DB::raw('count(*) as total'))
            ->whereNotNull('district')->groupBy('district')->pluck('total', 'district');
        $touristDistrictLabels = $touristDistrict->keys()->toArray();
        $touristDistrictCounts = $touristDistrict->values()->toArray();

        // 4. Data Terbaru
        $recentLodgings = Lodging::with('owner.user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOwners',
            'totalHangouts',
            'totalLodgings',
            'totalTours',
            'totalViews',
            'totalPending',
            'totalApproved',
            'totalRejected',
            'monthlyLabels',
            'lodgingMonthlyCounts',
            'hangoutMonthlyCounts',
            'touristMonthlyCounts',
            'lodgingDistrictLabels', 'lodgingDistrictCounts',
            'hangoutDistrictLabels', 'hangoutDistrictCounts',
            'touristDistrictLabels', 'touristDistrictCounts',
            'recentLodgings'
        ));
    }
}