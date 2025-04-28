<?php

namespace Database\Seeders;

use App\Models\MonitoringPeriod;
use App\Models\MonitoringIndicator;
use App\Models\MonitoringMeasurement;
use App\Models\MonitoringDashboard;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonitoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan user untuk created_by
        $superAdmin = User::whereHas('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->first();

        $kepalaPm = User::whereHas('roles', function ($query) {
            $query->where('name', 'kepala-lpm');
        })->first();

        // Dapatkan standar
        $standardPendidikan = Standard::where('category', 'Pendidikan')->first();
        $standardPenelitian = Standard::where('category', 'Penelitian')->first();

        // Buat periode monitoring
        $period = MonitoringPeriod::create([
            'name' => 'Periode Monitoring Semester Ganjil 2023/2024',
            'description' => 'Monitoring dan evaluasi pelaksanaan standar mutu semester ganjil tahun akademik 2023/2024',
            'start_date' => now()->subMonths(6),
            'end_date' => now()->addMonths(3),
            'status' => 'active',
            'created_by' => $kepalaPm?->id,
            'updated_by' => null,
        ]);

        // Buat indikator monitoring
        $indicators = [
            // Indikator Akademik
            [
                'code' => 'IND-AKD-01',
                'name' => 'Persentase kehadiran dosen',
                'description' => 'Persentase kehadiran dosen dalam perkuliahan',
                'category' => 'Akademik',
                'unit' => '%',
                'target_value' => 95.00,
                'minimum_value' => 80.00,
                'standard_id' => $standardPendidikan?->id,
                'formula' => 'Jumlah kehadiran / total pertemuan x 100%',
                'data_source' => 'Sistem Informasi Akademik',
                'frequency' => 'Semester',
                'is_active' => true,
                'created_by' => $kepalaPm?->id,
            ],
            [
                'code' => 'IND-AKD-02',
                'name' => 'Rata-rata IPK lulusan',
                'description' => 'Rata-rata Indeks Prestasi Kumulatif lulusan',
                'category' => 'Akademik',
                'unit' => 'Skala 4',
                'target_value' => 3.25,
                'minimum_value' => 2.75,
                'standard_id' => $standardPendidikan?->id,
                'formula' => 'Jumlah IPK lulusan / jumlah lulusan',
                'data_source' => 'Sistem Informasi Akademik',
                'frequency' => 'Semester',
                'is_active' => true,
                'created_by' => $kepalaPm?->id,
            ],
            // Indikator Penelitian
            [
                'code' => 'IND-PNL-01',
                'name' => 'Jumlah publikasi internasional',
                'description' => 'Jumlah publikasi pada jurnal internasional',
                'category' => 'Penelitian',
                'unit' => 'Publikasi',
                'target_value' => 50.00,
                'minimum_value' => 30.00,
                'standard_id' => $standardPenelitian?->id,
                'formula' => 'Jumlah total publikasi internasional',
                'data_source' => 'SINTA',
                'frequency' => 'Tahunan',
                'is_active' => true,
                'created_by' => $kepalaPm?->id,
            ],
            [
                'code' => 'IND-PNL-02',
                'name' => 'Jumlah sitasi dosen',
                'description' => 'Jumlah sitasi publikasi dosen',
                'category' => 'Penelitian',
                'unit' => 'Sitasi',
                'target_value' => 500.00,
                'minimum_value' => 300.00,
                'standard_id' => $standardPenelitian?->id,
                'formula' => 'Jumlah total sitasi dosen',
                'data_source' => 'Google Scholar',
                'frequency' => 'Tahunan',
                'is_active' => true,
                'created_by' => $kepalaPm?->id,
            ],
        ];

        foreach ($indicators as $indicator) {
            MonitoringIndicator::create($indicator);
        }

        // Ambil fakultas dan program studi
        $ftik = Faculty::where('code', 'FTIK')->first();
        $ti = Department::where('code', 'TI')->first();
        $si = Department::where('code', 'SI')->first();

        // Ambil indikator yang sudah dibuat
        $indikatorKehadiran = MonitoringIndicator::where('code', 'IND-AKD-01')->first();
        $indikatorIPK = MonitoringIndicator::where('code', 'IND-AKD-02')->first();
        $indikatorPublikasi = MonitoringIndicator::where('code', 'IND-PNL-01')->first();
        $indikatorSitasi = MonitoringIndicator::where('code', 'IND-PNL-02')->first();

        // Buat measurement untuk indikator
        if ($period && $indikatorKehadiran && $ftik && $ti) {
            // Pengukuran untuk Teknik Informatika
            MonitoringMeasurement::create([
                'monitoring_period_id' => $period->id,
                'monitoring_indicator_id' => $indikatorKehadiran->id,
                'faculty_id' => $ftik->id,
                'department_id' => $ti->id,
                'actual_value' => 92.50,
                'status' => 'achieved',
                'remarks' => 'Kehadiran dosen sudah baik',
                'achievements' => 'Program reminder kehadiran efektif',
                'obstacles' => 'Beberapa dosen masih terlambat input kehadiran',
                'follow_up' => 'Perbaikan sistem reminder kehadiran',
                'created_by' => $kepalaPm?->id,
            ]);

            // Pengukuran untuk Sistem Informasi
            MonitoringMeasurement::create([
                'monitoring_period_id' => $period->id,
                'monitoring_indicator_id' => $indikatorKehadiran->id,
                'faculty_id' => $ftik->id,
                'department_id' => $si?->id,
                'actual_value' => 88.75,
                'status' => 'achieved',
                'remarks' => 'Kehadiran dosen cukup baik',
                'achievements' => 'Monitoring kehadiran rutin dilakukan',
                'obstacles' => 'Beberapa dosen memiliki jadwal yang bentrok',
                'follow_up' => 'Perbaikan penjadwalan dosen',
                'created_by' => $kepalaPm?->id,
            ]);
        }

        if ($period && $indikatorIPK && $ftik && $ti && $si) {
            MonitoringMeasurement::create([
                'monitoring_period_id' => $period->id,
                'monitoring_indicator_id' => $indikatorIPK->id,
                'faculty_id' => $ftik->id,
                'department_id' => $ti->id,
                'actual_value' => 3.42,
                'status' => 'achieved',
                'remarks' => 'IPK lulusan sangat baik',
                'achievements' => 'Sistem pembimbingan akademik berjalan efektif',
                'obstacles' => null,
                'follow_up' => 'Mempertahankan sistem pembimbingan',
                'created_by' => $kepalaPm?->id,
            ]);

            MonitoringMeasurement::create([
                'monitoring_period_id' => $period->id,
                'monitoring_indicator_id' => $indikatorIPK->id,
                'faculty_id' => $ftik->id,
                'department_id' => $si?->id,
                'actual_value' => 3.38,
                'status' => 'achieved',
                'remarks' => 'IPK lulusan baik',
                'achievements' => 'Pemantauan mahasiswa bermasalah berjalan efektif',
                'obstacles' => null,
                'follow_up' => null,
                'created_by' => $kepalaPm?->id,
            ]);
        }

        // Buat dashboard
        $academicDashboard = MonitoringDashboard::create([
            'name' => 'Dashboard Akademik',
            'description' => 'Dashboard monitoring indikator akademik',
            'type' => 'chart',
            'category' => 'Akademik',
            'config' => json_encode([
                'chart_type' => 'bar',
                'x_axis' => 'Program Studi',
                'y_axis' => 'Nilai',
                'show_target' => true,
                'color_scheme' => 'blue',
            ]),
            'is_public' => true,
            'filters' => json_encode([
                'period' => true,
                'faculty' => true,
                'department' => true,
            ]),
            'display_order' => 1,
            'created_by' => $kepalaPm?->id,
        ]);

        $researchDashboard = MonitoringDashboard::create([
            'name' => 'Dashboard Penelitian',
            'description' => 'Dashboard monitoring indikator penelitian',
            'type' => 'chart',
            'category' => 'Penelitian',
            'config' => json_encode([
                'chart_type' => 'line',
                'x_axis' => 'Periode',
                'y_axis' => 'Jumlah',
                'show_target' => true,
                'color_scheme' => 'green',
            ]),
            'is_public' => true,
            'filters' => json_encode([
                'period' => true,
                'faculty' => true,
            ]),
            'display_order' => 2,
            'created_by' => $kepalaPm?->id,
        ]);

        // Tambahkan indikator ke dashboard
        if ($academicDashboard && $indikatorKehadiran && $indikatorIPK) {
            $academicDashboard->indicators()->attach($indikatorKehadiran->id, ['display_order' => 1]);
            $academicDashboard->indicators()->attach($indikatorIPK->id, ['display_order' => 2]);
        }

        if ($researchDashboard && $indikatorPublikasi && $indikatorSitasi) {
            $researchDashboard->indicators()->attach($indikatorPublikasi->id, ['display_order' => 1]);
            $researchDashboard->indicators()->attach($indikatorSitasi->id, ['display_order' => 2]);
        }

        // Seed performance indicators
        $performanceIndicators = [
            [
                'id' => 1,
                'code' => 'IKU-01',
                'name' => 'Persentase Lulusan Tepat Waktu',
                'description' => 'Persentase mahasiswa yang lulus tepat waktu (maksimal 4 tahun)',
                'unit' => '%',
                'target' => 80.00,
                'type' => 'output',
                'category' => 'academic',
                'level' => 'department',
                'department_id' => 1,
                'parent_id' => null,
                'order' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'code' => 'IKU-02',
                'name' => 'Rata-rata IPK Lulusan',
                'description' => 'Rata-rata Indeks Prestasi Kumulatif lulusan',
                'unit' => 'Nilai (0-4)',
                'target' => 3.30,
                'type' => 'output',
                'category' => 'academic',
                'level' => 'department',
                'department_id' => 1,
                'parent_id' => null,
                'order' => 2,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'code' => 'IKU-03',
                'name' => 'Tingkat Publikasi Dosen',
                'description' => 'Jumlah publikasi internasional per dosen per tahun',
                'unit' => 'Publikasi',
                'target' => 2.00,
                'type' => 'output',
                'category' => 'research',
                'level' => 'department',
                'department_id' => 1,
                'parent_id' => null,
                'order' => 3,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'code' => 'IKU-04',
                'name' => 'Waktu Tunggu Lulusan',
                'description' => 'Rata-rata waktu tunggu lulusan mendapatkan pekerjaan pertama',
                'unit' => 'Bulan',
                'target' => 3.00,
                'type' => 'outcome',
                'category' => 'academic',
                'level' => 'department',
                'department_id' => 1,
                'parent_id' => null,
                'order' => 4,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'code' => 'IKU-05',
                'name' => 'Tingkat Kepuasan Mahasiswa',
                'description' => 'Tingkat kepuasan mahasiswa terhadap layanan akademik',
                'unit' => 'Skala (1-5)',
                'target' => 4.00,
                'type' => 'quality',
                'category' => 'academic',
                'level' => 'department',
                'department_id' => 1,
                'parent_id' => null,
                'order' => 5,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('performance_indicators')->insert($performanceIndicators);

        // Seed performance values
        $values = [
            [
                'id' => 1,
                'indicator_id' => 1,
                'year' => date('Y') - 1,
                'semester' => 2,
                'measurement_date' => Carbon::now()->subMonths(6),
                'value' => 78.50,
                'achievement_percentage' => 98.13,
                'description' => 'Persentase lulusan tepat waktu semester genap tahun akademik ' . (date('Y') - 2) . '/' . (date('Y') - 1),
                'findings' => 'Tingkat kelulusan tepat waktu masih di bawah target namun cukup baik. Beberapa mahasiswa terlambat karena kendala tugas akhir.',
                'root_causes' => 'Pembimbingan tugas akhir belum optimal. Ketersediaan laboratorium untuk penelitian terbatas.',
                'recommendations' => 'Tingkatkan monitoring kemajuan tugas akhir secara berkala. Tambah jam operasional laboratorium.',
                'created_by' => 1,
                'updated_by' => 1,
                'verified_by' => 2,
                'verified_at' => Carbon::now()->subMonths(5),
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now()->subMonths(5),
            ],
            [
                'id' => 2,
                'indicator_id' => 2,
                'year' => date('Y') - 1,
                'semester' => 2,
                'measurement_date' => Carbon::now()->subMonths(6),
                'value' => 3.45,
                'achievement_percentage' => 104.55,
                'description' => 'Rata-rata IPK lulusan semester genap tahun akademik ' . (date('Y') - 2) . '/' . (date('Y') - 1),
                'findings' => 'Rata-rata IPK lulusan melebihi target yang ditetapkan. Tren positif selama 3 tahun terakhir.',
                'root_causes' => 'Implementasi kurikulum baru dan peningkatan metode pembelajaran berbasis proyek memberikan hasil positif.',
                'recommendations' => 'Pertahankan dan tingkatkan metode pembelajaran berbasis proyek. Lakukan benchmarking dengan universitas lain.',
                'created_by' => 1,
                'updated_by' => 1,
                'verified_by' => 2,
                'verified_at' => Carbon::now()->subMonths(5),
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now()->subMonths(5),
            ],
            [
                'id' => 3,
                'indicator_id' => 3,
                'year' => date('Y') - 1,
                'semester' => null,
                'measurement_date' => Carbon::now()->subMonths(3),
                'value' => 1.70,
                'achievement_percentage' => 85.00,
                'description' => 'Rata-rata publikasi internasional per dosen tahun ' . (date('Y') - 1),
                'findings' => 'Jumlah publikasi internasional masih di bawah target. Beberapa dosen belum menghasilkan publikasi internasional.',
                'root_causes' => 'Beban mengajar yang tinggi. Kurangnya insentif penelitian. Keterbatasan dana penelitian.',
                'recommendations' => 'Tingkatkan alokasi dana penelitian. Berikan insentif yang lebih menarik untuk publikasi internasional. Kurangi beban mengajar bagi dosen yang aktif penelitian.',
                'created_by' => 1,
                'updated_by' => 1,
                'verified_by' => 2,
                'verified_at' => Carbon::now()->subMonths(2),
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now()->subMonths(2),
            ],
            [
                'id' => 4,
                'indicator_id' => 4,
                'year' => date('Y') - 1,
                'semester' => null,
                'measurement_date' => Carbon::now()->subMonths(3),
                'value' => 2.80,
                'achievement_percentage' => 106.67,
                'description' => 'Rata-rata waktu tunggu lulusan untuk mendapatkan pekerjaan pertama tahun ' . (date('Y') - 1),
                'findings' => 'Waktu tunggu lulusan lebih cepat dari target. Mayoritas lulusan mendapatkan pekerjaan sebelum wisuda.',
                'root_causes' => 'Kerja sama dengan industri berjalan baik. Program magang dan sertifikasi memberikan nilai tambah bagi lulusan.',
                'recommendations' => 'Perluas kerja sama dengan industri. Tingkatkan program sertifikasi profesional. Adakan job fair secara rutin.',
                'created_by' => 1,
                'updated_by' => 1,
                'verified_by' => 2,
                'verified_at' => Carbon::now()->subMonths(2),
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now()->subMonths(2),
            ],
            [
                'id' => 5,
                'indicator_id' => 5,
                'year' => date('Y') - 1,
                'semester' => 2,
                'measurement_date' => Carbon::now()->subMonths(6),
                'value' => 3.90,
                'achievement_percentage' => 97.50,
                'description' => 'Tingkat kepuasan mahasiswa terhadap layanan akademik semester genap ' . (date('Y') - 2) . '/' . (date('Y') - 1),
                'findings' => 'Tingkat kepuasan mahasiswa cukup tinggi. Aspek yang masih perlu ditingkatkan adalah sarana prasarana dan responsivitas layanan akademik.',
                'root_causes' => 'Beberapa fasilitas belajar perlu pembaharuan. Rasio tenaga kependidikan dan mahasiswa belum ideal.',
                'recommendations' => 'Lakukan renovasi ruang kuliah secara bertahap. Tingkatkan pelatihan bagi tenaga kependidikan untuk meningkatkan responsivitas.',
                'created_by' => 1,
                'updated_by' => 1,
                'verified_by' => 2,
                'verified_at' => Carbon::now()->subMonths(5),
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now()->subMonths(5),
            ],
        ];

        DB::table('performance_values')->insert($values);

        // Seed dashboards
        $dashboards = [
            [
                'id' => 1,
                'name' => 'Dashboard Monitoring Akademik',
                'description' => 'Dashboard untuk memantau kinerja akademik program studi',
                'type' => 'monitoring',
                'level' => 'department',
                'department_id' => 1,
                'configuration' => json_encode([
                    'layout' => 'grid',
                    'refreshInterval' => 3600,
                    'theme' => 'light',
                ]),
                'is_public' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
            [
                'id' => 2,
                'name' => 'Dashboard Kinerja Penelitian',
                'description' => 'Dashboard untuk memantau kinerja penelitian dosen',
                'type' => 'analytics',
                'level' => 'department',
                'department_id' => 1,
                'configuration' => json_encode([
                    'layout' => 'grid',
                    'refreshInterval' => 3600,
                    'theme' => 'light',
                ]),
                'is_public' => false,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now()->subMonths(10),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
        ];

        DB::table('dashboards')->insert($dashboards);

        // Seed dashboard indicators
        $dashboardIndicators = [
            [
                'id' => 1,
                'dashboard_id' => 1,
                'indicator_id' => 1,
                'chart_type' => 'line',
                'chart_config' => json_encode([
                    'title' => 'Tren Kelulusan Tepat Waktu',
                    'xAxis' => 'Tahun',
                    'yAxis' => 'Persentase (%)',
                    'colors' => ['#4e73df'],
                    'showTarget' => true,
                ]),
                'order' => 1,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
            [
                'id' => 2,
                'dashboard_id' => 1,
                'indicator_id' => 2,
                'chart_type' => 'line',
                'chart_config' => json_encode([
                    'title' => 'Tren Rata-rata IPK Lulusan',
                    'xAxis' => 'Tahun',
                    'yAxis' => 'IPK',
                    'colors' => ['#1cc88a'],
                    'showTarget' => true,
                ]),
                'order' => 2,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
            [
                'id' => 3,
                'dashboard_id' => 1,
                'indicator_id' => 4,
                'chart_type' => 'bar',
                'chart_config' => json_encode([
                    'title' => 'Waktu Tunggu Lulusan',
                    'xAxis' => 'Tahun',
                    'yAxis' => 'Bulan',
                    'colors' => ['#36b9cc'],
                    'showTarget' => true,
                ]),
                'order' => 3,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
            [
                'id' => 4,
                'dashboard_id' => 1,
                'indicator_id' => 5,
                'chart_type' => 'gauge',
                'chart_config' => json_encode([
                    'title' => 'Tingkat Kepuasan Mahasiswa',
                    'min' => 1,
                    'max' => 5,
                    'colors' => ['#f6c23e', '#1cc88a'],
                    'showTarget' => true,
                ]),
                'order' => 4,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
            [
                'id' => 5,
                'dashboard_id' => 2,
                'indicator_id' => 3,
                'chart_type' => 'bar',
                'chart_config' => json_encode([
                    'title' => 'Publikasi Internasional per Dosen',
                    'xAxis' => 'Tahun',
                    'yAxis' => 'Jumlah',
                    'colors' => ['#e74a3b'],
                    'showTarget' => true,
                ]),
                'order' => 1,
                'created_at' => Carbon::now()->subMonths(10),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
        ];

        DB::table('dashboard_indicators')->insert($dashboardIndicators);

        // Seed performance targets
        $targets = [
            [
                'id' => 1,
                'indicator_id' => 1,
                'year' => date('Y'),
                'target' => 82.00,
                'description' => 'Target persentase lulusan tepat waktu untuk tahun ' . date('Y'),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subMonths(12),
            ],
            [
                'id' => 2,
                'indicator_id' => 2,
                'year' => date('Y'),
                'target' => 3.35,
                'description' => 'Target rata-rata IPK lulusan untuk tahun ' . date('Y'),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subMonths(12),
            ],
            [
                'id' => 3,
                'indicator_id' => 3,
                'year' => date('Y'),
                'target' => 2.20,
                'description' => 'Target publikasi internasional per dosen untuk tahun ' . date('Y'),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subMonths(12),
            ],
            [
                'id' => 4,
                'indicator_id' => 4,
                'year' => date('Y'),
                'target' => 2.50,
                'description' => 'Target waktu tunggu lulusan untuk tahun ' . date('Y'),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subMonths(12),
            ],
            [
                'id' => 5,
                'indicator_id' => 5,
                'year' => date('Y'),
                'target' => 4.20,
                'description' => 'Target tingkat kepuasan mahasiswa untuk tahun ' . date('Y'),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subMonths(12),
            ],
        ];

        DB::table('performance_targets')->insert($targets);

        // Seed monitoring documents
        $documents = [
            [
                'id' => 1,
                'title' => 'Laporan Monitoring Akademik Semester Genap ' . (date('Y') - 2) . '/' . (date('Y') - 1),
                'description' => 'Laporan hasil monitoring kinerja akademik program studi untuk semester genap tahun akademik ' . (date('Y') - 2) . '/' . (date('Y') - 1),
                'file_path' => 'monitoring/laporan-monitoring-akademik-genap-' . (date('Y') - 1) . '.pdf',
                'type' => 'report',
                'year' => date('Y') - 1,
                'semester' => 2,
                'level' => 'department',
                'department_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now()->subMonths(6),
            ],
            [
                'id' => 2,
                'title' => 'Laporan Kinerja Penelitian Dosen Tahun ' . (date('Y') - 1),
                'description' => 'Laporan evaluasi kinerja penelitian dosen program studi untuk tahun ' . (date('Y') - 1),
                'file_path' => 'monitoring/laporan-kinerja-penelitian-' . (date('Y') - 1) . '.pdf',
                'type' => 'report',
                'year' => date('Y') - 1,
                'semester' => null,
                'level' => 'department',
                'department_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now()->subMonths(3),
            ],
            [
                'id' => 3,
                'title' => 'Rencana Tindak Lanjut Monitoring Akademik ' . date('Y'),
                'description' => 'Rencana tindak lanjut berdasarkan hasil monitoring akademik untuk tahun ' . date('Y'),
                'file_path' => 'monitoring/rtl-akademik-' . date('Y') . '.pdf',
                'type' => 'plan',
                'year' => date('Y'),
                'semester' => null,
                'level' => 'department',
                'department_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now()->subMonths(1),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
        ];

        DB::table('monitoring_documents')->insert($documents);

        // Seed monitoring document indicators
        $documentIndicators = [
            [
                'document_id' => 1,
                'indicator_id' => 1,
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now()->subMonths(6),
            ],
            [
                'document_id' => 1,
                'indicator_id' => 2,
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now()->subMonths(6),
            ],
            [
                'document_id' => 1,
                'indicator_id' => 5,
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now()->subMonths(6),
            ],
            [
                'document_id' => 2,
                'indicator_id' => 3,
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now()->subMonths(3),
            ],
            [
                'document_id' => 3,
                'indicator_id' => 1,
                'created_at' => Carbon::now()->subMonths(1),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
            [
                'document_id' => 3,
                'indicator_id' => 2,
                'created_at' => Carbon::now()->subMonths(1),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
            [
                'document_id' => 3,
                'indicator_id' => 3,
                'created_at' => Carbon::now()->subMonths(1),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
            [
                'document_id' => 3,
                'indicator_id' => 4,
                'created_at' => Carbon::now()->subMonths(1),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
            [
                'document_id' => 3,
                'indicator_id' => 5,
                'created_at' => Carbon::now()->subMonths(1),
                'updated_at' => Carbon::now()->subMonths(1),
            ],
        ];

        DB::table('monitoring_document_indicators')->insert($documentIndicators);
    }
}
