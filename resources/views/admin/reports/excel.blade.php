<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta charset="utf-8">
<style>
    .header { font-family: sans-serif; font-size: 16px; font-weight: bold; text-align: center; }
    .subheader { font-family: sans-serif; font-size: 12px; text-align: center; margin-bottom: 20px; }
    table { border-collapse: collapse; width: 100%; font-family: sans-serif; font-size: 11px; }
    th { background-color: #0F767E; color: white; border: 1px solid #ddd; padding: 10px; font-weight: bold; text-align: left; }
    td { border: 1px solid #ddd; padding: 8px; }
    .status-pending { background-color: #FEF3C7; color: #D97706; font-weight: bold; }
    .status-verified { background-color: #DBEAFE; color: #2563EB; font-weight: bold; }
    .status-resolved { background-color: #D1FAE5; color: #059669; font-weight: bold; }
    .status-rejected { background-color: #FEE2E2; color: #DC2626; font-weight: bold; }
    .severity-low { color: #059669; font-weight: bold; }
    .severity-medium { color: #D97706; font-weight: bold; }
    .severity-high { color: #EA580C; font-weight: bold; }
    .severity-critical { color: #DC2626; font-weight: bold; }
</style>
</head>
<body>
    <div class="header">KALIWEDI - LAPORAN MITIGASI BANJIR ROB</div>
    <div class="subheader">Laporan Kebencanaan Kabupaten Indramayu - Diunduh pada: {{ now()->format('d M Y H:i') }}</div>
    
    <table>
        <thead>
            <tr>
                <th style="background-color: #0F767E; color: white; font-weight: bold; border: 1px solid #ddd;">No</th>
                <th style="background-color: #0F767E; color: white; font-weight: bold; border: 1px solid #ddd;">Nama Pelapor</th>
                <th style="background-color: #0F767E; color: white; font-weight: bold; border: 1px solid #ddd;">Kategori</th>
                <th style="background-color: #0F767E; color: white; font-weight: bold; border: 1px solid #ddd;">Lokasi / Alamat</th>
                <th style="background-color: #0F767E; color: white; font-weight: bold; border: 1px solid #ddd;">Deskripsi Kejadian</th>
                <th style="background-color: #0F767E; color: white; font-weight: bold; border: 1px solid #ddd;">Tingkat Keparahan</th>
                <th style="background-color: #0F767E; color: white; font-weight: bold; border: 1px solid #ddd;">Status</th>
                <th style="background-color: #0F767E; color: white; font-weight: bold; border: 1px solid #ddd;">Tanggal Laporan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $index => $report)
                <tr>
                    <td style="border: 1px solid #ddd;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #ddd;">{{ $report->user?->name ?? 'Warga' }}</td>
                    <td style="border: 1px solid #ddd;">{{ $report->category }}</td>
                    <td style="border: 1px solid #ddd;">{{ $report->address }}</td>
                    <td style="border: 1px solid #ddd;">{{ $report->description }}</td>
                    
                    @php
                        $sevColor = '#059669';
                        if ($report->severity === 'medium') $sevColor = '#D97706';
                        elseif ($report->severity === 'high') $sevColor = '#EA580C';
                        elseif ($report->severity === 'critical') $sevColor = '#DC2626';

                        $stBg = '#FEF3C7'; $stColor = '#D97706'; $stLabel = 'PENDING';
                        if ($report->status === 'verified') { $stBg = '#DBEAFE'; $stColor = '#2563EB'; $stLabel = 'TERVERIFIKASI'; }
                        elseif ($report->status === 'resolved') { $stBg = '#D1FAE5'; $stColor = '#059669'; $stLabel = 'SELESAI'; }
                        elseif ($report->status === 'rejected') { $stBg = '#FEE2E2'; $stColor = '#DC2626'; $stLabel = 'DITOLAK'; }
                    @endphp

                    <td style="border: 1px solid #ddd; color: {{ $sevColor }}; font-weight: bold;">
                        {{ strtoupper($report->severity) }}
                    </td>
                    <td style="border: 1px solid #ddd; background-color: {{ $stBg }}; color: {{ $stColor }}; font-weight: bold; text-align: center;">
                        {{ $stLabel }}
                    </td>
                    <td style="border: 1px solid #ddd;">{{ $report->reported_at?->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
