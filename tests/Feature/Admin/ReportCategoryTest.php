<?php

namespace Tests\Feature\Admin;

use App\Models\ReportCategory;

class ReportCategoryTest extends AdminTestCase
{
    private array $validData = [
        'name' => 'Banjir',
        'icon' => 'water',
        'is_active' => true,
    ];

    public function test_admin_can_view_categories_list(): void
    {
        $this->actingAsAdmin();

        ReportCategory::create($this->validData);

        $this->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSee('Banjir');
    }

    public function test_admin_can_view_create_and_edit_pages(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.categories.create'))->assertOk();

        $category = ReportCategory::create($this->validData);

        $this->get(route('admin.categories.edit', $category))
            ->assertOk()
            ->assertSee($category->name);
    }

    public function test_admin_can_store_category(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.categories.store'), $this->validData)
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('report_categories', ['name' => 'Banjir']);
    }

    public function test_store_category_requires_name(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.categories.store'), [])
            ->assertSessionHasErrors('name');
    }

    public function test_store_category_rejects_duplicate_name(): void
    {
        $this->actingAsAdmin();

        ReportCategory::create($this->validData);

        $this->post(route('admin.categories.store'), $this->validData)
            ->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_category(): void
    {
        $this->actingAsAdmin();

        $category = ReportCategory::create($this->validData);

        $this->put(route('admin.categories.update', $category), [...$this->validData, 'name' => 'Angin Kencang'])
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('report_categories', ['name' => 'Angin Kencang']);
    }

    public function test_update_category_allows_keeping_own_name(): void
    {
        $this->actingAsAdmin();

        $category = ReportCategory::create($this->validData);

        $this->put(route('admin.categories.update', $category), $this->validData)
            ->assertRedirect(route('admin.categories.index'));
    }

    public function test_admin_can_toggle_category(): void
    {
        $this->actingAsAdmin();

        $category = ReportCategory::create($this->validData);

        $this->post(route('admin.categories.toggle', $category))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertFalse($category->fresh()->is_active);
    }

    public function test_admin_can_delete_category(): void
    {
        $this->actingAsAdmin();

        $category = ReportCategory::create($this->validData);

        $this->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('report_categories', ['id' => $category->id]);
    }
}
