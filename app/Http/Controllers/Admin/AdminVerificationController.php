<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Lodging;
use App\Models\TouristPlace;
use App\Models\HangoutPlace;
use App\Models\Owner;
use App\Models\VerificationLog;

/**
 * Class AdminVerificationController
 * @package App\Http\Controllers\Admin
 *
 * Pengendali verifikasi Dua Tingkat (Double Verification) oleh Admin:
 *   Verifikasi 1: Validasi Akun Owner (account_status)
 *   Verifikasi 2: Validasi Listing Tempat Usaha (status) untuk semua tipe
 */
class AdminVerificationController extends Controller
{
    // ── VERIFIKASI LISTING (Tingkat 2) ─────────────────────────────────────────

    /**
     * Daftar semua pengajuan listing (Penginapan + Wisata + Kafe) untuk verifikasi.
     * Filter by: type (category), status.
     */
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', null);
        $typeFilter   = $request->query('type', null);

        // Lodgings
        $lodgingsQuery = Lodging::with(['owner.user']);
        if ($statusFilter) $lodgingsQuery->where('status', $statusFilter);
        $lodgings = $lodgingsQuery->get()->map(fn($l) => $l->setAttribute('place_type', 'penginapan'));

        // Tourist Places
        $touristQuery = TouristPlace::with(['owner.user']);
        if ($statusFilter) $touristQuery->where('status', $statusFilter);
        $tourists = $touristQuery->get()->map(fn($t) => $t->setAttribute('place_type', 'wisata'));

        // Hangout Places
        $hangoutQuery = HangoutPlace::with(['owner.user']);
        if ($statusFilter) $hangoutQuery->where('status', $statusFilter);
        $hangouts = $hangoutQuery->get()->map(fn($h) => $h->setAttribute('place_type', 'nongkrong'));

        // Gabungkan semua listing, filter by type jika ada
        $allListings = collect()
            ->concat($lodgings)
            ->concat($tourists)
            ->concat($hangouts);

        if ($typeFilter && in_array($typeFilter, ['penginapan', 'wisata', 'nongkrong'])) {
            $allListings = $allListings->where('place_type', $typeFilter)->values();
        }

        // Sort: pending di atas
        $allListings = $allListings->sortBy(function ($item) {
            return match ($item->status) {
                'pending'  => 0,
                'approved' => 1,
                'rejected' => 2,
                default    => 3,
            };
        })->values();

        // Statistik ringkasan
        $stats = [
            'total_pending'  => $allListings->where('status', 'pending')->count(),
            'total_approved' => $allListings->where('status', 'approved')->count(),
            'total_rejected' => $allListings->where('status', 'rejected')->count(),
        ];

