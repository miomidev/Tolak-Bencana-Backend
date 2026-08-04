<?php

namespace Tests\Feature\Admin;

use App\Models\Report;
use App\Models\ReportComment;

class CommentModerationTest extends AdminTestCase
{
    private function createReport(): Report
    {
        $reporter = $this->createRegularUser();

        return Report::create([
            'user_id' => $reporter->id,
            'latitude' => -6.3290,
            'longitude' => 108.3550,
            'address' => 'Jl. Merdeka No. 10',
            'description' => 'Air menggenangi jalan.',
            'severity' => 'medium',
            'status' => 'pending',
            'reported_at' => now(),
        ]);
    }

    public function test_admin_can_view_comments_list(): void
    {
        $this->actingAsAdmin();

        $report = $this->createReport();

        ReportComment::create([
            'user_id' => $report->user_id,
            'report_id' => $report->id,
            'comment' => 'Air di sini sudah setinggi lutut.',
        ]);

        $this->get(route('admin.comments.index'))
            ->assertOk()
            ->assertSee('Air di sini sudah setinggi lutut.');
    }

    public function test_comments_list_can_filter_by_status(): void
    {
        $this->actingAsAdmin();

        $report = $this->createReport();

        ReportComment::create([
            'user_id' => $report->user_id,
            'report_id' => $report->id,
            'comment' => 'Komentar aktif',
            'is_active' => true,
        ]);
        ReportComment::create([
            'user_id' => $report->user_id,
            'report_id' => $report->id,
            'comment' => 'Komentar disembunyikan',
            'is_active' => false,
        ]);

        $this->get(route('admin.comments.index', ['status' => 'hidden']))
            ->assertOk()
            ->assertSee('Komentar disembunyikan')
            ->assertDontSee('Komentar aktif');
    }

    public function test_admin_can_view_comment_detail(): void
    {
        $this->actingAsAdmin();

        $report = $this->createReport();
        $comment = ReportComment::create([
            'user_id' => $report->user_id,
            'report_id' => $report->id,
            'comment' => 'Detail komentar',
        ]);

        $this->get(route('admin.comments.show', $comment))
            ->assertOk()
            ->assertSee('Detail komentar');
    }

    public function test_admin_can_hide_and_show_comment(): void
    {
        $this->actingAsAdmin();

        $report = $this->createReport();
        $comment = ReportComment::create([
            'user_id' => $report->user_id,
            'report_id' => $report->id,
            'comment' => 'Komentar moderasi',
        ]);

        $this->post(route('admin.comments.toggle', $comment))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertFalse($comment->fresh()->is_active);
        $this->assertDatabaseHas('audit_logs', ['action' => 'comment.hidden']);
    }

    public function test_admin_can_delete_comment(): void
    {
        $this->actingAsAdmin();

        $report = $this->createReport();
        $comment = ReportComment::create([
            'user_id' => $report->user_id,
            'report_id' => $report->id,
            'comment' => 'Komentar akan dihapus',
        ]);

        $this->delete(route('admin.comments.destroy', $comment))
            ->assertRedirect(route('admin.comments.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('report_comments', ['id' => $comment->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'comment.deleted']);
    }
}
