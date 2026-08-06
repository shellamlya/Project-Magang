<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class OwnerProfileController
 * @package App\Http\Controllers\Owner
 * Pengendali pengelolaan profil akun Pemilik Penginapan (Owner).
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
     * Memperbarui profil owner.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $owner = $user->owner;

        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'nik'          => ['nullable', 'string', 'max:20'],
            'phone'        => ['required', 'string', 'max:20'],
            'address'      => ['required', 'string'],
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        $owner->update([
            'company_name' => $request->company_name,
            'nik'          => $request->nik,
            'phone'        => $request->phone,
            'address'      => $request->address,
        ]);

        return back()->with('success', 'Profil usaha Anda berhasil diperbarui!');
    }
}
