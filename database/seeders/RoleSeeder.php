<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for users
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'assign roles']);

        // Create permissions for faculties
        Permission::create(['name' => 'view faculties']);
        Permission::create(['name' => 'create faculties']);
        Permission::create(['name' => 'edit faculties']);
        Permission::create(['name' => 'delete faculties']);

        // Create permissions for departments
        Permission::create(['name' => 'view departments']);
        Permission::create(['name' => 'create departments']);
        Permission::create(['name' => 'edit departments']);
        Permission::create(['name' => 'delete departments']);

        // Create permissions for units
        Permission::create(['name' => 'view units']);
        Permission::create(['name' => 'create units']);
        Permission::create(['name' => 'edit units']);
        Permission::create(['name' => 'delete units']);

        // Create permissions for standards
        Permission::create(['name' => 'view standards']);
        Permission::create(['name' => 'create standards']);
        Permission::create(['name' => 'edit standards']);
        Permission::create(['name' => 'delete standards']);

        // Create permissions for audits
        Permission::create(['name' => 'view audits']);
        Permission::create(['name' => 'create audits']);
        Permission::create(['name' => 'edit audits']);
        Permission::create(['name' => 'delete audits']);
        Permission::create(['name' => 'plan audits']);
        Permission::create(['name' => 'conduct audits']);
        Permission::create(['name' => 'respond to findings']);
        Permission::create(['name' => 'verify findings']);

        // Create permissions for documents
        Permission::create(['name' => 'view documents']);
        Permission::create(['name' => 'create documents']);
        Permission::create(['name' => 'edit documents']);
        Permission::create(['name' => 'delete documents']);

        // Create permissions for surveys
        Permission::create(['name' => 'view surveys']);
        Permission::create(['name' => 'create surveys']);
        Permission::create(['name' => 'edit surveys']);
        Permission::create(['name' => 'delete surveys']);
        Permission::create(['name' => 'fill surveys']);
        Permission::create(['name' => 'analyze surveys']);

        // Create permissions for reports
        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'create reports']);
        Permission::create(['name' => 'distribute reports']);

        // Create roles and assign permissions

        // Super Admin
        $superAdminRole = Role::create(['name' => 'super-admin']);
        // Super admin gets all permissions
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
            'view faculties',
            'create faculties',
            'edit faculties',
            'delete faculties',
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',
            'view units',
            'create units',
            'edit units',
            'delete units',
            'view documents',
            'create documents',
            'edit documents',
            'delete documents',
        ]);

        // Kepala LPM
        $kepalaLpmRole = Role::create(['name' => 'kepala-lpm']);
        $kepalaLpmRole->givePermissionTo([
            'view users',
            'view faculties',
            'view departments',
            'view units',
            'view standards',
            'create standards',
            'edit standards',
            'delete standards',
            'view audits',
            'create audits',
            'edit audits',
            'delete audits',
            'plan audits',
            'view documents',
            'create documents',
            'edit documents',
            'delete documents',
            'view surveys',
            'create surveys',
            'edit surveys',
            'delete surveys',
            'analyze surveys',
            'view reports',
            'create reports',
            'distribute reports',
        ]);

        // Auditor
        $auditorRole = Role::create(['name' => 'auditor']);
        $auditorRole->givePermissionTo([
            'view faculties',
            'view departments',
            'view units',
            'view standards',
            'view audits',
            'conduct audits',
            'verify findings',
            'view documents',
            'view reports',
        ]);

        // Pimpinan Universitas
        $pimpinanRole = Role::create(['name' => 'pimpinan']);
        $pimpinanRole->givePermissionTo([
            'view users',
            'view faculties',
            'view departments',
            'view units',
            'view standards',
            'view audits',
            'view documents',
            'create documents',
            'edit documents',
            'view surveys',
            'analyze surveys',
            'view reports',
            'create reports',
            'distribute reports',
        ]);

        // Dekan
        $dekanRole = Role::create(['name' => 'dekan']);
        $dekanRole->givePermissionTo([
            'view users',
            'view faculties',
            'view departments',
            'view standards',
            'view audits',
            'respond to findings',
            'view documents',
            'create documents',
            'edit documents',
            'view surveys',
            'analyze surveys',
            'view reports',
        ]);

        // Kaprodi
        $kaprodiRole = Role::create(['name' => 'kaprodi']);
        $kaprodiRole->givePermissionTo([
            'view users',
            'view faculties',
            'view departments',
            'view standards',
            'view audits',
            'respond to findings',
            'view documents',
            'create documents',
            'edit documents',
            'view surveys',
            'fill surveys',
            'analyze surveys',
            'view reports',
        ]);

        // Dosen
        $dosenRole = Role::create(['name' => 'dosen']);
        $dosenRole->givePermissionTo([
            'view standards',
            'view documents',
            'view surveys',
            'fill surveys',
        ]);

        // Staff
        $staffRole = Role::create(['name' => 'staff']);
        $staffRole->givePermissionTo([
            'view documents',
            'create documents',
            'edit documents',
            'view surveys',
            'fill surveys',
        ]);
    }
}
