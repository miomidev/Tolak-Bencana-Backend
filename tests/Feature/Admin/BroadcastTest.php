<?php

namespace Tests\Feature\Admin;

use App\Models\Notification;
use App\Models\NotificationSetting;

class BroadcastTest extends AdminTestCase
{
    public function test_admin_can_view_broadcast_create_page(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.broadcast.create'))
            ->assertOk()
            ->assertSee('Siaran');
    }

    public function test_admin_can_view_broadcast_history(): void
    {
        $this->actingAsAdmin();

        $user = $this->createRegularUser();

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Waspada Banjir',
            'body' => 'Ketinggian air meningkat.',
            'type' => 'flood_alert',
            'is_read' => false,
            'sent_at' => now(),
        ]);

        $this->get(route('admin.broadcast.history'))
            ->assertOk()
            ->assertSee('Waspada Banjir');
    }

    public function test_admin_can_send_broadcast_to_active_users(): void
    {
        $this->actingAsAdmin();

        $this->createRegularUser();
        $this->createRegularUser();
        $this->createUser(['role' => 'user', 'is_active' => false]);
        $this->createAdmin();

        $this->post(route('admin.broadcast.send'), [
            'title' => 'Siaga Banjir',
            'body' => 'Waspada banjir rob.',
            'type' => 'info',
        ])
            ->assertRedirect(route('admin.broadcast.history'))
            ->assertSessionHas('success');

        $this->assertSame(4, Notification::where('title', 'Siaga Banjir')->count());
        $this->assertDatabaseHas('audit_logs', ['action' => 'broadcast.sent']);
    }

    public function test_send_broadcast_respects_user_notification_preferences(): void
    {
        $this->actingAsAdmin();

        $optedOut = $this->createRegularUser();
        NotificationSetting::create([
            'user_id' => $optedOut->id,
            'flood_alert' => false,
        ]);

        $this->createRegularUser();

        $this->post(route('admin.broadcast.send'), [
            'title' => 'Siaga Banjir',
            'body' => 'Waspada banjir.',
            'type' => 'flood_alert',
        ])->assertRedirect(route('admin.broadcast.history'));

        $this->assertSame(2, Notification::where('title', 'Siaga Banjir')->count());
    }

    public function test_send_broadcast_requires_title_body_and_valid_type(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.broadcast.send'), [])
            ->assertSessionHasErrors(['title', 'body', 'type']);

        $this->post(route('admin.broadcast.send'), [
            'title' => 'Judul',
            'body' => 'Isi',
            'type' => 'unknown',
        ])->assertSessionHasErrors('type');
    }
}
