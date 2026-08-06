<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Jalankan seeder Fasilitas Penginapan.
     */
    public function run(): void
    {
        $facilities = [
            ['name' => 'WiFi',               'icon' => 'fa-wifi'],
            ['name' => 'AC',                 'icon' => 'fa-snowflake'],
            ['name' => 'TV',                 'icon' => 'fa-tv'],
            ['name' => 'Kolam Renang',       'icon' => 'fa-person-swimming'],
            ['name' => 'Parkir',             'icon' => 'fa-square-p'],
            ['name' => 'Restoran',           'icon' => 'fa-utensils'],
            ['name' => 'Musholla',           'icon' => 'fa-mosque'],
            ['name' => 'Laundry',            'icon' => 'fa-jug-detergent'],
            ['name' => 'Lift',               'icon' => 'fa-elevator'],
            ['name' => 'Water Heater',       'icon' => 'fa-shower'],
            ['name' => 'Meeting Room',       'icon' => 'fa-users'],
            ['name' => 'Family Room',        'icon' => 'fa-people-roof'],
            ['name' => 'Breakfast',          'icon' => 'fa-mug-hot'],
            ['name' => 'Smoking Area',       'icon' => 'fa-smoking'],
            ['name' => 'Non Smoking Room',   'icon' => 'fa-ban-smoking'],
            ['name' => 'CCTV',               'icon' => 'fa-video'],
            ['name' => 'Resepsionis 24 Jam', 'icon' => 'fa-clock'],
        ];

        foreach ($facilities as $fac) {
            Facility::firstOrCreate(['name' => $fac['name']], $fac);
        }
    }
}
