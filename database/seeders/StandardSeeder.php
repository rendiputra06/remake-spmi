<?php

namespace Database\Seeders;

use App\Models\Standard;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StandardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get super admin ID for created_by
        $superAdmin = User::whereHas('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->first();

        // Kategori standar SPMI
        $categories = ['Pendidikan', 'Penelitian', 'Pengabdian Masyarakat', 'Tata Kelola'];
        $types = ['Standar', 'Prosedur', 'Indikator'];

        // Standar Pendidikan
        Standard::create([
            'code' => 'STD-PDK-01',
            'name' => 'Standar Kompetensi Lulusan',
            'description' => 'Standar minimum kompetensi yang harus dicapai oleh lulusan',
            'category' => 'Pendidikan',
            'type' => 'Standar',
            'is_active' => true,
            'created_by' => $superAdmin?->id,
        ]);

        Standard::create([
            'code' => 'STD-PDK-02',
            'name' => 'Standar Isi Pembelajaran',
            'description' => 'Standar minimum materi dan isi pembelajaran',
            'category' => 'Pendidikan',
            'type' => 'Standar',
            'is_active' => true,
            'created_by' => $superAdmin?->id,
        ]);

        // Standar Penelitian
        Standard::create([
            'code' => 'STD-PNL-01',
            'name' => 'Standar Hasil Penelitian',
            'description' => 'Standar minimum hasil penelitian yang diharapkan',
            'category' => 'Penelitian',
            'type' => 'Standar',
            'is_active' => true,
            'created_by' => $superAdmin?->id,
        ]);

        // Standar Pengabdian Masyarakat
        Standard::create([
            'code' => 'STD-PPM-01',
            'name' => 'Standar Hasil Pengabdian Masyarakat',
            'description' => 'Standar minimum hasil pengabdian masyarakat yang diharapkan',
            'category' => 'Pengabdian Masyarakat',
            'type' => 'Standar',
            'is_active' => true,
            'created_by' => $superAdmin?->id,
        ]);

        // Standar Tata Kelola
        Standard::create([
            'code' => 'STD-TKL-01',
            'name' => 'Standar Pengelolaan',
            'description' => 'Standar minimum pengelolaan institusi',
            'category' => 'Tata Kelola',
            'type' => 'Standar',
            'is_active' => true,
            'created_by' => $superAdmin?->id,
        ]);
    }
}
