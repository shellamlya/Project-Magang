<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Lodging;
use App\Models\TouristPlace;
use App\Models\HangoutPlace;

class InstagramPlaceSubmissionTest extends TestCase
{
    public function test_instagram_variations_and_url_generation()
    {
        $testCases = [
            '@kopisenja' => [
                'expected_handle' => '@kopisenja',
                'expected_url'    => 'https://www.instagram.com/kopisenja/',
            ],
            'kopisenja' => [
                'expected_handle' => '@kopisenja',
                'expected_url'    => 'https://www.instagram.com/kopisenja/',
            ],
            'https://www.instagram.com/kopisenja' => [
                'expected_handle' => '@kopisenja',
                'expected_url'    => 'https://www.instagram.com/kopisenja/',
            ],
            'https://www.instagram.com/kopisenja/' => [
                'expected_handle' => '@kopisenja',
                'expected_url'    => 'https://www.instagram.com/kopisenja/',
            ],
            'https://instagram.com/kopisenja' => [
                'expected_handle' => '@kopisenja',
                'expected_url'    => 'https://www.instagram.com/kopisenja/',
            ],
            'http://instagram.com/kopisenja/' => [
                'expected_handle' => '@kopisenja',
                'expected_url'    => 'https://www.instagram.com/kopisenja/',
            ],
            'instagram.com/kopisenja' => [
                'expected_handle' => '@kopisenja',
                'expected_url'    => 'https://www.instagram.com/kopisenja/',
            ],
            'www.instagram.com/kopisenja/' => [
                'expected_handle' => '@kopisenja',
                'expected_url'    => 'https://www.instagram.com/kopisenja/',
            ],
            'https://www.instagram.com/kopisenja/?igsh=MTQ1Z21hYW' => [
                'expected_handle' => '@kopisenja',
                'expected_url'    => 'https://www.instagram.com/kopisenja/',
            ],
            '  @kopisenja  ' => [
                'expected_handle' => '@kopisenja',
                'expected_url'    => 'https://www.instagram.com/kopisenja/',
            ],
        ];

        // Test pada Lodging
        foreach ($testCases as $input => $expected) {
            $lodging = new Lodging(['instagram' => $input]);
            $this->assertEquals($expected['expected_handle'], $lodging->instagram_handle, "Failed handle for lodging input: $input");
            $this->assertEquals($expected['expected_url'], $lodging->instagram_url, "Failed url for lodging input: $input");
        }

        // Test pada TouristPlace
        foreach ($testCases as $input => $expected) {
            $wisata = new TouristPlace(['instagram' => $input]);
            $this->assertEquals($expected['expected_handle'], $wisata->instagram_handle, "Failed handle for wisata input: $input");
            $this->assertEquals($expected['expected_url'], $wisata->instagram_url, "Failed url for wisata input: $input");
        }

        // Test pada HangoutPlace
        foreach ($testCases as $input => $expected) {
            $nongkrong = new HangoutPlace(['instagram' => $input]);
            $this->assertEquals($expected['expected_handle'], $nongkrong->instagram_handle, "Failed handle for nongkrong input: $input");
            $this->assertEquals($expected['expected_url'], $nongkrong->instagram_url, "Failed url for nongkrong input: $input");
        }
    }

    public function test_null_or_empty_instagram_handling()
    {
        $emptyInputs = [null, '', '   '];

        foreach ($emptyInputs as $emptyInput) {
            $lodging = new Lodging(['instagram' => $emptyInput]);
            $this->assertNull($lodging->instagram_handle);
            $this->assertNull($lodging->instagram_url);

            $wisata = new TouristPlace(['instagram' => $emptyInput]);
            $this->assertNull($wisata->instagram_handle);
            $this->assertNull($wisata->instagram_url);

            $nongkrong = new HangoutPlace(['instagram' => $emptyInput]);
            $this->assertNull($nongkrong->instagram_handle);
            $this->assertNull($nongkrong->instagram_url);
        }
    }

    public function test_public_pages_render_instagram_when_available()
    {
        // Lodging test
        $lodging = new Lodging([
            'id' => 9991,
            'name' => 'Hotel Uji Instagram',
            'description' => 'Deskripsi uji',
            'operational_hours' => '24 Jam',
            'instagram' => '@hoteluji',
            'status' => 'approved',
        ]);
        $lodging->setRelation('facilities', collect());
        $viewLodging = $this->view('user.penginapan-detail', [
            'lodging' => $lodging,
            'otherLodgings' => collect(),
            'nearbyWisata' => collect(),
        ]);
        $viewLodging->assertSee('Instagram Usaha');
        $viewLodging->assertSee('@hoteluji');
        $viewLodging->assertSee('https://www.instagram.com/hoteluji/');

        // TouristPlace test
        $wisata = new TouristPlace([
            'id' => 9992,
            'name' => 'Pantai Uji Instagram',
            'description' => 'Deskripsi uji wisata',
            'operational_hours' => '08.00 - 17.00 WIB',
            'instagram' => 'https://www.instagram.com/pantaiuji/',
            'status' => 'approved',
            'manager_name' => 'Pengelola Pantai',
        ]);
        $wisata->setRelation('facilities', collect());
        $viewWisata = $this->view('user.wisata-detail', [
            'touristPlace' => $wisata,
            'otherWisata' => collect(),
            'nearbyLodgings' => collect(),
        ]);
        $viewWisata->assertSee('Instagram Usaha');
        $viewWisata->assertSee('@pantaiuji');
        $viewWisata->assertSee('https://www.instagram.com/pantaiuji/');

        // HangoutPlace test
        $hangout = new HangoutPlace([
            'id' => 9993,
            'name' => 'Kopi Uji Instagram',
            'description' => 'Deskripsi uji kafe',
            'operational_hours' => '10.00 - 23.00 WIB',
            'instagram' => 'kopiuji',
            'status' => 'approved',
            'manager_name' => 'Barista',
        ]);
        $hangout->setRelation('facilities', collect());
        $viewHangout = $this->view('user.nongkrong-detail', [
            'hangoutPlace' => $hangout,
            'otherNongkrong' => collect(),
            'nearbyLodgings' => collect(),
        ]);
        $viewHangout->assertSee('Instagram Usaha');
        $viewHangout->assertSee('@kopiuji');
        $viewHangout->assertSee('https://www.instagram.com/kopiuji/');
    }

    public function test_public_pages_do_not_render_instagram_when_empty()
    {
        $lodging = new Lodging([
            'id' => 9994,
            'name' => 'Hotel Tanpa IG',
            'description' => 'Deskripsi uji',
            'operational_hours' => '24 Jam',
            'instagram' => null,
            'status' => 'approved',
        ]);
        $lodging->setRelation('facilities', collect());
        $view = $this->view('user.penginapan-detail', [
            'lodging' => $lodging,
            'otherLodgings' => collect(),
            'nearbyWisata' => collect(),
        ]);
        $view->assertDontSee('@undefined');
        $view->assertDontSee('Instagram Usaha');
    }
}
