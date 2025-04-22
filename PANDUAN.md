# Panduan Penggunaan Sistem Penjaminan Mutu Internal (SPMI)

## Tentang Aplikasi

Sistem Penjaminan Mutu Internal (SPMI) adalah platform untuk mengelola berbagai aspek penjaminan mutu di lingkungan institusi pendidikan tinggi. Aplikasi ini memudahkan proses audit mutu, survei, pengelolaan dokumen standar, dan pelaporan di seluruh unit, fakultas, dan program studi.

## Persyaratan Sistem

-   PHP 8.1 atau lebih tinggi
-   MySQL/MariaDB
-   Composer
-   Server web (Apache/Nginx)

## Instalasi

1. Clone repositori

    ```
    git clone [url-repositori]
    ```

2. Instal dependensi

    ```
    composer install
    npm install
    ```

3. Siapkan file .env

    ```
    cp .env.example .env
    php artisan key:generate
    ```

4. Konfigurasi database di file .env

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database
    DB_USERNAME=username
    DB_PASSWORD=password
    ```

5. Migrasi dan seed database

    ```
    php artisan migrate:fresh --seed
    ```

6. Jalankan server
    ```
    php artisan serve
    npm run dev
    ```

## Login Akses

Berikut akun-akun yang telah disediakan untuk akses sistem (pada mode development):

| Peran       | Email                  | Password |
| ----------- | ---------------------- | -------- |
| Super Admin | superadmin@example.com | password |
| Admin       | admin@example.com      | password |
| Kepala LPM  | lpm@example.com        | password |
| Auditor     | auditor@example.com    | password |
| Rektor      | rektor@example.com     | password |
| Dekan       | dekan@example.com      | password |
| Kaprodi     | kaprodi@example.com    | password |
| Dosen       | dosen@example.com      | password |
| Staff       | staff@example.com      | password |

## Fitur Utama

### Dashboard

Dashboard menyediakan ringkasan visual dari status sistem penjaminan mutu. Ini menampilkan:

-   Aktivitas audit terbaru
-   Status survei
-   Dokumen mutu terbaru
-   Notifikasi dan pengumuman

### Manajemen Dokumen

Modul ini memungkinkan pengguna untuk:

-   Mengunggah dan mengkategorikan dokumen mutu
-   Mengkaitkan dokumen dengan standar tertentu
-   Berbagi dokumen dengan unit lain
-   Mencari dokumen berdasarkan kategori, standar, dan kata kunci

### Audit Mutu

Sistem audit mutu memungkinkan:

-   Perencanaan dan penjadwalan audit
-   Penunjukan auditor dan tim audit
-   Pencatatan temuan audit
-   Tindak lanjut temuan dan verifikasi

### Survei

Modul survei menyediakan:

-   Pembuatan berbagai jenis survei (mahasiswa, dosen, alumni, dll)
-   Distribusi survei ke target responden
-   Pengumpulan dan analisis respons
-   Laporan visual hasil survei

### Standar Mutu

Sistem ini memungkinkan pengelolaan:

-   Standar mutu institusi
-   Indikator kinerja untuk setiap standar
-   Pencapaian dan target mutu
-   Dokumentasi bukti pencapaian standar

## Alur Kerja Umum

### Alur Kerja Audit

1. Admin/Kepala LPM membuat jadwal audit
2. Auditor ditugaskan ke audit tertentu
3. Auditor melakukan audit dan mencatat temuan
4. Auditee menanggapi temuan dan membuat rencana tindak lanjut
5. Auditor memverifikasi tindak lanjut
6. Admin/Kepala LPM menutup audit setelah semua temuan ditindaklanjuti

### Alur Kerja Survei

1. Admin/Kepala LPM membuat survei
2. Survei didistribusikan ke target responden
3. Responden mengisi survei
4. Admin/Kepala LPM menganalisis hasil survei
5. Laporan survei dibagikan ke pemangku kepentingan

## Peran dan Hak Akses

### Super Admin

-   Akses penuh ke semua fitur sistem
-   Mengelola pengguna dan peran
-   Melihat log aktivitas sistem

### Admin

-   Mengelola konfigurasi sistem
-   Membantu pengguna dengan masalah teknis
-   Mengelola master data

### Kepala LPM

-   Mengelola standar mutu
-   Merencanakan audit dan survei
-   Meninjau hasil audit dan survei
-   Membuat laporan penjaminan mutu

### Auditor

-   Melaksanakan audit
-   Mencatat temuan
-   Memverifikasi tindak lanjut temuan

### Pimpinan (Rektor, Dekan, Kaprodi)

-   Melihat laporan audit dan survei terkait unit mereka
-   Meninjau kinerja mutu unit mereka
-   Menerima notifikasi tentang temuan audit yang memerlukan perhatian

### Dosen/Staff

-   Mengisi survei
-   Mengunggah dokumen sesuai peran
-   Mengakses dokumen mutu sesuai izin

## Kontak Dukungan

Untuk bantuan teknis atau pertanyaan tentang penggunaan sistem, silakan hubungi:

-   Email: support@spmi.ac.id
-   Telepon: (021) 1234-5678

---

Dokumen ini akan terus diperbarui seiring perkembangan sistem. Versi terakhir diperbarui pada: April 2025.
