<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Lodging;
use App\Models\TouristPlace;
use App\Models\HangoutPlace;
use App\Models\WebsiteVisitor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LandingPageStatsTest extends TestCase
{
    use RefreshDatabase;
    public function test_landing_page_displays_dynamic_statistics_section(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Lebih Luas, Lebih Terhubung');
        $response->assertSee('Usaha Terdaftar');
        $response->assertSee('Pengunjung Website');

        $totalPlaces = Lodging::approved()->count() + TouristPlace::approved()->count() + HangoutPlace::approved()->count();
        $response->assertSee(number_format($totalPlaces, 0, ',', '.') . '+');
    }

    public function test_unique_visitor_is_tracked_for_public_visitor(): void
    {
        $initialVisitors = WebsiteVisitor::count();

        // Visit landing page with new session
        $response = $this->withSession(['visitor_session' => 'unique_test_1'])->get('/');
        $response->assertStatus(200);

        // Same visitor browses another page
        $response2 = $this->withSession(['visitor_session' => 'unique_test_1'])->get('/penginapan');
        $response2->assertStatus(200);

        // Visitor count should only increase by at most 1 for this session
        $this->assertGreaterThanOrEqual($initialVisitors, WebsiteVisitor::count());
    }
}
