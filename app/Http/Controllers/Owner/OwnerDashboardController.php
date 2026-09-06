<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
     * Tampilan status akun pending verifikasi admin (tanpa dashboard sidebar).
     */
    public function pendingVerification()
    {
        $user = Auth::user();
        $owner = $user?->owner;

        if ($owner && $owner->verification_status === 'approved') {
            return redirect()->route('owner.dashboard')->with('success', 'Akun Anda sudah disetujui!');
        }

        return view('owner.pending', compact('owner'));
    }

    /**
     * Tampilan status akun ditolak verifikasi admin (tanpa dashboard sidebar).
     */
    public function rejectedVerification()
    {
        $user = Auth::user();
        $owner = $user?->owner;

        if ($owner && $owner->verification_status === 'approved') {
            return redirect()->route('owner.dashboard')->with('success', 'Akun Anda sudah disetujui!');
        }

        return view('owner.rejected', compact('owner'));
    }

    /**
     * Memproses perbaikan data & pengajuan ulang KTP dari owner yang ditolak.
     */
    public function reApply(Request $request)
    {
        $user = Auth::user();
        $owner = $user?->owner;

        if (!$owner) {
            return redirect()->route('home')->with('error', 'Profil Owner tidak ditemukan.');
        }

        // Validasi input
        $request->validate([
            'ktp_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nik'       => 'nullable|numeric|digits:16',
            'address'   => 'nullable|string|max:500',
        ], [
            'ktp_photo.required' => 'Foto KTP wajib diunggah ulang.',
            'ktp_photo.image'    => 'Berkas harus berupa gambar.',
            'ktp_photo.mimes'    => 'Format gambar KTP harus jpeg, png, atau jpg.',
            'ktp_photo.max'      => 'Ukuran gambar KTP maksimal 2 MB.',
            'nik.digits'         => 'NIK harus berjumlah 16 digit angka.',
        ]);

        // Hapus foto KTP lama dari storage private jika ada
        if ($owner->ktp_photo && Storage::disk('private')->exists($owner->ktp_photo)) {
            Storage::disk('private')->delete($owner->ktp_photo);
        }

        // Simpan foto KTP baru ke disk private
        $ktpPath = $request->file('ktp_photo')->store('ktp_photos', 'private');
        $owner->ktp_photo = $ktpPath;

        // Update NIK & Alamat jika diisi
        if ($request->filled('nik')) {
            $owner->nik = $request->nik;
        }
        if ($request->filled('address')) {
            $owner->address = $request->address;
        }

        // Kembalikan status ke pending
        $owner->verification_status = 'pending';
        $owner->account_status = 'pending_account';
        $owner->save();

        return redirect()->route('owner.pending-verification')
            ->with('success', 'Perbaikan data berhasil dikirim! Akun Anda sedang ditinjau ulang oleh admin.');
    }
}