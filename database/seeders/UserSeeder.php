<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder Pengguna (Admin, Owners, User).
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $ownerRole = Role::where('name', 'owner')->first();
        $userRole  = Role::where('name', 'user')->first();

        // 1. Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@grex.id'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Super Administrator GREX',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
            ]
        );

        // 2. Akun Owner 1
        $userOwner1 = User::firstOrCreate(
            ['email' => 'owner1@grex.id'],
            [
                'role_id' => $ownerRole->id,
                'name' => 'Bambang Sudarmono (PT Gresik Homestay)',
                'password' => Hash::make('password'),
                'phone' => '081399887766',
            ]
        );
        Owner::firstOrCreate(
            ['user_id' => $userOwner1->id],
            [
                'nik' => '3525011234560001',
                'company_name' => 'PT Gresik Homestay Nusantara',
                'address' => 'Jl. Dr. Wahidin Sudirohusodo No. 12, Kebomas, Gresik',
                'phone' => '081399887766',
                'status' => 'active',
            ]
        );

        // 3. Akun Owner 2
        $userOwner2 = User::firstOrCreate(
            ['email' => 'owner2@grex.id'],
            [
                'role_id' => $ownerRole->id,
                'name' => 'Siti Aminah (Java Hospitality)',
                'password' => Hash::make('password'),
                'phone' => '082155443322',
            ]
        );
        Owner::firstOrCreate(
            ['user_id' => $userOwner2->id],
            [
                'nik' => '3525029876540002',
                'company_name' => 'CV Java Hospitality Gresik',
                'address' => 'Jl. Veteran No. 45, Gresik Kota',
                'phone' => '082155443322',
                'status' => 'active',
            ]
        );

        // 4. Akun Owner 3
        $userOwner3 = User::firstOrCreate(
            ['email' => 'owner3@grex.id'],
            [
                'role_id' => $ownerRole->id,
                'name' => 'H. Ahmad Sulaiman (Pemilik Villa & Kost)',
                'password' => Hash::make('password'),
                'phone' => '085711223344',
            ]
        );
        Owner::firstOrCreate(
            ['user_id' => $userOwner3->id],
            [
                'nik' => '3525035554440003',
                'company_name' => 'Gresik Asri Group',
                'address' => 'Jl. Raya Menganti No. 88, Menganti, Gresik',
                'phone' => '085711223344',
                'status' => 'active',
            ]
        );

        // 5. Akun User Publik (Opsional untuk testing login user)
        User::firstOrCreate(
            ['email' => 'user@grex.id'],
            [
                'role_id' => $userRole->id,
                'name' => 'Wisatawan Pengunjung',
                'password' => Hash::make('password'),
                'phone' => '089900112233',
            ]
        );
    }
}
