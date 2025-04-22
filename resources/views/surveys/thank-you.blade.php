<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow text-center">
                    <div class="card-header bg-success text-white">
                        <h1 class="h4 mb-0">Terima Kasih</h1>
                    </div>
                    <div class="card-body py-5">
                        <div class="mb-4">
                            <div class="display-1 text-success">
                                <i class="bi bi-check-circle-fill"></i>
                                ✓
                            </div>
                            <h2 class="mt-4">Respon Anda Telah Diterima</h2>
                            <p class="lead">Terima kasih telah berpartisipasi dalam survei <strong>{{ $survey->title }}</strong>.</p>
                            <p>Jawaban Anda sangat berarti bagi kami untuk meningkatkan kualitas layanan pendidikan.</p>
                        </div>

                        <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
                            <a href="{{ route('surveys.index') }}" class="btn btn-primary">Kembali ke Daftar Survei</a>
                        </div>
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