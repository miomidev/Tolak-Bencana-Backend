<?php

namespace Tests\Feature\Admin;

use App\Models\Announcement;

class AnnouncementTest extends AdminTestCase
{
    private array $validData = [
        'key' => 'banjir_warning',
        'message' => 'Waspada potensi banjir di wilayah Kaliwedi.',
        'is_active' => true,
    ];

    public function test_admin_can_view_announcements_list(): void
    {
        $this->actingAsAdmin();

        Announcement::create($this->validData);

        $this->get(route('admin.announcements.index'))
            ->assertOk()
            ->assertSee('banjir_warning');
    }

    public function test_admin_can_view_create_and_edit_pages(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.announcements.create'))->assertOk();

        $announcement = Announcement::create($this->validData);

        $this->get(route('admin.announcements.edit', $announcement))
            ->assertOk()
            ->assertSee($announcement->key);
    }

    public function test_admin_can_store_announcement(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.announcements.store'), $this->validData)
            ->assertRedirect(route('admin.announcements.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('announcements', ['key' => 'banjir_warning']);

        $this->assertDatabaseHas('audit_logs', ['action' => 'announcement.created']);
    }

    public function test_store_announcement_requires_key_and_message(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.announcements.store'), [])
            ->assertSessionHasErrors(['key', 'message']);
    }

    public function test_admin_can_update_announcement(): void
    {
        $this->actingAsAdmin();

        $announcement = Announcement::create($this->validData);

        $this->put(route('admin.announcements.update', $announcement), [...$this->validData, 'message' => 'Pemberitahuan terbaru.'])
            ->assertRedirect(route('admin.announcements.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('announcements', ['message' => 'Pemberitahuan terbaru.']);
    }

    public function test_admin_can_toggle_announcement(): void
    {
        $this->actingAsAdmin();

        $announcement = Announcement::create($this->validData);

        $this->post(route('admin.announcements.toggle', $announcement))
            ->assertRedirect(route('admin.announcements.index'))
            ->assertSessionHas('success');

        $this->assertFalse($announcement->fresh()->is_active);
    }

    public function test_admin_can_delete_announcement(): void
    {
        $this->actingAsAdmin();

        $announcement = Announcement::create($this->validData);

        $this->delete(route('admin.announcements.destroy', $announcement))
            ->assertRedirect(route('admin.announcements.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
    }
}
