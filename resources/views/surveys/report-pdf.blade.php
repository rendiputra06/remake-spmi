<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Survei - {{ $survey->title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            font-size: 12px;
        }

        .container {
            width: 100%;
        }

        h1 {
            font-size: 18px;
            color: #151B54;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 16px;
            color: #151B54;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        h3 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .survey-header {
            margin-bottom: 20px;
        }

        .survey-info {
            margin-bottom: 15px;
        }

        .stats-container {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .stat-item {
            text-align: center;
            flex: 1;
        }

        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #151B54;
        }

        .question-card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-size: 10px;
            border-radius: 3px;
            background-color: #777;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .badge-secondary {
            background-color: #6c757d;
        }

        .text-answers {
            margin-top: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        .text-answer {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .text-answer:last-child {
            border-bottom: none;
        }

        .text-muted {
            color: #6c757d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="survey-header">
            <h1>Laporan Hasil Survei: {{ $survey->title }}</h1>
            <p>{{ $survey->description }}</p>

            <div class="survey-info">
                <p><strong>Status:</strong> {{ ucfirst($survey->status) }}</p>
                <p><strong>Periode:</strong>
                    {{ $survey->start_date ? $survey->start_date->format('d M Y') : 'Tidak ditentukan' }}
                    -
                    {{ $survey->end_date ? $survey->end_date->format('d M Y') : 'Tidak dibatasi' }}
                </p>
                <p><strong>Target Responden:</strong> {{ $survey->target_audience ?? 'Umum' }}</p>
                <p><strong>Kategori:</strong> {{ $survey->category ?? 'Tidak dikategorikan' }}</p>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-item">
                <div>Total Responden</div>
                <div class="stat-value">{{ $totalResponses }}</div>
            </div>
            <div class="stat-item">
                <div>Tingkat Penyelesaian</div>
                <div class="stat-value">{{ number_format($completionRate, 1) }}%</div>
            </div>
            <div class="stat-item">
                <div>Jumlah Pertanyaan</div>
                <div class="stat-value">{{ $questions->count() }}</div>
            </div>
        </div>

        <h2>Hasil Per Pertanyaan</h2>

        @foreach($questions as $question)
        <div class="question-card">
            <h3>{{ $loop->iteration }}. {{ $question->question }}</h3>
            <p class="text-muted">
                Tipe:
                <span class="badge badge-info">
                    @switch($question->type)
                    @case('text')
                    Teks
                    @break
                    @case('textarea')
                    Teks Panjang
                    @break
                    @case('number')
                    Angka
                    @break
                    @case('multiple_choice')
                    Pilihan Ganda
                    @break
                    @case('checkbox')
                    Kotak Centang
                    @break
                    @case('scale')
                    Skala
                    @break
                    @case('dropdown')
                    Dropdown
                    @break
                    @default
                    {{ $question->type }}
                    @endswitch
                </span>
                | Total Jawaban: <span class="badge badge-secondary">{{ $questionStats[$question->id]['total_answers'] }}</span>
            </p>

            @if(isset($questionStats[$question->id]))
            @php $stats = $questionStats[$question->id]; @endphp

            @if($stats['type'] === 'bar' || $stats['type'] === 'pie' || $stats['type'] === 'horizontalBar')
            <table>
                <thead>
                    <tr>
                        @if($stats['type'] === 'bar')
                        <th>Nilai</th>
                        <th>Jumlah Responden</th>
                        @elseif($stats['type'] === 'pie')
                        <th>Pilihan</th>
                        <th>Jumlah Dipilih</th>
                        @elseif($stats['type'] === 'horizontalBar')
                        <th>Pilihan</th>
                        <th>Jumlah Dipilih</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['labels'] as $index => $label)
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ $stats['data'][$index] ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
                @if($stats['type'] === 'bar' && isset($stats['average']))
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: left; font-weight: bold;">
                            Rata-rata: {{ number_format($stats['average'], 1) }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
            @elseif($stats['type'] === 'text')
            <div class="text-answers">
                @if(count($stats['answers']) > 0)
                @foreach($stats['answers'] as $answer)
                <div class="text-answer">{{ $answer }}</div>
                @endforeach
                @else
                <p class="text-muted">Tidak ada jawaban</p>
                @endif
            </div>
            @else
            <p class="text-muted">Tipe visualisasi tidak didukung</p>
            @endif
            @else
            <p class="text-muted">Tidak ada data statistik</p>
            @endif
        </div>

        @if(!$loop->last)
        <div class="page-break"></div>
        @endif
        @endforeach
    </div>
</body>

</html>