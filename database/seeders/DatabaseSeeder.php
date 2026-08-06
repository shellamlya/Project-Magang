<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DistrictSeeder::class,
            CategorySeeder::class,
            FacilitySeeder::class,
            UserSeeder::class,
            LodgingSeeder::class,
            TouristPlaceSeeder::class,
            HangoutSeeder::class,
        ]);
    }
}
