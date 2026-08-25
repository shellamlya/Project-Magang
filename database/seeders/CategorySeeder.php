<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Jalankan seeder Kategori Penginapan .
     */
    public function run(): void
    {
        $categories = [
            // Penginapan
            ['name' => 'Hotel',                   'service_type' => 'penginapan', 'icon' => 'fa-hotel'],
            ['name' => 'Guest House',             'service_type' => 'penginapan', 'icon' => 'fa-bed'],
            ['name' => 'Homestay',                'service_type' => 'penginapan', 'icon' => 'fa-house-user'],
            ['name' => 'Villa',                   'service_type' => 'penginapan', 'icon' => 'fa-gopuram'],
            ['name' => 'Kost Harian',             'service_type' => 'penginapan', 'icon' => 'fa-door-open'],
            ['name' => 'Resort',                  'service_type' => 'penginapan', 'icon' => 'fa-umbrella-beach'],

            // Wisata
            ['name' => 'Wisata Alam',             'service_type' => 'wisata',     'icon' => 'fa-mountain-sun'],
            ['name' => 'Wisata Bahari',           'service_type' => 'wisata',     'icon' => 'fa-water'],
            ['name' => 'Wisata Sejarah & Budaya', 'service_type' => 'wisata',     'icon' => 'fa-landmark'],
            ['name' => 'Wisata Religi',           'service_type' => 'wisata',     'icon' => 'fa-mosque'],
            ['name' => 'Wisata Edukasi',          'service_type' => 'wisata',     'icon' => 'fa-graduation-cap'],

            // Nongkrong
            ['name' => 'Coffee Shop & Cafe',     'service_type' => 'nongkrong',  'icon' => 'fa-mug-hot'],
            ['name' => 'Resto & Culinary',        'service_type' => 'nongkrong',  'icon' => 'fa-utensils'],
            ['name' => 'Outdoor & Rooftop',       'service_type' => 'nongkrong',  'icon' => 'fa-sun'],
            ['name' => 'Live Music & Lounge',     'service_type' => 'nongkrong',  'icon' => 'fa-music'],
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
