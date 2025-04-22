<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultyDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample faculties
        $ftik = Faculty::create([
            'name' => 'Fakultas Teknologi Informasi dan Komputer',
            'code' => 'FTIK',
            'description' => 'Fakultas yang berfokus pada bidang teknologi informasi dan ilmu komputer',
        ]);

        $feb = Faculty::create([
            'name' => 'Fakultas Ekonomi dan Bisnis',
            'code' => 'FEB',
            'description' => 'Fakultas yang berfokus pada bidang ekonomi dan bisnis',
        ]);

        $fkip = Faculty::create([
            'name' => 'Fakultas Keguruan dan Ilmu Pendidikan',
            'code' => 'FKIP',
            'description' => 'Fakultas yang berfokus pada bidang keguruan dan pendidikan',
        ]);

        // Create departments for FTIK
        Department::create([
            'faculty_id' => $ftik->id,
            'name' => 'Teknik Informatika',
            'code' => 'TI',
            'description' => 'Program Studi Teknik Informatika',
            'accreditation' => 'A',
            'accreditation_date' => now(),
        ]);

        Department::create([
            'faculty_id' => $ftik->id,
            'name' => 'Sistem Informasi',
            'code' => 'SI',
            'description' => 'Program Studi Sistem Informasi',
            'accreditation' => 'B',
            'accreditation_date' => now(),
        ]);

        // Create departments for FEB
        Department::create([
            'faculty_id' => $feb->id,
            'name' => 'Manajemen',
            'code' => 'MAN',
            'description' => 'Program Studi Manajemen',
            'accreditation' => 'A',
            'accreditation_date' => now(),
        ]);

        Department::create([
            'faculty_id' => $feb->id,
            'name' => 'Akuntansi',
            'code' => 'AKT',
            'description' => 'Program Studi Akuntansi',
            'accreditation' => 'B',
            'accreditation_date' => now(),
        ]);

        // Create departments for FKIP
        Department::create([
            'faculty_id' => $fkip->id,
            'name' => 'Pendidikan Matematika',
            'code' => 'PMAT',
            'description' => 'Program Studi Pendidikan Matematika',
            'accreditation' => 'B',
            'accreditation_date' => now(),
        ]);

        Department::create([
            'faculty_id' => $fkip->id,
            'name' => 'Pendidikan Bahasa Inggris',
            'code' => 'PBI',
            'description' => 'Program Studi Pendidikan Bahasa Inggris',
            'accreditation' => 'A',
            'accreditation_date' => now(),
        ]);
    }
}
