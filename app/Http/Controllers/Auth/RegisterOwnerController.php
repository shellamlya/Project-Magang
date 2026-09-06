<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Auth\Events\Registered;

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
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'        => ['required', 'string', 'max:25'],
            'company_name' => ['required', 'string', 'max:255'],
            'ktp_photo'    => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'         => 'Nama Lengkap Owner / Penanggung Jawab wajib diisi.',
            'name.max'              => 'Nama lengkap maksimal 255 karakter.',
            'email.required'        => 'Alamat Email Login wajib diisi.',
            'email.email'           => 'Format alamat email tidak valid.',
            'email.unique'          => 'Alamat email ini sudah terdaftar di sistem.',
            'phone.required'        => 'No. HP / WhatsApp Personal Owner wajib diisi.',
            'company_name.required' => 'Nama Tempat Usaha / Badan Usaha wajib diisi.',
            'ktp_photo.required'    => 'Foto KTP Penanggung Jawab wajib diunggah.',
            'ktp_photo.image'       => 'File foto KTP harus berupa file gambar.',
            'ktp_photo.mimes'       => 'Foto KTP harus berformat: jpeg, png, atau jpg.',
            'ktp_photo.max'         => 'Ukuran file foto KTP maksimal 2 MB.',
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

        // 3. Buat Profil Owner dengan status pending_account & verification_status pending
        Owner::create([
            'user_id'             => $user->id,
            'company_name'        => $request->company_name,
            'phone'               => $request->phone,
            'business_phone'      => $request->phone, // Default awal sebelum diubah di form usaha
            'ktp_photo'           => $ktpPath,
            'account_status'      => Owner::ACCOUNT_PENDING,
            'verification_status' => Owner::STATUS_PENDING,
            'status'              => 'active',
        ]);

        // 4. Trigger Email Verification
        event(new Registered($user));

        // 5. Login Pengguna Baru
        Auth::login($user);

        return redirect()->route('verification.notice')->with('success', 'Pendaftaran akun Owner berhasil! Silakan periksa inbox email Anda untuk melakukan konfirmasi alamat email.');
    }
}
