<?php

namespace Tests\Feature\Admin;

use App\Models\Notification;
use App\Models\Report;

class ReportModerationTest extends AdminTestCase
{
    private function createReport(array $attributes = []): Report
    {
        $reporter = $this->createRegularUser();

        return Report::create(array_merge([
            'user_id' => $reporter->id,
            'latitude' => -6.3290,
            'longitude' => 108.3550,
            'address' => 'Jl. Melati No. 5',
            'description' => 'Genangan air di depan rumah.',
            'severity' => 'medium',
            'status' => 'pending',
            'reported_at' => now(),
        ], $attributes));
    }

    public function test_admin_can_view_reports_list(): void
    {
        $this->actingAsAdmin();

        $this->createReport(['address' => 'Jl. Melati No. 5']);

        $this->get(route('admin.reports.index'))
            ->assertOk()
            ->assertSee('Jl. Melati No. 5');
    }

    public function test_reports_list_can_be_filtered(): void
    {
        $this->actingAsAdmin();

        $this->createReport(['address' => 'Jl. Melati No. 5', 'status' => 'pending']);
        $this->createReport(['address' => 'Jl. Kenanga No. 2', 'status' => 'verified']);

        $this->get(route('admin.reports.index', ['status' => 'verified']))
            ->assertOk()
            ->assertSee('Jl. Kenanga No. 2')
            ->assertDontSee('Jl. Melati No. 5');
    }

    public function test_admin_can_view_report_detail(): void
    {
        $this->actingAsAdmin();

        $report = $this->createReport();

        $this->get(route('admin.reports.show', $report))
            ->assertOk()
            ->assertSee($report->address);
    }

    public function test_admin_can_verify_report(): void
    {
        $this->actingAsAdmin();

        $report = $this->createReport();

        $this->from(route('admin.reports.show', $report))
            ->post(route('admin.reports.status', $report), ['status' => 'verified'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('verified', $report->fresh()->status);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $report->user_id,
            'type' => 'report_update',
        ]);

        $this->assertDatabaseHas('audit_logs', ['action' => 'report.status_changed']);
    }

    public function test_admin_can_resolve_report(): void
    {
        $this->actingAsAdmin();

        $report = $this->createReport();

        $this->post(route('admin.reports.status', $report), ['status' => 'resolved'])
            ->assertRedirect();

        $this->assertSame('resolved', $report->fresh()->status);
    }

    public function test_admin_can_reject_report_with_reason(): void
    {
        $this->actingAsAdmin();

        $report = $this->createReport();

        $this->post(route('admin.reports.status', $report), [
            'status' => 'rejected',
            'rejection_reason' => 'Foto tidak jelas.',
        ])
            ->assertRedirect();

        $this->assertSame('rejected', $report->fresh()->status);
        $this->assertSame('Foto tidak jelas.', $report->fresh()->rejection_reason);

        $notification = Notification::where('user_id', $report->user_id)->where('type', 'report_update')->first();
        $this->assertNotNull($notification);
        $this->assertStringContainsString('Foto tidak jelas.', $notification->body);
    }

    public function test_update_status_requires_valid_status(): void
    {
        $this->actingAsAdmin();

        $report = $this->createReport();

        $this->post(route('admin.reports.status', $report), ['status' => 'invalid'])
            ->assertSessionHasErrors('status');

        $this->assertSame('pending', $report->fresh()->status);
    }

    public function test_admin_can_export_reports_to_excel(): void
    {
        $this->actingAsAdmin();

        $this->createReport(['address' => 'Jl. Melati No. 5']);

        $this->get(route('admin.reports.export'))
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
