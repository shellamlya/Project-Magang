<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Class RegisterOwnerController
 * @package App\Http\Controllers\Auth
 * Pengendali registrasi akun Owner dengan validasi KTP privat dan No. HP Personal.
 */
class RegisterOwnerController extends Controller
{
    /**
     * Menampilkan form registrasi owner.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register-owner');
    }

    /**
     * Memproses pendaftaran akun owner dengan verifikasi KTP dan No. HP Personal.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'        => ['required', 'string', 'max:25'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'ktp_photo'    => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:3072'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'         => 'Nama lengkap penanggung jawab wajib diisi.',
            'email.required'        => 'Email login wajib diisi.',
            'email.unique'          => 'Email ini sudah terdaftar di sistem.',
            'phone.required'        => 'Nomor HP/WhatsApp personal wajib diisi untuk verifikasi Admin.',
            'ktp_photo.required'    => 'Foto KTP penanggung jawab wajib diunggah.',
            'ktp_photo.mimes'       => 'Foto KTP harus berupa format gambar (JPG, PNG, WEBP) atau PDF.',
            'ktp_photo.max'         => 'Ukuran foto KTP maksimal 3 MB.',
            'password.required'     => 'Kata sandi wajib diisi.',
            'password.min'          => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $ownerRole = Role::where('name', 'owner')->first();

        // 1. Simpan Foto KTP ke private storage (disk: local, path: private/ktp)
        $ktpPath = null;
        if ($request->hasFile('ktp_photo')) {
            $ktpPath = $request->file('ktp_photo')->store('private/ktp', 'local');
        }

        // 2. Buat User
        $user = User::create([
            'role_id'  => $ownerRole->id,
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // 3. Buat Profil Owner dengan status pending_account (Verifikasi Tingkat 1)
        Owner::create([
            'user_id'        => $user->id,
            'company_name'   => $request->company_name ?: ('Usaha ' . $user->name),
            'phone'          => $request->phone,
            'business_phone' => $request->phone, // Default awal sebelum diubah di form usaha
            'ktp_photo'      => $ktpPath,
            'account_status' => Owner::ACCOUNT_PENDING,
            'status'         => 'active',
        ]);

        // 4. Auto Login
        Auth::login($user);

        return redirect()->route('owner.dashboard')->with('success', 'Pendaftaran akun Owner berhasil! Akun Anda sedang dalam proses verifikasi identitas (KTP & No. HP) oleh Admin Disparekrafbudpora.');
    }
}
