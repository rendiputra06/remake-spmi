<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei SPMI - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h4 mb-0">Daftar Survei SPMI</h1>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif

                        @if($surveys->isEmpty())
                        <div class="alert alert-info">
                            Saat ini tidak ada survei yang tersedia.
                        </div>
                        @else
                        <div class="list-group">
                            @foreach($surveys as $survey)
                            <a href="{{ route('surveys.show', $survey->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $survey->title }}</h5>
                                    <small>
                                        <span class="badge bg-primary">{{ $survey->questions->count() }} Pertanyaan</span>
                                    </small>
                                </div>
                                <p class="mb-1">{{ Str::limit($survey->description, 150) }}</p>
                                <small>
                                    @if($survey->end_date)
                                    Berakhir pada: {{ \Carbon\Carbon::parse($survey->end_date)->format('d M Y') }}
                                    @endif
                                </small>
                            </a>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $surveys->links() }}
                        </div>
                        @endif
                    </div>
                    <div class="card-footer text-center">
                        <p class="small mb-0">
                            &copy; {{ date('Y') }} Sistem Penjaminan Mutu Internal (SPMI)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>