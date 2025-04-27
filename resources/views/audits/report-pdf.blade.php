<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Audit - {{ $audit->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4a86e8;
            padding-bottom: 10px;
        }
        .logo {
            width: 100px;
            height: auto;
        }
        h1 {
            font-size: 24px;
            margin: 10px 0;
            color: #2c5282;
        }
        h2 {
            font-size: 20px;
            margin: 20px 0 10px;
            color: #2c5282;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        h3 {
            font-size: 18px;
            margin: 15px 0 5px;
            color: #2c5282;
        }
        .audit-info {
            margin-bottom: 20px;
        }
        .audit-info-item {
            margin: 5px 0;
        }
        .info-label {
            font-weight: bold;
            width: 180px;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #cbd5e0;
        }
        th {
            background-color: #edf2f7;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px 10px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-minor {
            background-color: #fef6db;
            color: #92400e;
        }
        .badge-major {
            background-color: #fed7d7;
            color: #c53030;
        }
        .badge-observation {
            background-color: #e9f6fd;
            color: #2b6cb0;
        }
        .badge-opportunity {
            background-color: #e6fffa;
            color: #2c7a7b;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN AUDIT MUTU INTERNAL</h1>
        <h2>{{ $audit->title }}</h2>
    </div>

    <div class="audit-info">
        <div class="audit-info-item">
            <span class="info-label">Tanggal Audit:</span>
            <span>{{ $audit->audit_date_start->format('d/m/Y') }} - {{ $audit->audit_date_end->format('d/m/Y') }}</span>
        </div>
        <div class="audit-info-item">
            <span class="info-label">Auditor Utama:</span>
            <span>{{ $audit->leadAuditor->name ?? '-' }}</span>
        </div>
        <div class="audit-info-item">
            <span class="info-label">Tim Auditor:</span>
            <span>{{ $audit->auditors->pluck('name')->implode(', ') }}</span>
        </div>
        <div class="audit-info-item">
            <span class="info-label">Fakultas:</span>
            <span>{{ $audit->faculty->name ?? '-' }}</span>
        </div>
        @if($audit->department)
        <div class="audit-info-item">
            <span class="info-label">Departemen/Prodi:</span>
            <span>{{ $audit->department->name }}</span>
        </div>
        @endif
        @if($audit->unit)
        <div class="audit-info-item">
            <span class="info-label">Unit:</span>
            <span>{{ $audit->unit->name }}</span>
        </div>
        @endif
        <div class="audit-info-item">
            <span class="info-label">Status:</span>
            <span>{{ ucfirst($audit->status) }}</span>
        </div>
    </div>

    <h2>Deskripsi Audit</h2>
    <p>{{ $audit->description }}</p>

    <h2>Temuan Audit</h2>
    
    @if($findings->isEmpty())
    <p><em>Tidak ada temuan yang tercatat.</em></p>
    @else
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tipe</th>
                <th>Standar</th>
                <th>Deskripsi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($findings as $index => $finding)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <span class="badge badge-{{ $finding->type }}">
                        @switch($finding->type)
                            @case('minor')
                                Minor
                                @break
                            @case('major')
                                Major
                                @break
                            @case('observation')
                                Observasi
                                @break
                            @case('opportunity')
                                Peluang
                                @break
                            @default
                                {{ $finding->type }}
                        @endswitch
                    </span>
                </td>
                <td>{{ $finding->standard->code ?? '-' }}</td>
                <td>{{ $finding->description }}</td>
                <td>
                    @switch($finding->status)
                        @case('open')
                            Terbuka
                            @break
                        @case('responded')
                            Direspon
                            @break
                        @case('in_progress')
                            Dalam Proses
                            @break
                        @case('verified')
                            Terverifikasi
                            @break
                        @case('closed')
                            Ditutup
                            @break
                        @default
                            {{ $finding->status }}
                    @endswitch
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <h2>Detail Temuan dan Tindak Lanjut</h2>
    
    @if($findings->isEmpty())
    <p><em>Tidak ada detail temuan yang tersedia.</em></p>
    @else
        @foreach($findings as $index => $finding)
        <div @if(!$loop->last) class="page-break" @endif>
            <h3>Temuan #{{ $index + 1 }}</h3>
            <div class="audit-info">
                <div class="audit-info-item">
                    <span class="info-label">Tipe:</span>
                    <span>
                        @switch($finding->type)
                            @case('minor')
                                Ketidaksesuaian Minor
                                @break
                            @case('major')
                                Ketidaksesuaian Major
                                @break
                            @case('observation')
                                Observasi
                                @break
                            @case('opportunity')
                                Peluang Perbaikan
                                @break
                            @default
                                {{ $finding->type }}
                        @endswitch
                    </span>
                </div>
                <div class="audit-info-item">
                    <span class="info-label">Standar:</span>
                    <span>{{ $finding->standard->name ?? '-' }} ({{ $finding->standard->code ?? '-' }})</span>
                </div>
                <div class="audit-info-item">
                    <span class="info-label">Deskripsi:</span>
                    <span>{{ $finding->description }}</span>
                </div>
                <div class="audit-info-item">
                    <span class="info-label">Target Penyelesaian:</span>
                    <span>{{ $finding->target_completion_date ? $finding->target_completion_date->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="audit-info-item">
                    <span class="info-label">Status:</span>
                    <span>
                        @switch($finding->status)
                            @case('open')
                                Terbuka
                                @break
                            @case('responded')
                                Direspon
                                @break
                            @case('in_progress')
                                Dalam Proses
                                @break
                            @case('verified')
                                Terverifikasi
                                @break
                            @case('closed')
                                Ditutup
                                @break
                            @default
                                {{ $finding->status }}
                        @endswitch
                    </span>
                </div>
                
                @if($finding->followup_action)
                <div class="audit-info-item">
                    <span class="info-label">Tindak Lanjut:</span>
                    <span>{{ $finding->followup_action }}</span>
                </div>
                <div class="audit-info-item">
                    <span class="info-label">Tanggal Tindak Lanjut:</span>
                    <span>{{ $finding->followup_date ? $finding->followup_date->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="audit-info-item">
                    <span class="info-label">Oleh:</span>
                    <span>{{ $finding->followupBy->name ?? '-' }}</span>
                </div>
                @endif
                
                @if($finding->verification_notes)
                <div class="audit-info-item">
                    <span class="info-label">Catatan Verifikasi:</span>
                    <span>{{ $finding->verification_notes }}</span>
                </div>
                <div class="audit-info-item">
                    <span class="info-label">Tanggal Verifikasi:</span>
                    <span>{{ $finding->verification_date ? $finding->verification_date->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="audit-info-item">
                    <span class="info-label">Diverifikasi Oleh:</span>
                    <span>{{ $finding->verifiedBy->name ?? '-' }}</span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    @endif

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Penjaminan Mutu Internal (SPMI)</p>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html> 