        return view('admin.verifications.index', compact('allListings', 'stats', 'statusFilter', 'typeFilter'));
    }

    /**
     * Detail satu listing untuk diperiksa Admin sebelum approve/reject.
     * Mendukung semua tipe: penginapan, wisata, nongkrong.
     */
    public function show(Request $request, $id)
    {
        $type = $request->query('type', 'penginapan');

        $place = match ($type) {
            'wisata'    => TouristPlace::with(['owner.user', 'facilities'])->findOrFail($id),
            'nongkrong' => HangoutPlace::with(['owner.user', 'facilities'])->findOrFail($id),
            default     => Lodging::with(['owner.user', 'facilities'])->findOrFail($id),
        };

        // Ambil log verifikasi terdahulu via polymorphic
        $verificationLogs = VerificationLog::where('verifiable_type', get_class($place))
            ->where('verifiable_id', $place->id)
            ->with('admin')
            ->latest()
            ->get();

        return view('admin.verifications.show', compact('place', 'type', 'verificationLogs'));
    }

    /**
     * Approve listing tempat usaha (Verifikasi Tingkat 2: SETUJU).
     * Mendukung: lodging, tourist_place, hangout_place.
     */
    public function approve(Request $request, $id)
    {
        $admin = Auth::user();
        $type  = $request->input('type', 'penginapan');

        $place = $this->findPlace($type, $id);

        $place->update([
            'status'              => 'approved',
            'listing_verified_at' => now(),
            'listing_verified_by' => $admin->id,
            'listing_rejection_reason' => null,
        ]);

        // Catat di verification_logs (polymorphic)
        VerificationLog::create([
            'verifiable_type' => get_class($place),
            'verifiable_id'   => $place->id,
            'admin_id'        => $admin->id,
            'action'          => 'approve',
            'status'          => 'approved',
            'notes'           => $request->input('notes', 'Listing telah disetujui dan dipublikasikan.'),
        ]);

        return redirect()
            ->route('admin.verifications.index')
            ->with('success', 'Listing "' . $place->name . '" telah DISETUJUI dan sekarang tayang secara publik.');
    }

    /**
     * Reject listing tempat usaha (Verifikasi Tingkat 2: TOLAK).
     * Alasan penolakan wajib diisi agar owner bisa memperbaikinya.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisikan agar owner dapat memperbaikinya.',
            'rejection_reason.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $admin = Auth::user();
        $type  = $request->input('type', 'penginapan');

        $place = $this->findPlace($type, $id);

        $place->update([
            'status'                   => 'rejected',
            'listing_verified_at'      => now(),
            'listing_verified_by'      => $admin->id,
            'listing_rejection_reason' => $request->rejection_reason,
        ]);

        // Catat di verification_logs (polymorphic)
        VerificationLog::create([
            'verifiable_type' => get_class($place),
            'verifiable_id'   => $place->id,
            'admin_id'        => $admin->id,
            'action'          => 'reject',
            'status'          => 'rejected',
            'notes'           => $request->rejection_reason,
        ]);

        return redirect()
            ->route('admin.verifications.index')
            ->with('success', 'Listing "' . $place->name . '" telah DITOLAK beserta alasan penolakan.');
    }

    // ── VERIFIKASI AKUN OWNER (Tingkat 1) ─────────────────────────────────────

    /**
     * Daftar semua akun Owner yang menunggu verifikasi akun (Tingkat 1).
     */
    public function ownerIndex(Request $request)
    {
        $statusFilter = $request->query('status', 'pending_account');

        $query = Owner::with('user');
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('account_status', $statusFilter);
        }

        $owners = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total_pending'  => Owner::where('account_status', 'pending_account')->count(),
            'total_verified' => Owner::where('account_status', 'account_verified')->count(),
            'total_rejected' => Owner::where('account_status', 'rejected')->count(),
        ];

        return view('admin.verifications.owners', compact('owners', 'stats', 'statusFilter'));
    }

    /**
     * Detail akun Owner untuk diperiksa Admin (termasuk foto KTP & No WA).
     */
    public function ownerShow($id)
    {
        $owner = Owner::with(['user', 'accountVerifiedBy'])->findOrFail($id);

        $verificationLogs = VerificationLog::where('verifiable_type', Owner::class)
            ->where('verifiable_id', $owner->id)
            ->with('admin')
            ->latest()
            ->get();

        return view('admin.verifications.owner-show', compact('owner', 'verificationLogs'));
    }

    /**
     * Tampilkan / stream foto KTP owner secara aman (hanya untuk Admin terotentikasi).
     */
    public function viewKtp($id)
    {
        $owner = Owner::findOrFail($id);
        if (!$owner->ktp_photo) {
            abort(404, 'File KTP belum diunggah.');
        }

        if (Storage::disk('local')->exists($owner->ktp_photo)) {
            return Storage::disk('local')->response($owner->ktp_photo);
        } elseif (Storage::disk('public')->exists($owner->ktp_photo)) {
            return Storage::disk('public')->response($owner->ktp_photo);
        }

        abort(404, 'File KTP tidak ditemukan di server penyimpanan.');
    }

    /**
     * Approve akun Owner (Verifikasi Tingkat 1: SETUJU).
     * Owner bisa mulai mendaftarkan listing usaha.
     */
    public function ownerApprove(Request $request, $id)
    {
        $admin = Auth::user();
        $owner = Owner::findOrFail($id);

        $owner->update([
            'account_status'      => Owner::ACCOUNT_VERIFIED,
            'account_verified_at' => now(),
            'account_verified_by' => $admin->id,
            'account_rejection_reason' => null,
        ]);

        // Catat log verifikasi akun (polymorphic)
        VerificationLog::create([
            'verifiable_type' => Owner::class,
            'verifiable_id'   => $owner->id,
            'admin_id'        => $admin->id,
            'action'          => 'approve',
            'status'          => 'approved',
            'notes'           => $request->input('notes', 'Akun owner telah diverifikasi. Owner dapat mendaftarkan tempat usaha.'),
        ]);

        return redirect()
            ->route('admin.owner-verifications.index')
            ->with('success', 'Akun Owner "' . $owner->user->name . '" telah DISETUJUI. Owner dapat mendaftarkan listing usaha.');
    }

    /**
     * Reject akun Owner (Verifikasi Tingkat 1: TOLAK).
     * Alasan wajib diisi, dikirimkan sebagai notifikasi ke owner.
     */
    public function ownerReject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan akun wajib diisikan.',
            'rejection_reason.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $admin = Auth::user();
        $owner = Owner::findOrFail($id);

        $owner->update([
            'account_status'           => Owner::ACCOUNT_REJECTED,
            'account_verified_at'      => now(),
            'account_verified_by'      => $admin->id,
            'account_rejection_reason' => $request->rejection_reason,
        ]);

        VerificationLog::create([
            'verifiable_type' => Owner::class,
            'verifiable_id'   => $owner->id,
            'admin_id'        => $admin->id,
            'action'          => 'reject',
            'status'          => 'rejected',
            'notes'           => $request->rejection_reason,
        ]);

        return redirect()
            ->route('admin.owner-verifications.index')
            ->with('success', 'Akun Owner "' . $owner->user->name . '" telah DITOLAK beserta alasan penolakan.');
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    /**
     * Cari model Place berdasarkan type string dan ID.
     *
     * @param  string $type  'penginapan' | 'wisata' | 'nongkrong'
     * @param  int    $id
     * @return Lodging|TouristPlace|HangoutPlace
     */
    private function findPlace(string $type, int $id)
    {
        return match ($type) {
            'wisata'    => TouristPlace::findOrFail($id),
            'nongkrong' => HangoutPlace::findOrFail($id),
            default     => Lodging::findOrFail($id),
        };
    }
}
