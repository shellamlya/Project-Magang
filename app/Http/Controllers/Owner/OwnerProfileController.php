<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Owner;

/**
 * Class OwnerProfileController
 * @package App\Http\Controllers\Owner
 * Pengendali pengelolaan profil akun Pemilik Tempat Usaha (Owner).
 */
class OwnerProfileController extends Controller
{
    /**
     * Menampilkan form profil owner.
     */
    public function show()
    {
        $user = Auth::user();
        $owner = $user->owner;

        return view('owner.profile', compact('user', 'owner'));
    }

    /**
     * Memperbarui profil owner, kontak, dan file KTP.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $owner = $user->owner;

        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'company_name'   => ['required', 'string', 'max:255'],
            'nik'            => ['nullable', 'string', 'max:20'],
            'phone'          => ['required', 'string', 'max:20'],
            'business_phone' => ['nullable', 'string', 'max:20'],
            'address'        => ['nullable', 'string'],
            'ktp_photo'      => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:3072'],
        ], [
            'name.required'         => 'Nama lengkap penanggung jawab wajib diisi.',
            'company_name.required' => 'Nama badan usaha wajib diisi.',
            'phone.required'        => 'Nomor HP personal wajib diisi untuk validasi admin.',
            'ktp_photo.mimes'       => 'File KTP harus berformat gambar (JPG, PNG, WEBP) atau PDF.',
        ]);

        $user->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        $ownerData = [
            'company_name'   => $request->company_name,
            'nik'            => $request->nik,
            'phone'          => $request->phone,
            'business_phone' => $request->business_phone ?: $request->phone,
            'address'        => $request->address,
        ];

        // Jika upload KTP baru
        if ($request->hasFile('ktp_photo')) {
            // Hapus file lama jika ada di local disk
            if ($owner->ktp_photo && Storage::disk('local')->exists($owner->ktp_photo)) {
                Storage::disk('local')->delete($owner->ktp_photo);
            }
            $ownerData['ktp_photo'] = $request->file('ktp_photo')->store('private/ktp', 'local');
            // Jika sebelumnya rejected, kembalikan ke pending agar Admin meninjau ulang
            if ($owner->account_status === Owner::ACCOUNT_REJECTED || $owner->verification_status === Owner::STATUS_REJECTED) {
                $ownerData['account_status'] = Owner::ACCOUNT_PENDING;
                $ownerData['verification_status'] = Owner::STATUS_PENDING;
                $ownerData['account_rejection_reason'] = null;
            }
        }

        $owner->update($ownerData);

        return back()->with('success', 'Profil usaha dan data kontak Anda berhasil diperbarui!');
    }
}
