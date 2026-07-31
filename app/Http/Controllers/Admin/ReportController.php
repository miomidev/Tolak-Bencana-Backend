<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationSetting;
use App\Models\Report;
use App\Support\Audit;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::query()
            ->with('user:id,name,username,avatar_url')
            ->withCount(['likes', 'comments'])
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('severity'), function ($q) use ($request) {
                $q->where('severity', $request->severity);
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->where('category', $request->category);
            })
            ->when($request->filled('from'), function ($q) use ($request) {
                $q->whereDate('reported_at', '>=', $request->from);
            })
            ->when($request->filled('to'), function ($q) use ($request) {
                $q->whereDate('reported_at', '<=', $request->to);
            })
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('address', 'like', "%{$request->q}%")
                        ->orWhere('description', 'like', "%{$request->q}%");
                });
            })
            ->orderBy('reported_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $categories = Report::distinct()->pluck('category')->filter()->values();

        return view('admin.reports.index', compact('reports', 'categories'));
    }

    public function show(Report $report)
    {
        $report->load(['user:id,name,username,avatar_url,phone', 'comments.user:id,name,avatar_url'])
            ->loadCount(['likes', 'comments']);

        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:verified,resolved,rejected',
            'rejection_reason' => 'nullable|string|max:500',
        ], [
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status tidak valid.',
        ]);

        $status = $request->input('status');
        $report->status = $status;
        $report->rejection_reason = $status === 'rejected' ? $request->input('rejection_reason') : null;
        $report->save();

        $labels = [
            'verified' => 'Terverifikasi',
            'resolved' => 'Selesai',
            'rejected' => 'Ditolak',
        ];

        $this->notifyReporter($report, $status);

        Audit::log(
            'report.status_changed',
            'Laporan "'.$report->address.'" dari "'.($report->user?->name ?? 'Pengguna').'" ditandai: '.($labels[$status] ?? $status).'.',
            $report
        );

        return redirect()->back()
            ->with('success', 'Laporan dari "'.($report->user?->name ?? 'Pengguna').'" ditandai: '.($labels[$status] ?? $status).'.');
    }

    /**
     * Notify the reporting citizen about the moderation result.
     */
    private function notifyReporter(Report $report, string $status): void
    {
        $setting = NotificationSetting::where('user_id', $report->user_id)->first();

        if ($setting && ! $setting->report_update) {
            return;
        }

        $messages = [
            'verified' => ['Laporan Anda Terverifikasi', 'Laporan banjir Anda di "'.$report->address.'" telah diverifikasi oleh tim BPBD. Terima kasih atas partisipasinya.'],
            'resolved' => ['Laporan Anda Selesai Ditangani', 'Kondisi di "'.$report->address.'" telah surut dan laporan Anda ditandai selesai.'],
            'rejected' => ['Laporan Anda Ditolak', 'Laporan Anda di "'.$report->address.'" ditolak. '.($report->rejection_reason ? 'Alasan: '.$report->rejection_reason : 'Silakan kirim laporan ulang dengan data yang lebih lengkap.')],
        ];

        [$title, $body] = $messages[$status] ?? ['Pembaruan Laporan', 'Status laporan Anda telah diperbarui.'];

        Notification::create([
            'user_id' => $report->user_id,
            'title' => $title,
            'body' => $body,
            'type' => 'report_update',
            'is_read' => false,
            'sent_at' => now(),
        ]);
    }

    public function exportExcel(Request $request)
    {
        $reports = Report::query()
            ->with('user:id,name,username')
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('from'), function ($q) use ($request) {
                $q->whereDate('reported_at', '>=', $request->from);
            })
            ->when($request->filled('to'), function ($q) use ($request) {
                $q->whereDate('reported_at', '<=', $request->to);
            })
            ->orderBy('reported_at', 'desc')
            ->get();

        $filename = 'laporan-banjir-'.now()->format('Y-m-d-His').'.xlsx';

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Banjir');

        $headers = ['No', 'Nama Pelapor', 'Kategori', 'Lokasi / Alamat', 'Deskripsi Kejadian', 'Tingkat Keparahan', 'Status', 'Koordinat', 'Tanggal Laporan'];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:I1')->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:I1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF0F767E');

        $severityLabels = ['low' => 'Rendah', 'medium' => 'Waspada', 'high' => 'Siaga', 'critical' => 'Awas / Kritis'];
        $statusLabels = ['pending' => 'Pending', 'verified' => 'Terverifikasi', 'resolved' => 'Selesai', 'rejected' => 'Ditolak'];

        $row = 2;
        foreach ($reports as $index => $report) {
            $sheet->fromArray([
                $index + 1,
                $report->user?->name ?? 'Warga',
                $report->category,
                $report->address,
                $report->description,
                $severityLabels[$report->severity] ?? $report->severity,
                $statusLabels[$report->status] ?? $report->status,
                $report->latitude.', '.$report->longitude,
                $report->reported_at?->format('d-m-Y H:i'),
            ], null, 'A'.$row);
            $row++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
