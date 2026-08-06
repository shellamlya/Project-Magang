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
 * Pengendali registrasi sederhana untuk pendaftaran akun Owner.
 */
class RegisterOwnerController extends Controller
{
    /**
     * Menampilkan form registrasi owner sederhana.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register-owner');
    }

    /**
     * Memproses pendaftaran akun owner dengan 4 field sederhana.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'     => 'Nama Anda wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $ownerRole = Role::where('name', 'owner')->first();

        // 1. Buat User
        $user = User::create([
            'role_id'  => $ownerRole->id,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 2. Buat Profil Owner default (bisa dilengkapi nanti di Dashboard Owner)
        Owner::create([
            'user_id'      => $user->id,
            'company_name' => 'Usaha ' . $user->name,
            'status'       => 'active',
        ]);

        // 3. Auto Login
        Auth::login($user);

        return redirect()->route('owner.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Dashboard Owner.');
    }
}
