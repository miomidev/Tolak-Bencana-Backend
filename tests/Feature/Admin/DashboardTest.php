<?php

namespace Tests\Feature\Admin;

use App\Models\Report;
use App\Models\Shelter;

class DashboardTest extends AdminTestCase
{
    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_regular_user_is_rejected_from_dashboard(): void
    {
        $this->actingAs($this->createRegularUser());

        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    public function test_admin_can_view_dashboard(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_dashboard_displays_statistics(): void
    {
        $this->actingAsAdmin();

        $reporter = $this->createRegularUser();

        Report::create([
            'user_id' => $reporter->id,
            'latitude' => -6.3290,
            'longitude' => 108.3550,
            'address' => 'Jl. Raya Kaliwedi',
            'description' => 'Air mulai naik',
            'severity' => 'high',
            'status' => 'pending',
            'reported_at' => now(),
        ]);

        Shelter::create([
            'name' => 'Balai Desa Kaliwedi',
            'latitude' => -6.3290,
            'longitude' => 108.3550,
            'address' => 'Kaliwedi',
            'capacity' => 100,
            'current_occupancy' => 20,
            'status' => 'active',
        ]);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Balai Desa Kaliwedi');
    }
}
