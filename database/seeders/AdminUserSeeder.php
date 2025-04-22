<?php

namespace Database\Seeders;

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
    }
}
