<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SPMI - Sistem Penjaminan Mutu Internal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .hero-section {
            background: linear-gradient(135deg, #4a86e8 0%, #2c5282 100%);
            color: white;
            padding: 80px 0;
        }

        .hero-content {
            max-width: 700px;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 2rem;
            color: #2c5282;
        }

        .feature-card {
            height: 100%;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2c5282;
        }

        .footer {
            background-color: #1a365d;
            color: white;
            padding: 40px 0;
        }

        .btn-primary {
            background-color: #4a86e8;
            border-color: #4a86e8;
        }

        .btn-primary:hover {
            background-color: #3a76d8;
            border-color: #3a76d8;
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="antialiased">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1a365d;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">SPMI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('surveys.index') }}">Survei</a>
                    </li>
                </ul>
                <div class="d-flex">
                    @if (Route::has('login'))
                    <div>
                        @auth
                        <a href="{{ url('/admin') }}" class="btn btn-outline-light">Dashboard</a>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Masuk</a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                        @endif
                        @endauth
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">Sistem Penjaminan Mutu Internal</h1>
                    <p class="lead mb-4">
                        Platform terintegrasi untuk mengelola, memantau, dan meningkatkan mutu pendidikan tinggi
                        secara berkelanjutan.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg">Masuk ke Sistem</a>
                        <a href="{{ route('surveys.index') }}" class="btn btn-outline-light btn-lg">Isi Survei</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="https://via.placeholder.com/600x400/e2f0ff/2c5282?text=SPMI" alt="SPMI" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="section-title text-center">Modul Utama</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-primary">
                                <i class="bi bi-clipboard-data"></i>
                            </div>
                            <h3 class="feature-title">Standar Mutu</h3>
                            <p>Pengelolaan standar mutu pendidikan sesuai dengan regulasi internal dan eksternal.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-primary">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                            <h3 class="feature-title">Audit Mutu</h3>
                            <p>Merencanakan, menjalankan dan melacak progress audit mutu internal secara terstruktur.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-primary">
                                <i class="bi bi-bar-chart"></i>
                            </div>
                            <h3 class="feature-title">Survei & Evaluasi</h3>
                            <p>Membuat dan menganalisis hasil survei dengan visualisasi data yang komprehensif.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-primary">
                                <i class="bi bi-award"></i>
                            </div>
                            <h3 class="feature-title">Akreditasi</h3>
                            <p>Persiapan, simulasi, dan evaluasi kesiapan proses akreditasi institusi dan program studi.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-primary">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <h3 class="feature-title">Monitoring & Evaluasi</h3>
                            <p>Pemantauan pencapaian standar mutu dan analisis kesenjangan (gap analysis).</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon text-primary">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <h3 class="feature-title">Pelaporan</h3>
                            <p>Menghasilkan dan mendistribusikan laporan mutu secara efektif ke semua stakeholder.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title">Mengapa Menggunakan SPMI?</h2>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent border-0 ps-0">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Mempercepat proses penjaminan mutu dengan pengelolaan yang terintegrasi
                        </li>
                        <li class="list-group-item bg-transparent border-0 ps-0">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Memudahkan pelacakan ketercapaian standar mutu secara real-time
                        </li>
                        <li class="list-group-item bg-transparent border-0 ps-0">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Meningkatkan kolaborasi antar unit kerja dalam peningkatan mutu
                        </li>
                        <li class="list-group-item bg-transparent border-0 ps-0">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Menyediakan data dan analisis untuk pengambilan keputusan berbasis bukti
                        </li>
                        <li class="list-group-item bg-transparent border-0 ps-0">
                            <i class="bi bi-check-circle-fill text-primary me-2"></i>
                            Mempersiapkan institusi menghadapi akreditasi BAN-PT dan lembaga akreditasi lainnya
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow">
                        <div class="card-body p-4">
                            <h4 class="mb-4 text-primary">Mulai Gunakan SPMI Sekarang</h4>
                            <p>Dengan sistem penjaminan mutu yang terintegrasi, institusi Anda dapat fokus pada perbaikan berkelanjutan dan peningkatan kualitas pendidikan.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary mt-3">Masuk ke Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mb-4">SPMI - Sistem Penjaminan Mutu Internal</h4>
                    <p>Platform terintegrasi untuk pengelolaan penjaminan mutu pendidikan tinggi.</p>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-3">Tautan</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50">Tentang SPMI</a></li>
                        <li><a href="#" class="text-white-50">Panduan Pengguna</a></li>
                        <li><a href="#" class="text-white-50">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-3">Kontak</h5>
                    <ul class="list-unstyled">
                        <li><a href="mailto:info@spmi.ac.id" class="text-white-50">info@spmi.ac.id</a></li>
                        <li><a href="tel:+62123456789" class="text-white-50">+62 123 456 789</a></li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4 mb-4" style="border-color: rgba(255,255,255,.1)">
            <div class="text-center text-white-50">
                <p>&copy; {{ date('Y') }} Sistem Penjaminan Mutu Internal. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>