<?php

namespace Tests\Feature\Admin;

use App\Models\AlertLevel;

class AlertLevelTest extends AdminTestCase
{
    private array $validData = [
        'name' => 'Siaga 1',
        'color' => '#FFD700',
        'min_height' => 100,
        'max_height' => 150,
        'is_active' => true,
    ];

    public function test_admin_can_view_alert_levels_list(): void
    {
        $this->actingAsAdmin();

        AlertLevel::create($this->validData);

        $this->get(route('admin.alert-levels.index'))
            ->assertOk()
            ->assertSee('Siaga 1');
    }

    public function test_admin_can_view_create_and_edit_pages(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.alert-levels.create'))->assertOk();

        $level = AlertLevel::create($this->validData);

        $this->get(route('admin.alert-levels.edit', $level))
            ->assertOk()
            ->assertSee($level->name);
    }

    public function test_admin_can_store_alert_level(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.alert-levels.store'), $this->validData)
            ->assertRedirect(route('admin.alert-levels.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('alert_levels', ['name' => 'Siaga 1']);
    }

    public function test_store_alert_level_requires_name_color_and_min_height(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.alert-levels.store'), [])
            ->assertSessionHasErrors(['name', 'color', 'min_height']);
    }

    public function test_store_alert_level_rejects_max_below_min(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.alert-levels.store'), [...$this->validData, 'max_height' => 50])
            ->assertSessionHasErrors('max_height');
    }

    public function test_admin_can_update_alert_level(): void
    {
        $this->actingAsAdmin();

        $level = AlertLevel::create($this->validData);

        $this->put(route('admin.alert-levels.update', $level), [...$this->validData, 'name' => 'Siaga 2'])
            ->assertRedirect(route('admin.alert-levels.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('alert_levels', ['name' => 'Siaga 2']);
    }

    public function test_admin_can_toggle_alert_level(): void
    {
        $this->actingAsAdmin();

        $level = AlertLevel::create($this->validData);

        $this->post(route('admin.alert-levels.toggle', $level))
            ->assertRedirect(route('admin.alert-levels.index'))
            ->assertSessionHas('success');

        $this->assertFalse($level->fresh()->is_active);
    }

    public function test_admin_can_delete_alert_level(): void
    {
        $this->actingAsAdmin();

        $level = AlertLevel::create($this->validData);

        $this->delete(route('admin.alert-levels.destroy', $level))
            ->assertRedirect(route('admin.alert-levels.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('alert_levels', ['id' => $level->id]);
    }
}
