<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lodging;

/**
 * Class OwnerDashboardController
 * @package App\Http\Controllers\Owner
 * Pengendali Dashboard statistik dan daftar status pengajuan penginapan milik Owner.
 */
class OwnerDashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Owner.
     */
    public function index()
    {
        $user = Auth::user();
        $owner = $user->owner;

        if (!$owner) {
            return redirect()->route('home')->with('error', 'Profil Owner tidak ditemukan.');
        }

        // Statistik Penginapan Milik Owner
        $totalLodgings  = $owner->lodgings()->count();
        $totalApproved  = $owner->lodgings()->where('status', 'approved')->count();
        $totalPending   = $owner->lodgings()->where('status', 'pending')->count();
        $totalRejected  = $owner->lodgings()->where('status', 'rejected')->count();

        // Daftar Penginapan Milik Owner
        $lodgings = $owner->lodgings()->with('facilities')->latest()->get();

        return view('owner.dashboard', compact(
            'owner',
            'totalLodgings',
            'totalApproved',
            'totalPending',
            'totalRejected',
            'lodgings'
        ));
    }
}
