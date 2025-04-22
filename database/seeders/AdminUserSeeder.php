<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\Department;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin user
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('super-admin');

        // Create profile for super admin
        UserProfile::create([
            'user_id' => $superAdmin->id,
            'position' => 'Super Administrator',
            'phone' => '08123456789',
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin SPMI',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create profile for admin
        UserProfile::create([
            'user_id' => $admin->id,
            'position' => 'Administrator SPMI',
            'phone' => '08123456788',
        ]);

        // Create LPM user
        $kepalaPM = User::create([
            'name' => 'Kepala LPM',
            'email' => 'lpm@example.com',
            'password' => Hash::make('password'),
        ]);
        $kepalaPM->assignRole('kepala-lpm');

        // Create profile for LPM
        UserProfile::create([
            'user_id' => $kepalaPM->id,
            'position' => 'Kepala Lembaga Penjaminan Mutu',
            'phone' => '08123456787',
        ]);

        // Create auditor user
        $auditor = User::create([
            'name' => 'Auditor',
            'email' => 'auditor@example.com',
            'password' => Hash::make('password'),
        ]);
        $auditor->assignRole('auditor');

        // Create profile for auditor
        UserProfile::create([
            'user_id' => $auditor->id,
            'position' => 'Auditor Mutu Internal',
            'phone' => '08123456786',
        ]);

        // Create rektor (pimpinan) user
        $rektor = User::create([
            'name' => 'Rektor',
            'email' => 'rektor@example.com',
            'password' => Hash::make('password'),
        ]);
        $rektor->assignRole('pimpinan');

        // Create profile for rektor
        UserProfile::create([
            'user_id' => $rektor->id,
            'position' => 'Rektor Universitas',
            'phone' => '08123456785',
        ]);

        // Dapatkan fakultas dan departemen
        $faculty = Faculty::first();
        $department = Department::first();

        // Create dekan user
        $dekan = User::create([
            'name' => 'Dr. Budi Fakultas',
            'email' => 'dekan@example.com',
            'password' => Hash::make('password'),
        ]);
        $dekan->assignRole('dekan');

        // Create profile for dekan
        UserProfile::create([
            'user_id' => $dekan->id,
            'position' => 'Dekan Fakultas',
            'phone' => '08123456784',
            'faculty_id' => $faculty?->id,
        ]);

        // Update faculty dengan dean_id
        if ($faculty) {
            $faculty->dean_id = $dekan->id;
            $faculty->save();
        }

        // Create kaprodi user
        $kaprodi = User::create([
            'name' => 'Dr. Andi Prodi',
            'email' => 'kaprodi@example.com',
            'password' => Hash::make('password'),
        ]);
        $kaprodi->assignRole('kaprodi');

        // Create profile for kaprodi
        UserProfile::create([
            'user_id' => $kaprodi->id,
            'position' => 'Ketua Program Studi',
            'phone' => '08123456783',
            'faculty_id' => $faculty?->id,
            'department_id' => $department?->id,
        ]);

        // Update department dengan head_id
        if ($department) {
            $department->head_id = $kaprodi->id;
            $department->save();
        }

        // Create dosen user
        $dosen = User::create([
            'name' => 'Dr. Citra Dosen',
            'email' => 'dosen@example.com',
            'password' => Hash::make('password'),
        ]);
        $dosen->assignRole('dosen');

        // Create profile for dosen
        UserProfile::create([
            'user_id' => $dosen->id,
            'position' => 'Dosen Tetap',
            'phone' => '08123456782',
            'faculty_id' => $faculty?->id,
            'department_id' => $department?->id,
        ]);

        // Create staff user
        $staff = User::create([
            'name' => 'Diana Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
        ]);
        $staff->assignRole('staff');

        // Create profile for staff
        UserProfile::create([
            'user_id' => $staff->id,
            'position' => 'Staff Akademik',
            'phone' => '08123456781',
            'faculty_id' => $faculty?->id,
            'department_id' => $department?->id,
        ]);
    }
}
