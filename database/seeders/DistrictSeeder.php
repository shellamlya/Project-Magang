<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Village;

class DistrictSeeder extends Seeder
{
    /**
     * Jalankan seeder Kecamatan dan Kelurahan/Desa Kabupaten Gresik.
     */
    public function run(): void
    {
        $data = [
            'Gresik' => ['Sukorame', 'Sidokumpul', 'Kebungson', 'Bedilan', 'Pekelingan', 'Tlogopatut', 'Kroman', 'Pulopancikan'],
            'Kebomas' => ['Randuagung', 'Dahanrejo', 'Sekarkurate', 'Indro', 'Gulomantung', 'Kedimbang', 'Singosari', 'Kembangan'],
            'Manyar' => ['Manyar Sidoroso', 'Suci', 'Pegenden', 'Yosowilangun', 'Tepanas', 'Leran', 'Betoyo'],
            'Driyorejo' => ['Driyorejo', 'Krikilan', 'Tenaru', 'Cangkir', 'Mulung', 'Petiken', 'Karangandong'],
            'Cerme' => ['Cerme Lor', 'Cerme Kidul', 'Betiting', 'Semambung', 'Padeg', 'Gedangkulut'],
            'Menganti' => ['Menganti', 'Hulaan', 'Pelemwatu', 'Bringkang', 'Sidojangkung', 'Lontar', 'Setro'],
            'Benjeng' => ['Benjeng', 'Metatu', 'Bulurejo', 'Deliksumber', 'Klampok', 'Munggugebang'],
            'Duduksampeyan' => ['Duduksampeyan', 'Setrohadi', 'Sumengko', 'Gredek', 'Ambeng-Ambeng Watangrejo'],
            'Panceng' => ['Campurejo', 'Banyuwangi', 'Doudo', 'Surowiti', 'Pantenan'],
            'Ujungpangkah' => ['Sekapuk', 'Pangkah Kulon', 'Pangkah Wetan', 'Bolo', 'Cangaan'],
            'Bungah' => ['Bungah', 'Bedanten', 'Masangan', 'Sungonlegowo', 'Abar-Abir', 'Sukorejo'],
            'Sidayu' => ['Kauman', 'Randuboto', 'Kertosono', 'Sidomulyo', 'Golokan'],
            'Balongpanggang' => ['Balongpanggang', 'Karangsemanding', 'Dapet', 'Kedungpring', 'Wotansari'],
            'Wringinanom' => ['Wringinanom', 'Sembung', 'Lebanisuko', 'Kapas', 'Sumberame'],
        ];

        foreach ($data as $districtName => $villages) {
            $district = District::firstOrCreate(['name' => $districtName]);
            foreach ($villages as $villageName) {
                Village::firstOrCreate([
                    'district_id' => $district->id,
                    'name' => $villageName,
                ]);
            }
        }
    }
}
