<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Jalankan seeder Kategori Penginapan (Modul Shella).
     */
    public function run(): void
    {
        $categories = [
            // Shella (Penginapan)
            ['name' => 'Hotel',                   'service_type' => 'shella', 'icon' => 'fa-hotel'],
            ['name' => 'Guest House',             'service_type' => 'shella', 'icon' => 'fa-bed'],
            ['name' => 'Homestay',                'service_type' => 'shella', 'icon' => 'fa-house-user'],
            ['name' => 'Villa',                   'service_type' => 'shella', 'icon' => 'fa-gopuram'],
            ['name' => 'Kost Harian',             'service_type' => 'shella', 'icon' => 'fa-door-open'],
            ['name' => 'Resort',                  'service_type' => 'shella', 'icon' => 'fa-umbrella-beach'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'service_type' => $cat['service_type'],
                    'icon' => $cat['icon'],
                ]
            );
        }
    }
}
