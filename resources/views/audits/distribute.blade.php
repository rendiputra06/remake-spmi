<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribusi Laporan Audit - {{ $audit->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Distribusi Laporan Audit</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-4">{{ $audit->title }}</h5>

                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('audits.distribute', $audit) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="recipients" class="form-label">Pilih Penerima:</label>
                                <select name="recipients[]" id="recipients" class="form-select" multiple required>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                                @error('recipients')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan Tambahan (Opsional):</label>
                                <textarea name="message" id="message" rows="4" class="form-control"></textarea>
                                @error('message')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('audits.show', $audit) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Kirim Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4 shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Pratinjau Laporan</h5>
                    </div>
                    <div class="card-body">
                        <p>Anda dapat mengunduh laporan dalam format PDF terlebih dahulu untuk melihat isinya:</p>
                        <a href="{{ route('audits.report.pdf', $audit) }}" class="btn btn-outline-primary">
                            <i class="bi bi-filetype-pdf"></i> Unduh Laporan PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html> 