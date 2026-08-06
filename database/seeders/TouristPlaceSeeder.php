<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TouristPlace;
use App\Models\TouristFacility;
use Illuminate\Support\Facades\DB;

class TouristPlaceSeeder extends Seeder
{
    /**
     * Seed database dengan 20 data Wisata Gresik resmi.
     */
    public function run(): void
    {
        // Clear old pivot, facilities, and places
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tourist_place_facility')->truncate();
        DB::table('tourist_facilities')->truncate();
        DB::table('tourist_places')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $data = [
            [
                'name'               => 'Dynasty Water World',
                'operational_hours'  => 'Setiap hari 09.00–17.00 WIB',
                'description'        => 'Taman rekreasi air terbesar di Gresik yang memiliki berbagai kolam renang, wahana permainan air, dan area rekreasi keluarga. Cocok untuk wisata keluarga maupun rombongan sekolah.',
                'manager_name'       => 'Fathur (PIC/Pengelola)',
                'email'              => null,
                'phone'              => '(031) 99102308',
                'google_maps'        => 'https://maps.google.com/?q=Dynasty+Water+World+Gresik',
                'district'           => 'Manyar',
                'village'            => 'Yosowilangun',
                'address'            => 'Jl. Rantau I No.1, Yosowilangun, Manyar, Kabupaten Gresik',
                'ticket_price'       => 'Rp 50.000',
                'facilities'         => ['Area Parkir', 'Kolam Renang', 'Water Slide', 'Gazebo', 'Toilet', 'Mushola', 'Kantin', 'Pusat Informasi', 'Ruang Ganti'],
            ],
            [
                'name'               => 'Wisata Jati Sewu',
                'operational_hours'  => 'Senin–Jumat 08.00–16.00 WIB, Sabtu–Minggu 07.00–17.00 WIB',
                'description'        => 'Destinasi wisata keluarga yang menawarkan taman bermain, wisata air, area edukasi, dan spot foto dengan suasana pedesaan yang asri.',
                'manager_name'       => 'Irma',
                'email'              => null,
                'phone'              => '081252307627',
                'google_maps'        => 'https://maps.google.com/?q=Wisata+Jati+Sewu+Gresik',
                'district'           => 'Menganti',
                'village'            => 'Pelemwatu',
                'address'            => 'Bongso Wetan, Pelemwatu, Menganti, Kabupaten Gresik',
                'ticket_price'       => 'Rp 15.000',
                'facilities'         => ['Parkir Luas', 'Toilet', 'Mushola', 'Pusat Informasi', 'Area Kuliner', 'Gazebo', 'Taman Bermain'],
            ],
            [
                'name'               => 'Wisata Telaga Pelemwatu',
                'operational_hours'  => 'Setiap hari 08.00–17.00 WIB',
                'description'        => 'Objek wisata yang memadukan danau buatan, taman, wahana air, wisata edukasi, dan area pemancingan sehingga cocok untuk rekreasi keluarga.',
                'manager_name'       => 'Setyo Hadi',
                'email'              => null,
                'phone'              => '081230253549',
                'google_maps'        => 'https://maps.google.com/?q=Wisata+Telaga+Pelemwatu+Gresik',
                'district'           => 'Menganti',
                'village'            => 'Pelemwatu',
                'address'            => 'Desa Pelemwatu, Menganti, Kabupaten Gresik',
                'ticket_price'       => 'Rp 10.000',
                'facilities'         => ['Danau', 'Perahu', 'Area Pemancingan', 'Mushola', 'Toilet', 'Area Makan', 'Parkir', 'Gazebo'],
            ],
            [
                'name'               => 'Situs Giri Kedaton',
                'operational_hours'  => 'Setiap hari 07.00–16.00 WIB',
                'description'        => 'Situs peninggalan Kerajaan Giri Kedaton yang menjadi salah satu destinasi wisata sejarah dan religi di Kabupaten Gresik.',
                'manager_name'       => 'Moechtar',
                'email'              => null,
                'phone'              => '081230753708',
                'google_maps'        => 'https://maps.google.com/?q=Situs+Giri+Kedaton+Gresik',
                'district'           => 'Kebomas',
                'village'            => 'Sidomukti',
                'address'            => 'Bukit Giri Kedaton, Sidomukti, Kebomas, Kabupaten Gresik',
                'ticket_price'       => 'Gratis',
                'facilities'         => ['Area Parkir', 'Toilet', 'Mushola', 'Papan Informasi', 'Jalur Pejalan Kaki'],
            ],
            [
                'name'               => 'Ocean Kids Waterpark',
                'operational_hours'  => 'Setiap hari 07.00–16.00 WIB',
                'description'        => 'Kolam renang keluarga dengan wahana permainan air untuk anak-anak serta fasilitas rekreasi.',
                'manager_name'       => 'Mochtar',
                'email'              => null,
                'phone'              => '082180666050',
                'google_maps'        => 'https://maps.google.com/?q=Kolam+Renang+Ocean+Kids+Gresik',
                'district'           => 'Manyar',
                'village'            => 'Suci',
                'address'            => 'Kawasan Suci, Manyar, Kabupaten Gresik',
                'ticket_price'       => 'Rp 20.000',
                'facilities'         => ['Kolam Renang Anak', 'Kolam Renang Dewasa', 'Toilet', 'Mushola', 'Kantin', 'Area Parkir', 'Pusat Informasi'],
            ],
            [
                'name'               => 'Makam Sunan Giri',
                'operational_hours'  => 'Setiap hari 07.00–21.00 WIB',
                'description'        => 'Kompleks makam Sunan Giri yang menjadi tujuan utama wisata religi di Kabupaten Gresik.',
                'manager_name'       => 'Yayasan Makam Sunan Giri',
                'email'              => null,
                'phone'              => '(031) 3981367',
                'google_maps'        => 'https://maps.google.com/?q=Makam+Sunan+Giri+Gresik',
                'district'           => 'Kebomas',
                'village'            => 'Giri',
                'address'            => 'Jl. Sunan Giri, Giri, Kebomas, Kabupaten Gresik',
                'ticket_price'       => 'Gratis',
                'facilities'         => ['Area Parkir', 'Mushola', 'Toilet', 'Tempat Wudhu', 'Kios Oleh-Oleh', 'Area Istirahat', 'Pusat Informasi'],
            ],
            [
                'name'               => 'Makam Maulana Malik Ibrahim',
                'operational_hours'  => '24 Jam',
                'description'        => 'Makam penyebar Islam pertama di Pulau Jawa yang menjadi destinasi wisata religi nasional.',
                'manager_name'       => 'Yayasan Makam Maulana Malik Ibrahim',
                'email'              => null,
                'phone'              => '(031) 3981911',
                'google_maps'        => 'https://maps.google.com/?q=Makam+Maulana+Malik+Ibrahim+Gresik',
                'district'           => 'Gresik',
                'village'            => 'Bedilan',
                'address'            => 'Jl. Malik Ibrahim No.19, Bedilan, Kecamatan Gresik, Kabupaten Gresik',
                'ticket_price'       => 'Gratis',
                'facilities'         => ['Area Parkir', 'Toilet', 'Mushola', 'Tempat Wudhu', 'Kios Suvenir', 'Area Peziarah'],
            ],
            [
                'name'               => 'Masjid Jami\' Gresik',
                'operational_hours'  => '24 Jam',
                'description'        => 'Masjid bersejarah yang masih aktif digunakan sebagai pusat ibadah dan wisata religi.',
                'manager_name'       => 'Takmir Masjid Jami\'',
                'email'              => null,
                'phone'              => '(031) 3981534',
                'google_maps'        => 'https://maps.google.com/?q=Masjid+Jami+Gresik',
                'district'           => 'Gresik',
                'village'            => 'Kebungson',
                'address'            => 'Jl. KH. Wakhid Hasyim No.6, Kebungson, Kecamatan Gresik, Kabupaten Gresik',
                'ticket_price'       => 'Gratis',
                'facilities'         => ['Tempat Wudhu', 'Toilet', 'Area Parkir', 'Ruang Salat', 'Perpustakaan', 'Aula'],
            ],
            [
                'name'               => 'Makam Syekh Maulana Ishaq',
                'operational_hours'  => '07.00–17.00 WIB',
                'description'        => 'Kompleks makam ayah Sunan Giri yang menjadi salah satu tujuan ziarah masyarakat Jawa Timur.',
                'manager_name'       => 'Pengurus Makam Syekh Maulana Ishaq',
                'email'              => null,
                'phone'              => null,
                'google_maps'        => 'https://maps.google.com/?q=Makam+Syekh+Maulana+Ishaq+Gresik',
                'district'           => 'Panceng',
                'village'            => 'Kemangi',
                'address'            => 'Pesisir Kemangi, Panceng, Kabupaten Gresik',
                'ticket_price'       => 'Gratis',
                'facilities'         => ['Area Parkir', 'Mushola', 'Toilet', 'Tempat Istirahat', 'Kios Makanan'],
            ],
            [
                'name'               => 'Masjid Ainul Yaqin',
                'operational_hours'  => '04.00–21.00 WIB',
                'description'        => 'Masjid di kawasan Bukit Jamur yang sering menjadi lokasi singgah wisatawan.',
                'manager_name'       => 'Takmir Masjid Ainul Yaqin',
                'email'              => null,
                'phone'              => null,
                'google_maps'        => 'https://maps.google.com/?q=Masjid+Ainul+Yaqin+Bungah+Gresik',
                'district'           => 'Bungah',
                'village'            => 'Sungonlegowo',
                'address'            => 'Kawasan Bukit Jamur, Bungah, Kabupaten Gresik',
                'ticket_price'       => 'Gratis',
                'facilities'         => ['Area Parkir', 'Toilet', 'Tempat Wudhu', 'Taman', 'Gazebo', 'Area Istirahat'],
            ],
            [
                'name'               => 'Wisata Alam Gosari (WAGOS)',
                'operational_hours'  => '08.00–16.30 WIB',
                'description'        => 'Bekas tambang kapur yang diubah menjadi wisata alam dengan tebing batu kapur, danau, taman, dan spot foto.',
                'manager_name'       => 'Pokdarwis Desa Gosari',
                'email'              => null,
                'phone'              => '085859046576',
                'google_maps'        => 'https://maps.google.com/?q=Wisata+Alam+Gosari+Gresik',
                'district'           => 'Ujungpangkah',
                'village'            => 'Gosari',
                'address'            => 'Desa Gosari, Ujungpangkah, Kabupaten Gresik',
                'ticket_price'       => 'Rp 10.000',
                'facilities'         => ['Area Parkir', 'Toilet', 'Mushola', 'Gazebo', 'Kantin', 'Spot Foto', 'Area Bermain'],
            ],
            [
                'name'               => 'Pantai Dalegan',
                'operational_hours'  => '05.00–18.00 WIB',
                'description'        => 'Pantai berpasir putih dengan ombak tenang yang cocok untuk wisata keluarga.',
                'manager_name'       => 'Pemerintah Desa Dalegan',
                'email'              => null,
                'phone'              => null,
                'google_maps'        => 'https://maps.google.com/?q=Pantai+Dalegan+Gresik',
                'district'           => 'Panceng',
                'village'            => 'Dalegan',
                'address'            => 'Desa Dalegan, Panceng, Kabupaten Gresik',
                'ticket_price'       => 'Rp 10.000',
                'facilities'         => ['Area Parkir', 'Toilet', 'Mushola', 'Gazebo', 'Penyewaan Ban', 'Warung Makan'],
            ],
            [
                'name'               => 'Edu Wisata Lontar Sewu',
                'operational_hours'  => '08.00–17.00 WIB',
                'description'        => 'Destinasi wisata alam dan edukasi dengan hutan lontar, wahana bermain, serta kuliner khas legen.',
                'manager_name'       => 'BUMDes Hendrosari',
                'email'              => null,
                'phone'              => '085607175639',
                'google_maps'        => 'https://maps.google.com/?q=Lontar+Sewu+Hendrosari',
                'district'           => 'Menganti',
                'village'            => 'Hendrosari',
                'address'            => 'Desa Hendrosari, Menganti, Kabupaten Gresik',
                'ticket_price'       => 'Rp 8.000',
                'facilities'         => ['Area Parkir', 'Toilet', 'Mushola', 'Food Court', 'Gazebo', 'Playground', 'Spot Foto'],
            ],
            [
                'name'               => 'Bukit Jamur',
                'operational_hours'  => '06.00–17.00 WIB',
                'description'        => 'Kawasan bekas tambang kapur dengan batu berbentuk jamur raksasa.',
                'manager_name'       => 'Pemerintah Desa Bungah',
                'email'              => null,
                'phone'              => null,
                'google_maps'        => 'https://maps.google.com/?q=Bukit+Jamur+Gresik',
                'district'           => 'Bungah',
                'village'            => 'Bungah',
                'address'            => 'Desa Bungah, Bungah, Kabupaten Gresik',
                'ticket_price'       => 'Rp 5.000',
                'facilities'         => ['Area Parkir', 'Toilet', 'Gazebo', 'Spot Foto'],
            ],
            [
                'name'               => 'Telaga Ngipik',
                'operational_hours'  => '24 Jam',
                'description'        => 'Danau buatan yang menjadi ruang terbuka hijau dan tempat rekreasi masyarakat.',
                'manager_name'       => 'Pemerintah Kabupaten Gresik',
                'email'              => null,
                'phone'              => null,
                'google_maps'        => 'https://maps.google.com/?q=Telaga+Ngipik+Gresik',
                'district'           => 'Gresik',
                'village'            => 'Ngipik',
                'address'            => 'Jl. Raya Ngipik, Kecamatan Gresik, Kabupaten Gresik',
                'ticket_price'       => 'Gratis',
                'facilities'         => ['Jogging Track', 'Area Parkir', 'Gazebo', 'Taman', 'Toilet', 'Tempat Duduk'],
            ],
            [
                'name'               => 'Mangrove Kalimireng',
                'operational_hours'  => '06.00–17.00 WIB',
                'description'        => 'Kawasan ekowisata dan konservasi hutan mangrove di Kalimireng Gresik dengan jembatan kayu dan pemandangan asri.',
                'manager_name'       => 'Pokdarwis Kalimireng',
                'email'              => null,
                'phone'              => '081332557283',
                'google_maps'        => 'https://maps.google.com/?q=Mangrove+Kalimireng+Gresik',
                'district'           => 'Manyar',
                'village'            => 'Kalimireng',
                'address'            => 'Desa Kalimireng, Manyar, Kabupaten Gresik',
                'ticket_price'       => 'Rp 5.000',
                'facilities'         => ['Boardwalk', 'Gazebo', 'Area Parkir', 'Toilet', 'Spot Foto'],
            ],
            [
                'name'               => 'Ekowisata Mangrove Kali Lamong',
                'operational_hours'  => '08.00–17.00 WIB',
                'description'        => 'Kawasan ekowisata hutan mangrove di pesisir Kali Lamong yang menyuguhkan area edukasi ekosistem laut.',
                'manager_name'       => 'Kelompok Pengelola Ekowisata',
                'email'              => null,
                'phone'              => null,
                'google_maps'        => 'https://maps.google.com/?q=Ekowisata+Mangrove+Kali+Lamong',
                'district'           => 'Kebomas',
                'village'            => 'Singosari',
                'address'            => 'Kawasan Kali Lamong, Kebomas, Kabupaten Gresik',
                'ticket_price'       => 'Rp 5.000',
                'facilities'         => ['Jalur Tracking', 'Gazebo', 'Area Parkir', 'Toilet', 'Area Edukasi'],
            ],
            [
                'name'               => 'Exotic Mengare',
                'operational_hours'  => '09.00–16.00 WIB',
                'description'        => 'Destinasi wisata bahari dan ekosistem pulau eksotis di Mengare Gresik.',
                'manager_name'       => 'Pokdarwis Mengare',
                'email'              => null,
                'phone'              => null,
                'google_maps'        => 'https://maps.google.com/?q=Exotic+Mengare+Gresik',
                'district'           => 'Bungah',
                'village'            => 'Mengare',
                'address'            => 'Pulau Mengare, Bungah, Kabupaten Gresik',
                'ticket_price'       => 'Rp 10.000',
                'facilities'         => ['Area Parkir', 'Toilet', 'Gazebo', 'Spot Foto', 'Warung'],
            ],
            [
                'name'               => 'Bukit Surowiti',
                'operational_hours'  => '24 Jam',
                'description'        => 'Wisata bukit bersejarah dan tempat ziarah petilasan Sunan Kalijaga yang menyuguhkan panorama alam dari ketinggian.',
                'manager_name'       => 'Pokdarwis Surowiti',
                'email'              => null,
                'phone'              => '085348081953',
                'google_maps'        => 'https://maps.google.com/?q=Bukit+Surowiti+Gresik',
                'district'           => 'Panceng',
                'village'            => 'Surowiti',
                'address'            => 'Desa Surowiti, Panceng, Kabupaten Gresik',
                'ticket_price'       => 'Rp 5.000',
                'facilities'         => ['Area Parkir', 'Gazebo', 'Spot Foto', 'Warung'],
            ],
            [
                'name'               => 'Gunung Kapur Suci',
                'operational_hours'  => '24 Jam',
                'description'        => 'Pemandangan tebing batu kapur yang ikonik di Desa Suci Manyar Gresik.',
                'manager_name'       => 'Pemerintah Desa Suci',
                'email'              => null,
                'phone'              => null,
                'google_maps'        => 'https://maps.google.com/?q=Gunung+Kapur+Suci+Gresik',
                'district'           => 'Manyar',
                'village'            => 'Suci',
                'address'            => 'Desa Suci, Manyar, Kabupaten Gresik',
                'ticket_price'       => 'Gratis',
                'facilities'         => ['Area Parkir', 'Spot Foto', 'Area Istirahat'],
            ],
        ];

        // Collect all unique facilities and create them in database
        $allFacilityNames = [];
        foreach ($data as $item) {
            foreach ($item['facilities'] as $facName) {
                $allFacilityNames[$facName] = true;
            }
        }

        $facilityMap = [];
        foreach (array_keys($allFacilityNames) as $facName) {
            $facility = TouristFacility::create([
                'facility_name' => $facName,
            ]);
            $facilityMap[$facName] = $facility->id;
        }

        // Insert places and attach facilities
        foreach ($data as $item) {
            $place = TouristPlace::create([
                'name'              => $item['name'],
                'operational_hours' => $item['operational_hours'],
                'description'       => $item['description'],
                'manager_name'      => $item['manager_name'],
                'email'             => $item['email'],
                'phone'             => $item['phone'],
                'google_maps'       => $item['google_maps'],
                'district'          => $item['district'],
                'village'           => $item['village'],
                'address'           => $item['address'],
                'ticket_price'      => $item['ticket_price'],
                'status'            => 'approved',
            ]);

            $facilityIds = [];
            foreach ($item['facilities'] as $facName) {
                if (isset($facilityMap[$facName])) {
                    $facilityIds[] = $facilityMap[$facName];
                }
            }

            $place->facilities()->sync($facilityIds);
        }
    }
}
