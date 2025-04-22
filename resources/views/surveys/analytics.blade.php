<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Survei - {{ $survey->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .survey-header {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            flex: 1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .question-card {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        .text-answers {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 10px;
            background-color: #f8f9fa;
        }

        .text-answer {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
        }

        .text-answer:last-child {
            border-bottom: none;
        }

        .navbar {
            margin-bottom: 20px;
            background-color: #6366f1;
        }

        .navbar-brand {
            color: white;
            font-weight: 600;
        }

        .download-links {
            display: flex;
            gap: 10px;
        }

        .download-links a {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('surveys.index') }}">SPMI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('surveys.index') }}">Daftar Survei</a>
                    </li>
                </ul>
                <div class="download-links">
                    <a href="{{ route('survey-analytics.export-excel', $survey->id) }}" class="btn btn-success btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-excel" viewBox="0 0 16 16">
                            <path d="M5.884 6.68a.5.5 0 1 0-.768.64L7.349 10l-2.233 2.68a.5.5 0 0 0 .768.64L8 10.781l2.116 2.54a.5.5 0 0 0 .768-.641L8.651 10l2.233-2.68a.5.5 0 0 0-.768-.64L8 9.219l-2.116-2.54z" />
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                        </svg>
                        Excel
                    </a>
                    <a href="{{ route('survey-analytics.export-pdf', $survey->id) }}" class="btn btn-danger btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                            <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z" />
                        </svg>
                        PDF
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="survey-header">
            <h1 class="mb-3">{{ $survey->title }}</h1>
            <p class="lead">{{ $survey->description }}</p>
            <div class="row mt-4">
                <div class="col-md-6">
                    <p><strong>Status:</strong>
                        <span class="badge {{ $survey->status === 'active' ? 'bg-success' : ($survey->status === 'draft' ? 'bg-secondary' : 'bg-danger') }}">
                            {{ ucfirst($survey->status) }}
                        </span>
                    </p>
                    <p><strong>Periode:</strong>
                        {{ $survey->start_date ? $survey->start_date->format('d M Y') : 'Tidak ditentukan' }}
                        -
                        {{ $survey->end_date ? $survey->end_date->format('d M Y') : 'Tidak dibatasi' }}
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Target Responden:</strong> {{ $survey->target_audience ?? 'Umum' }}</p>
                    <p><strong>Kategori:</strong> {{ $survey->category ?? 'Tidak dikategorikan' }}</p>
                </div>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-card text-center">
                <h5>Total Responden</h5>
                <h2 class="text-primary">{{ $totalResponses }}</h2>
            </div>
            <div class="stat-card text-center">
                <h5>Tingkat Penyelesaian</h5>
                <h2 class="text-success">{{ number_format($completionRate, 1) }}%</h2>
            </div>
            <div class="stat-card text-center">
                <h5>Jumlah Pertanyaan</h5>
                <h2 class="text-info">{{ $questions->count() }}</h2>
            </div>
        </div>

        <h2 class="mb-4">Hasil Per Pertanyaan</h2>

        @foreach($questions as $question)
        <div class="question-card">
            <h3>{{ $loop->iteration }}. {{ $question->question }}</h3>
            <p class="text-muted">
                Tipe:
                <span class="badge bg-info">
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
                | Total Jawaban: <span class="badge bg-secondary">{{ $questionStats[$question->id]['total_answers'] }}</span>
            </p>

            @if(isset($questionStats[$question->id]))
            @php $stats = $questionStats[$question->id]; @endphp

            @if($stats['type'] === 'bar' || $stats['type'] === 'pie' || $stats['type'] === 'horizontalBar')
            <div class="chart-container">
                <canvas id="chart-question-{{ $question->id }}"></canvas>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('chart-question-{{ $question->id }}').getContext('2d');

                    @if($stats['type'] === 'bar')
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: {
                                !!json_encode($stats['labels']) !!
                            },
                            datasets: [{
                                label: 'Jumlah Responden',
                                data: {
                                    !!json_encode($stats['data']) !!
                                },
                                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                                borderColor: 'rgba(99, 102, 241, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    precision: 0
                                }
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Distribusi Nilai (Rata-rata: {{ isset($stats["average"]) ? number_format($stats["average"], 1) : "N/A" }})'
                                }
                            }
                        }
                    });
                    @elseif($stats['type'] === 'pie')
                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: {
                                !!json_encode($stats['labels']) !!
                            },
                            datasets: [{
                                data: {
                                    !!json_encode($stats['data']) !!
                                },
                                backgroundColor: [
                                    'rgba(99, 102, 241, 0.7)',
                                    'rgba(52, 211, 153, 0.7)',
                                    'rgba(239, 68, 68, 0.7)',
                                    'rgba(249, 115, 22, 0.7)',
                                    'rgba(16, 185, 129, 0.7)',
                                    'rgba(139, 92, 246, 0.7)',
                                    'rgba(245, 158, 11, 0.7)',
                                    'rgba(59, 130, 246, 0.7)',
                                    'rgba(236, 72, 153, 0.7)',
                                    'rgba(75, 85, 99, 0.7)'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right'
                                }
                            }
                        }
                    });
                    @elseif($stats['type'] === 'horizontalBar')
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: {
                                !!json_encode($stats['labels']) !!
                            },
                            datasets: [{
                                label: 'Jumlah Dipilih',
                                data: {
                                    !!json_encode($stats['data']) !!
                                },
                                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                                borderColor: 'rgba(99, 102, 241, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    precision: 0
                                }
                            }
                        }
                    });
                    @endif
                });
            </script>
            @elseif($stats['type'] === 'text')
            <div class="text-answers mt-3">
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
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>