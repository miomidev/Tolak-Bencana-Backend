<?php

namespace Tests\Feature\Admin;

use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserManagementTest extends AdminTestCase
{
    public function test_admin_can_view_users_list(): void
    {
        $this->actingAsAdmin();

        $this->createRegularUser(['name' => 'Warga Kaliwedi']);

        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Warga Kaliwedi');
    }

    public function test_admin_can_view_create_and_edit_pages(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.users.create'))->assertOk();

        $user = $this->createRegularUser();

        $this->get(route('admin.users.edit', $user))
            ->assertOk()
            ->assertSee($user->name);
    }

    public function test_admin_can_store_user(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.users.store'), [
            'name' => 'Pengguna Baru',
            'email' => 'baru@kaliwedi.test',
            'username' => 'baru_kaliwedi',
            'phone' => '081200000000',
            'role' => 'user',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['email' => 'baru@kaliwedi.test', 'role' => 'user', 'is_active' => true]);

        $user = User::where('email', 'baru@kaliwedi.test')->first();
        $this->assertTrue(Hash::check('password123', $user->password));

        $this->assertDatabaseHas('audit_logs', ['action' => 'user.created']);
    }

    public function test_store_user_requires_valid_data(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.users.store'), [])
            ->assertSessionHasErrors(['name', 'email', 'username', 'role', 'password']);
    }

    public function test_store_user_rejects_duplicate_email_and_username(): void
    {
        $this->actingAsAdmin();

        $this->createRegularUser(['email' => 'taken@kaliwedi.test', 'username' => 'taken_username']);

        $this->post(route('admin.users.store'), [
            'name' => 'Duplikat',
            'email' => 'taken@kaliwedi.test',
            'username' => 'taken_username',
            'role' => 'user',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
            ->assertSessionHasErrors(['email', 'username']);
    }

    public function test_admin_can_update_user(): void
    {
        $this->actingAsAdmin();

        $user = $this->createRegularUser(['name' => 'Nama Lama']);

        $this->put(route('admin.users.update', $user), [
            'name' => 'Nama Baru',
            'email' => $user->email,
            'username' => $user->username,
            'phone' => '081200000000',
            'role' => 'user',
            'is_active' => 1,
        ])
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['name' => 'Nama Baru']);
    }

    public function test_admin_cannot_change_own_role(): void
    {
        $admin = $this->actingAsAdmin();

        $this->from(route('admin.users.edit', $admin))
            ->put(route('admin.users.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'username' => $admin->username,
                'role' => 'user',
            ])
            ->assertSessionHasErrors('role');

        $this->assertSame('superadmin', $admin->fresh()->role);
    }

    public function test_admin_can_toggle_user_active_status(): void
    {
        $this->actingAsAdmin();

        $user = $this->createRegularUser();

        $this->post(route('admin.users.toggle', $user))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->assertSame(0, $user->fresh()->is_active);
    }

    public function test_admin_cannot_toggle_own_account(): void
    {
        $admin = $this->actingAsAdmin();

        $this->from(route('admin.users.edit', $admin))
            ->post(route('admin.users.toggle', $admin))
            ->assertSessionHasErrors('toggle');

        $this->assertSame(1, $admin->fresh()->is_active);
    }

    public function test_admin_can_reset_user_password(): void
    {
        $this->actingAsAdmin();

        $user = $this->createRegularUser();

        $this->from(route('admin.users.edit', $user))
            ->post(route('admin.users.reset-password', $user))
            ->assertRedirect(route('admin.users.edit', $user))
            ->assertSessionHas('info');

        $fresh = $user->fresh();

        $this->assertFalse(Hash::needsRehash($fresh->password));
        $this->assertFalse(Hash::check('password123', $fresh->password));
        $this->assertDatabaseHas('audit_logs', ['action' => 'user.password_reset']);
    }

    public function test_admin_can_update_user_notifications(): void
    {
        $this->actingAsAdmin();

        $user = $this->createRegularUser();

        $this->from(route('admin.users.edit', $user))
            ->post(route('admin.users.notifications', $user), [
                'flood_alert' => 1,
                'report_update' => 0,
                'emergency_alert' => 1,
                'location_alert' => 0,
            ])
            ->assertSessionHas('success');

        $setting = NotificationSetting::where('user_id', $user->id)->first();
        $this->assertNotNull($setting);
        $this->assertTrue($setting->flood_alert);
        $this->assertFalse($setting->report_update);
    }

    public function test_update_notifications_creates_setting_when_missing(): void
    {
        $this->actingAsAdmin();

        $user = $this->createRegularUser();

        $this->post(route('admin.users.notifications', $user), [
            'flood_alert' => 1,
            'report_update' => 1,
            'emergency_alert' => 1,
            'location_alert' => 1,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('notification_settings', ['user_id' => $user->id]);
    }
}
