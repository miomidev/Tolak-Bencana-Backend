<?php

namespace Tests\Feature\Admin;

use App\Models\DisasterZone;

class DisasterZoneCrudTest extends AdminTestCase
{
    private array $validData = [
        'name' => 'Zona Rawan Blok C',
        'polygon' => '[[-6.3290,108.3550],[-6.3295,108.3555],[-6.3290,108.3560]]',
        'risk_level' => 'high',
        'description' => 'Wilayah rawan genangan.',
    ];

    public function test_admin_can_view_zones_list(): void
    {
        $this->actingAsAdmin();

        DisasterZone::create($this->validData);

        $this->get(route('admin.zones.index'))
            ->assertOk()
            ->assertSee('Zona Rawan Blok C');
    }

    public function test_zones_list_can_be_filtered_by_risk(): void
    {
        $this->actingAsAdmin();

        DisasterZone::create($this->validData);
        DisasterZone::create([...$this->validData, 'name' => 'Zona Aman', 'risk_level' => 'low']);

        $this->get(route('admin.zones.index', ['risk' => 'low']))
            ->assertOk()
            ->assertSee('Zona Aman')
            ->assertDontSee('Zona Rawan Blok C');
    }

    public function test_admin_can_view_create_and_edit_pages(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.zones.create'))->assertOk();

        $zone = DisasterZone::create($this->validData);

        $this->get(route('admin.zones.edit', $zone))
            ->assertOk()
            ->assertSee($zone->name);
    }

    public function test_admin_can_store_zone(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.zones.store'), $this->validData)
            ->assertRedirect(route('admin.zones.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('disaster_zones', ['name' => 'Zona Rawan Blok C']);
    }

    public function test_store_zone_requires_name_polygon_and_risk_level(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.zones.store'), [])
            ->assertSessionHasErrors(['name', 'polygon', 'risk_level']);
    }

    public function test_store_zone_rejects_invalid_polygon(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.zones.store'), [...$this->validData, 'polygon' => 'not-a-json'])
            ->assertSessionHasErrors('polygon');
    }

    public function test_store_zone_rejects_invalid_risk_level(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.zones.store'), [...$this->validData, 'risk_level' => 'extreme'])
            ->assertSessionHasErrors('risk_level');
    }

    public function test_admin_can_update_zone(): void
    {
        $this->actingAsAdmin();

        $zone = DisasterZone::create($this->validData);

        $this->put(route('admin.zones.update', $zone), [...$this->validData, 'name' => 'Zona Rawan Blok D'])
            ->assertRedirect(route('admin.zones.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('disaster_zones', ['name' => 'Zona Rawan Blok D']);
    }

    public function test_admin_can_delete_zone(): void
    {
        $this->actingAsAdmin();

        $zone = DisasterZone::create($this->validData);

        $this->delete(route('admin.zones.destroy', $zone))
            ->assertRedirect(route('admin.zones.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('disaster_zones', ['id' => $zone->id]);
    }
}
