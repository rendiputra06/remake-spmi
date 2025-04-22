<?php

namespace Database\Seeders;

use App\Models\Audit;
use App\Models\AuditFinding;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Standard;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan superadmin dan auditor
        $superAdmin = User::whereHas('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->first();

        $auditor = User::whereHas('roles', function ($query) {
            $query->where('name', 'auditor');
        })->first();

        // Dapatkan fakultas pertama
        $faculty = Faculty::first();
        $department = Department::first();

        // Buat unit jika belum ada
        $unit = Unit::firstOrCreate(
            ['code' => 'LPM'],
            [
                'name' => 'Lembaga Penjaminan Mutu',
                'description' => 'Lembaga yang bertanggung jawab atas sistem penjaminan mutu internal',
                'head_id' => User::whereHas('roles', function ($query) {
                    $query->where('name', 'kepala-lpm');
                })->first()?->id
            ]
        );

        // Buat contoh audit
        $audit = Audit::create([
            'title' => 'Audit Internal Semester Ganjil 2024/2025',
            'description' => 'Audit internal untuk memastikan kesesuaian dengan standar mutu institusi',
            'audit_date_start' => now()->addWeeks(2),
            'audit_date_end' => now()->addWeeks(2)->addDays(3),
            'faculty_id' => $faculty?->id,
            'department_id' => $department?->id,
            'status' => 'planned',
            'lead_auditor_id' => $auditor?->id,
            'created_by' => $superAdmin?->id,
        ]);

        // Tambahkan auditor ke tim audit
        if ($auditor && $audit) {
            $audit->auditors()->attach($auditor->id, ['role' => 'lead_auditor']);
        }

        // Dapatkan standar untuk temuan
        $standard = Standard::where('category', 'Pendidikan')->first();

        // Buat contoh temuan audit
        if ($audit && $standard) {
            AuditFinding::create([
                'audit_id' => $audit->id,
                'standard_id' => $standard->id,
                'type' => 'observation',
                'finding' => 'Belum semua dosen melakukan update RPS sesuai dengan standar yang ditetapkan',
                'evidence' => 'Dari 30 mata kuliah yang diperiksa, 8 mata kuliah belum memiliki RPS yang diperbarui',
                'recommendation' => 'Perlu dilakukan monitoring dan evaluasi RPS secara berkala oleh Ketua Program Studi',
                'status' => 'open',
                'target_completion_date' => now()->addMonth(),
                'created_by' => $auditor?->id,
            ]);

            AuditFinding::create([
                'audit_id' => $audit->id,
                'standard_id' => $standard->id,
                'type' => 'minor',
                'finding' => 'Beberapa dokumen bukti pelaksanaan perkuliahan tidak lengkap',
                'evidence' => 'Dari 30 mata kuliah yang diperiksa, 5 mata kuliah tidak memiliki bukti pelaksanaan perkuliahan yang lengkap',
                'recommendation' => 'Perlu dilakukan pengecekan kelengkapan dokumen perkuliahan setiap bulan',
                'status' => 'open',
                'target_completion_date' => now()->addMonth(),
                'created_by' => $auditor?->id,
            ]);
        }

        // Buat contoh audit yang sedang berlangsung
        $auditOngoing = Audit::create([
            'title' => 'Audit Internal Unit Tahun 2024',
            'description' => 'Audit internal unit untuk memastikan kesesuaian dengan standar tata kelola',
            'audit_date_start' => now()->subDays(2),
            'audit_date_end' => now()->addDays(3),
            'unit_id' => $unit->id,
            'status' => 'ongoing',
            'lead_auditor_id' => $auditor?->id,
            'created_by' => $superAdmin?->id,
        ]);

        // Tambahkan auditor ke tim audit
        if ($auditor && $auditOngoing) {
            $auditOngoing->auditors()->attach($auditor->id, ['role' => 'lead_auditor']);
        }
    }
}
