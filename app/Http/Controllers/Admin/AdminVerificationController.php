<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lodging;
use App\Models\VerificationLog;
use Illuminate\Support\Facades\Auth;

/**
 * Class AdminVerificationController
 * @package App\Http\Controllers\Admin
 * Pengendali verifikasi data penginapan oleh Admin (Setujui / Tolak dengan Alasan).
 */
class AdminVerificationController extends Controller
{
    /**
     * Menampilkan daftar seluruh pengajuan penginapan untuk verifikasi admin.
     */
    public function index(Request $request)
    {
        $query = Lodging::with(['owner.user', 'facilities']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default tampilkan pending di atas
            $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')");
        }

        $lodgings = $query->latest()->paginate(10)->withQueryString();

        return view('admin.verifications.index', compact('lodgings'));
    }

    /**
     * Menampilkan detail pengajuan penginapan yang perlu diverifikasi.
     */
    public function show($id)
    {
        $lodging = Lodging::with(['owner.user', 'facilities', 'verificationLogs.admin'])->findOrFail($id);

        return view('admin.verifications.show', compact('lodging'));
    }

    /**
     * Menyetujui pengajuan penginapan (Approve).
     */
    public function approve(Request $request, $id)
    {
        $lodging = Lodging::findOrFail($id);
        $admin = Auth::user();

        $lodging->update([
            'status' => 'approved',
        ]);

        // Catat di verification_logs
        VerificationLog::create([
            'lodging_id' => $lodging->id,
            'admin_id'   => $admin->id,
            'status'     => 'approved',
            'notes'      => $request->input('notes', 'Pengajuan penginapan telah disetujui oleh Admin.'),
        ]);

        return redirect()->route('admin.verifications.index')->with('success', 'Penginapan "' . $lodging->name . '" telah berhasil DISETUJUI dan sekarang tayang secara publik.');
    }

    /**
     * Menolak pengajuan penginapan (Reject) beserta alasan penolakan.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisikan agar owner dapat memperbaikinya.',
            'rejection_reason.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $lodging = Lodging::findOrFail($id);
        $admin = Auth::user();

        $lodging->update([
            'status' => 'rejected',
        ]);

        // Catat di verification_logs
        VerificationLog::create([
            'lodging_id' => $lodging->id,
            'admin_id'   => $admin->id,
            'status'     => 'rejected',
            'notes'      => $request->rejection_reason,
        ]);

        return redirect()->route('admin.verifications.index')->with('success', 'Pengajuan penginapan "' . $lodging->name . '" telah DITOLAK beserta alasan penolakan.');
    }
}
