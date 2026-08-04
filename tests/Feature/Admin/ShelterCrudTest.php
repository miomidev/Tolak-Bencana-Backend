<?php

namespace Tests\Feature\Admin;

use App\Models\Shelter;

class ShelterCrudTest extends AdminTestCase
{
    private array $validData = [
        'name' => 'Balai Desa Kaliwedi',
        'latitude' => -6.3290,
        'longitude' => 108.3550,
        'address' => 'Jl. Raya Kaliwedi, Indramayu',
        'capacity' => 100,
        'current_occupancy' => 20,
        'status' => 'active',
        'contact' => '081234567890',
    ];

    public function test_guest_cannot_access_shelter_pages(): void
    {
        $this->get(route('admin.shelters.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.shelters.create'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_shelters_list(): void
    {
        $this->actingAsAdmin();

        Shelter::create($this->validData);

        $this->get(route('admin.shelters.index'))
            ->assertOk()
            ->assertSee('Balai Desa Kaliwedi');
    }

    public function test_shelters_list_can_be_filtered(): void
    {
        $this->actingAsAdmin();

        Shelter::create($this->validData);
        Shelter::create([...$this->validData, 'name' => 'SDN Kaliwedi', 'status' => 'inactive']);

        $this->get(route('admin.shelters.index', ['status' => 'inactive']))
            ->assertOk()
            ->assertSee('SDN Kaliwedi')
            ->assertDontSee('Balai Desa Kaliwedi');
    }

    public function test_admin_can_view_create_and_edit_pages(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.shelters.create'))->assertOk();

        $shelter = Shelter::create($this->validData);

        $this->get(route('admin.shelters.edit', $shelter))
            ->assertOk()
            ->assertSee($shelter->name);
    }

    public function test_admin_can_store_shelter(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.shelters.store'), $this->validData)
            ->assertRedirect(route('admin.shelters.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('shelters', ['name' => 'Balai Desa Kaliwedi']);
    }

    public function test_store_shelter_requires_required_fields(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.shelters.store'), [])
            ->assertSessionHasErrors(['name', 'latitude', 'longitude', 'address', 'capacity', 'current_occupancy', 'status']);
    }

    public function test_store_shelter_rejects_occupancy_greater_than_capacity(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.shelters.store'), [...$this->validData, 'current_occupancy' => 200])
            ->assertSessionHasErrors('current_occupancy');
    }

    public function test_admin_can_update_shelter(): void
    {
        $this->actingAsAdmin();

        $shelter = Shelter::create($this->validData);

        $this->put(route('admin.shelters.update', $shelter), [...$this->validData, 'name' => 'Posko Tanggul'])
            ->assertRedirect(route('admin.shelters.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('shelters', ['name' => 'Posko Tanggul']);
    }

    public function test_admin_can_delete_shelter(): void
    {
        $this->actingAsAdmin();

        $shelter = Shelter::create($this->validData);

        $this->delete(route('admin.shelters.destroy', $shelter))
            ->assertRedirect(route('admin.shelters.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('shelters', ['id' => $shelter->id]);
    }
}
