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
    }
}
