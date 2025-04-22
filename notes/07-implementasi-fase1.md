# Implementasi Fase 1: Sistem Autentikasi dan Manajemen Pengguna

## Task List

### 1. Setup Filament Admin Panel

-   [ ] Install Filament 3
-   [ ] Konfigurasi Filament Admin Panel
-   [ ] Sesuaikan tema dan branding untuk SPMI

### 2. Implementasi Autentikasi

-   [ ] Konfigurasi Filament Auth
-   [ ] Buat login page dengan branding SPMI
-   [ ] Implementasi fitur reset password

### 3. Implementasi Manajemen Role dan Permission

-   [ ] Install dan konfigurasi Spatie/Permission
-   [ ] Buat model dan migration untuk role dan permission
-   [ ] Buat resource Filament untuk mengelola role
-   [ ] Buat resource Filament untuk mengelola permission

### 4. Manajemen Struktur Organisasi

-   [ ] Buat model dan migration untuk Fakultas
-   [ ] Buat model dan migration untuk Departemen/Program Studi
-   [ ] Buat model dan migration untuk Unit
-   [ ] Buat resource Filament untuk pengelolaan Fakultas
-   [ ] Buat resource Filament untuk pengelolaan Departemen
-   [ ] Buat resource Filament untuk pengelolaan Unit

### 5. Implementasi Manajemen Pengguna

-   [ ] Perbarui model User dan migration untuk kebutuhan SPMI
-   [ ] Buat model dan migration untuk UserProfile
-   [ ] Buat resource Filament untuk pengelolaan pengguna
-   [ ] Implementasi fitur assign role ke pengguna
-   [ ] Implementasi fitur assign fakultas/departemen/unit ke pengguna

### 6. Dashboard

-   [ ] Buat dashboard dasar
-   [ ] Implementasi widget statistik pengguna
-   [ ] Implementasi widget aktivitas terbaru

### 7. Konfigurasi Akses dan Keamanan

-   [ ] Implementasi policy untuk model User
-   [ ] Implementasi policy untuk model Role dan Permission
-   [ ] Konfigurasi akses resource berdasarkan role
-   [ ] Setup login throttling

### 8. Seeder dan Migrasi

-   [ ] Buat seeder untuk data awal role dan permission
-   [ ] Buat seeder untuk struktur fakultas dan departemen
-   [ ] Buat seeder untuk user admin default

## Langkah Implementasi

Berikut adalah langkah-langkah rinci untuk implementasi fase 1:

1. Setup Filament Admin Panel:

    - Install package Filament 3 via Composer
    - Publish konfigurasi dan assets Filament
    - Kustomisasi branding (logo, warna, nama aplikasi)

2. Implementasi Spatie/Permission:

    - Install package Spatie/Permission
    - Konfigurasi middleware dan role/permission
    - Buat roles dasar: Super Admin, Admin, Kepala LPM, Auditor, Pimpinan, Dekan, Kaprodi, Dosen, Staff

3. Implementasi Struktur Organisasi:

    - Buat model dan migration untuk Fakultas, Departemen, Unit
    - Implementasikan relasi one-to-many antara fakultas dan departemen
    - Implementasikan relasi one-to-one untuk kepala unit

4. Implementasi UserProfile:

    - Perbarui model User dengan field tambahan
    - Buat model UserProfile dengan data tambahan
    - Implementasikan relasi antara User dan struktur organisasi

5. Buat Filament Resources:

    - Resource untuk Role dan Permission
    - Resource untuk User dan UserProfile
    - Resource untuk Fakultas, Departemen, dan Unit
    - Implementasi form, table, dan relation manager

6. Implementasi Dashboard:

    - Sesuaikan dashboard default Filament
    - Buat widget statistik dan aktivitas

7. Konfigurasi Keamanan:
    - Setup policy untuk akses resource
    - Konfigurasi Gate dan middleware berbasis permission
