<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Owner;
use App\Models\Lodging;
use App\Models\HangoutPlace;
use App\Models\TouristPlace;
use App\Models\WebsiteVisitor;

class AdminDashboardStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_displays_correct_statistics(): void
    {
        $role = Role::create(['name' => 'admin', 'label' => 'Administrator']);
        $admin = User::factory()->create(['role_id' => $role->id]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('totalOwners', Owner::count());
        $response->assertViewHas('totalHangouts', HangoutPlace::count());
        $response->assertViewHas('totalLodgings', Lodging::count());
        $response->assertViewHas('totalTours', TouristPlace::count());
        $response->assertViewHas('totalViews', WebsiteVisitor::count());
    }
}
