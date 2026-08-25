<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TouristPlace;
use App\Models\HangoutPlace;
use App\Models\Lodging;

class DisparekrafbudporaSeeder extends Seeder
{
    /**
     * Run database seeds for official Disparekrafbudpora Gresik reference places.
     */
    public function run(): void
    {
        // Data Wisata Resmi Kab. Gresik
        $touristPlaces = [
            [
                'name' => 'Wisata Religi Makam Sunan Giri',
                'description' => 'Makam Sunan Giri merupakan salah satu destinasi wisata religi utama di Kabupaten Gresik. Kawasan ini ramai dikunjungi peziarah dari berbagai daerah di Indonesia.',
                'operational_hours' => '24 Jam',
                'address' => 'Jl. Sunan Giri, Giri, Kebomas',
                'district' => 'Kebomas',
                'village' => 'Giri',
                'postal_code' => '61121',
                'latitude' => '-7.1681',
                'longitude' => '112.6317',
                'google_maps' => 'https://maps.google.com/?q=Makam+Sunan+Giri+Gresik',
                'manager_name' => 'Disparekrafbudpora / Yayasan Sunan Giri',
                'email' => 'disparekrafbudpora@gresikkab.go.id',
                'phone' => '(031) 3981111',
                'ticket_price' => 'Gratis / Infaq Sukarela',
                'status' => 'approved',
                'is_verified_official' => true,
                'status_claim' => 'unclaimed',
                'views_count' => 120,
                'maps_clicks_count' => 45,
            ],
            [
                'name' => 'Wisata Bahari Pantai Delegan',
                'description' => 'Pantai Delegan menawarkan keindahan pasir putih dan ombak tenang khas pesisir utara Gresik. Sangat cocok untuk wisata keluarga dan wahana permainan air.',
                'operational_hours' => '07.00 - 17.00 WIB',
                'address' => 'Desa Delegan, Kecamatan Panceng',
                'district' => 'Panceng',
                'village' => 'Delegan',
                'postal_code' => '61156',
                'latitude' => '-6.8833',
                'longitude' => '112.4833',
                'google_maps' => 'https://maps.google.com/?q=Pantai+Delegan+Gresik',
                'manager_name' => 'BUMDes Delegan & Disparekrafbudpora',
                'email' => 'pantaidelegan@gresikkab.go.id',
                'phone' => '081234567890',
                'ticket_price' => 'Rp 10.000 / Orang',
                'status' => 'approved',
                'is_verified_official' => true,
                'status_claim' => 'unclaimed',
                'views_count' => 95,
                'maps_clicks_count' => 30,
            ],
            [
                'name' => 'Bukit Jamur Bungah',
                'description' => 'Fenomena alam unik berupa batuan sisa penambangan yang mengikis membentuk seperti jamur raksasa. Menjadi spot foto ikonik di Gresik.',
                'operational_hours' => '08.00 - 16.00 WIB',
                'address' => 'Bungah, Kecamatan Bungah',
                'district' => 'Bungah',
                'village' => 'Bungah',
                'postal_code' => '61152',
                'latitude' => '-7.0612',
                'longitude' => '112.5645',
                'google_maps' => 'https://maps.google.com/?q=Bukit+Jamur+Gresik',
                'manager_name' => 'Pengelola Kawasan Bungah',
                'email' => 'info@gresikkab.go.id',
                'phone' => '082134567891',
                'ticket_price' => 'Rp 5.000 / Orang',
                'status' => 'approved',
                'is_verified_official' => true,
                'status_claim' => 'unclaimed',
                'views_count' => 78,
                'maps_clicks_count' => 22,
            ],
        ];

        foreach ($touristPlaces as $place) {
            TouristPlace::updateOrCreate(['name' => $place['name']], $place);
        }

        // Data Nongkrong / Kuliner Resmi Kab. Gresik
        $hangoutPlaces = [
            [
                'name' => 'Kawasan Warung Kopi Giras Gresik Kota',
                'description' => 'Warkop Giras Khas Gresik menyajikan Kopi Kopyok otentik khas Gresik yang diproses secara tradisional dengan racikan rempah istimewa.',
                'operational_hours' => '24 Jam',
                'address' => 'Jl. Veteran, Sidokumpul, Kec. Gresik',
                'district' => 'Gresik',
                'village' => 'Sidokumpul',
                'postal_code' => '61111',
                'latitude' => '-7.1550',
                'longitude' => '112.6500',
                'google_maps' => 'https://maps.google.com/?q=Warkop+Giras+Gresik',
                'manager_name' => 'Paguyuban Warkop Gresik',
                'email' => 'giras@gresik.go.id',
                'phone' => '085712345678',
                'status' => 'approved',
                'is_verified_official' => true,
                'status_claim' => 'unclaimed',
                'views_count' => 140,
                'maps_clicks_count' => 60,
            ],
            [
                'name' => 'Bandar Grisse Heritage Cafe',
                'description' => 'Kafe heritage di kawasan pusat sejarah Kota Tua Gresik Bandar Grisse. Menyediakan suasana kolonial dengan sajian kopi dan makanan khas Gresik.',
                'operational_hours' => '10.00 - 23.00 WIB',
                'address' => 'Jl. Basuki Rahmat, Kebungson, Kec. Gresik',
                'district' => 'Gresik',
                'village' => 'Kebungson',
                'postal_code' => '61114',
                'latitude' => '-7.1588',
                'longitude' => '112.6555',
                'google_maps' => 'https://maps.google.com/?q=Bandar+Grisse+Gresik',
                'manager_name' => 'Pengelola Bandar Grisse',
                'email' => 'bandargrisse@gresikkab.go.id',
                'phone' => '081399887766',
                'status' => 'approved',
                'is_verified_official' => true,
                'status_claim' => 'unclaimed',
                'views_count' => 110,
                'maps_clicks_count' => 50,
            ],
        ];

        foreach ($hangoutPlaces as $place) {
            HangoutPlace::updateOrCreate(['name' => $place['name']], $place);
        }

        // Data Penginapan Resmi Kab. Gresik
        $lodgings = [
            [
                'name' => 'KHAS Gresik Hotel',
                'description' => 'Hotel bintang 3 modern berstandar internasional yang berlokasi sangat strategis di pusat kota Gresik.',
                'operational_hours' => '24 Jam',
                'check_in' => '14.00 WIB',
                'check_out' => '12.00 WIB',
                'address' => 'Jl. Panglima Sudirman No. 1, Sidokumpul, Kec. Gresik',
                'district' => 'Gresik',
                'village' => 'Sidokumpul',
                'postal_code' => '61111',
                'latitude' => '-7.1590',
                'longitude' => '112.6530',
                'google_maps' => 'https://maps.google.com/?q=KHAS+Gresik+Hotel',
                'manager_name' => 'Management KHAS Gresik',
                'email' => 'info@khasgresik.com',
                'phone' => '(031) 99006330',
                'website' => 'https://khasgresik.com',
                'price_start' => 550000,
                'price_end' => 950000,
                'status' => 'approved',
                'is_verified_official' => true,
                'status_claim' => 'unclaimed',
                'views_count' => 210,
                'maps_clicks_count' => 85,
            ],
            [
                'name' => 'Hotel Santika Gresik',
                'description' => 'Penginapan mewah terhubung langsung dengan Icon Mall Gresik. Pilihan terbaik bagi pebisnis dan wisatawan.',
                'operational_hours' => '24 Jam',
                'check_in' => '14.00 WIB',
                'check_out' => '12.00 WIB',
                'address' => 'Jl. Dr. Wahidin Sudirohusodo No. 788, Kebomas',
                'district' => 'Kebomas',
                'village' => 'Kembangan',
                'postal_code' => '61124',
                'latitude' => '-7.1700',
                'longitude' => '112.6150',
                'google_maps' => 'https://maps.google.com/?q=Hotel+Santika+Gresik',
                'manager_name' => 'Santika Indonesia',
                'email' => 'gresik@santika.com',
                'phone' => '(031) 3992888',
                'website' => 'https://santika.com',
                'price_start' => 680000,
                'price_end' => 1250000,
                'status' => 'approved',
                'is_verified_official' => true,
                'status_claim' => 'unclaimed',
                'views_count' => 180,
                'maps_clicks_count' => 70,
            ],
        ];

        foreach ($lodgings as $place) {
            Lodging::updateOrCreate(['name' => $place['name']], $place);
        }
    }
}
