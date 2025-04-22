# Use Case Sistem Informasi Penjaminan Mutu Internal (SPMI)

## 1. Manajemen Pengguna

### UC-1.1: Pendaftaran Pengguna

-   **Aktor**: Admin Sistem
-   **Deskripsi**: Admin dapat mendaftarkan pengguna baru ke dalam sistem
-   **Alur Utama**:
    1. Admin mengakses halaman manajemen pengguna
    2. Admin memilih opsi "Tambah Pengguna Baru"
    3. Admin mengisi formulir data pengguna (nama, email, peran, fakultas/unit)
    4. Sistem memvalidasi data yang dimasukkan
    5. Sistem menyimpan data pengguna baru
    6. Sistem mengirimkan email dengan informasi akun ke pengguna baru

### UC-1.2: Pengaturan Peran dan Izin

-   **Aktor**: Admin Sistem
-   **Deskripsi**: Admin dapat mengatur peran dan izin untuk setiap pengguna
-   **Alur Utama**:
    1. Admin mengakses halaman manajemen peran
    2. Admin melihat daftar peran yang tersedia
    3. Admin dapat menambah, mengedit, atau menghapus peran
    4. Admin dapat mengassign peran ke pengguna
    5. Admin dapat mengatur izin khusus untuk setiap peran

### UC-1.3: Login Pengguna

-   **Aktor**: Semua Pengguna
-   **Deskripsi**: Pengguna dapat masuk ke sistem menggunakan kredensial mereka
-   **Alur Utama**:
    1. Pengguna mengakses halaman login
    2. Pengguna memasukkan email dan password
    3. Sistem memvalidasi kredensial
    4. Pengguna diarahkan ke dashboard sesuai peran mereka

## 2. Pengelolaan Standar Mutu

### UC-2.1: Pengelolaan Standar SPMI

-   **Aktor**: Kepala Lembaga Penjaminan Mutu
-   **Deskripsi**: Kepala LPM dapat mengelola standar SPMI yang digunakan
-   **Alur Utama**:
    1. Kepala LPM mengakses halaman standar mutu
    2. Kepala LPM dapat menambah, mengedit, atau menghapus standar
    3. Untuk setiap standar, Kepala LPM dapat mendefinisikan kriteria dan indikator
    4. Sistem menyimpan perubahan pada standar mutu

### UC-2.2: Penetapan Target Mutu

-   **Aktor**: Kepala LPM, Pimpinan Universitas
-   **Deskripsi**: Pimpinan dapat menetapkan target mutu untuk setiap standar
-   **Alur Utama**:
    1. Pimpinan mengakses halaman target mutu
    2. Pimpinan memilih standar yang akan ditetapkan targetnya
    3. Pimpinan menentukan nilai target untuk setiap indikator kinerja
    4. Sistem menyimpan target mutu yang telah ditetapkan

## 3. Audit Mutu Internal

### UC-3.1: Perencanaan Audit

-   **Aktor**: Kepala LPM
-   **Deskripsi**: Kepala LPM dapat merencanakan jadwal audit mutu internal
-   **Alur Utama**:
    1. Kepala LPM mengakses halaman perencanaan audit
    2. Kepala LPM membuat jadwal audit baru
    3. Kepala LPM menentukan ruang lingkup audit
    4. Kepala LPM memilih tim auditor
    5. Sistem membuat jadwal audit dan mengirimkan notifikasi ke pihak terkait

### UC-3.2: Pelaksanaan Audit

-   **Aktor**: Tim Auditor
-   **Deskripsi**: Tim auditor melaksanakan audit dan mencatat temuan
-   **Alur Utama**:
    1. Auditor mengakses halaman pelaksanaan audit
    2. Auditor memilih jadwal audit yang akan dilaksanakan
    3. Auditor mencatat temuan audit berdasarkan checklist
    4. Auditor mengupload bukti pendukung
    5. Sistem menyimpan data audit

### UC-3.3: Tindak Lanjut Temuan Audit

-   **Aktor**: Dekan/Kaprodi/Unit Terkait
-   **Deskripsi**: Unit yang diaudit merespon temuan dan merencanakan tindak lanjut
-   **Alur Utama**:
    1. Unit terkait mengakses halaman temuan audit
    2. Unit terkait melihat daftar temuan untuk unitnya
    3. Unit terkait merespon setiap temuan dengan rencana tindak lanjut
    4. Unit terkait mengupload bukti perbaikan
    5. Sistem mencatat respons dan bukti perbaikan

## 4. Akreditasi

### UC-4.1: Persiapan Dokumen Akreditasi

-   **Aktor**: Dekan/Kaprodi
-   **Deskripsi**: Dekan/Kaprodi dapat menyiapkan dokumen untuk akreditasi
-   **Alur Utama**:
    1. Dekan/Kaprodi mengakses halaman persiapan akreditasi
    2. Sistem menampilkan daftar dokumen yang diperlukan
    3. Dekan/Kaprodi mengupload dokumen yang diminta
    4. Sistem menyimpan dokumen dan melakukan validasi kelengkapan

### UC-4.2: Simulasi Penilaian Akreditasi

-   **Aktor**: Kepala LPM, Dekan/Kaprodi
-   **Deskripsi**: Melakukan simulasi penilaian akreditasi untuk persiapan
-   **Alur Utama**:
    1. Pengguna mengakses halaman simulasi akreditasi
    2. Pengguna memilih standar akreditasi yang digunakan
    3. Sistem melakukan penilaian berdasarkan data yang tersedia
    4. Sistem menampilkan hasil simulasi dan rekomendasi perbaikan

## 5. Survei dan Evaluasi

### UC-5.1: Pembuatan Instrumen Survei

-   **Aktor**: Tim LPM
-   **Deskripsi**: Tim LPM dapat membuat instrumen survei untuk evaluasi
-   **Alur Utama**:
    1. Tim LPM mengakses halaman pembuatan survei
    2. Tim LPM membuat survei baru dengan menentukan jenis responden
    3. Tim LPM menambahkan pertanyaan-pertanyaan survei
    4. Sistem menyimpan instrumen survei

### UC-5.2: Pengisian Survei

-   **Aktor**: Mahasiswa, Dosen, Alumni, Pengguna Lulusan
-   **Deskripsi**: Responden mengisi survei yang diberikan
-   **Alur Utama**:
    1. Responden menerima notifikasi untuk mengisi survei
    2. Responden mengakses halaman survei
    3. Responden mengisi pertanyaan-pertanyaan survei
    4. Sistem menyimpan respons survei

### UC-5.3: Analisis Hasil Survei

-   **Aktor**: Tim LPM, Pimpinan
-   **Deskripsi**: Menganalisis hasil survei untuk evaluasi
-   **Alur Utama**:
    1. Pengguna mengakses halaman analisis survei
    2. Pengguna memilih survei yang akan dianalisis
    3. Sistem menampilkan data statistik dan visualisasi hasil survei
    4. Pengguna dapat mengekspor hasil analisis dalam berbagai format

## 6. Monitoring dan Evaluasi

### UC-6.1: Monitoring Pencapaian Standar

-   **Aktor**: Kepala LPM, Pimpinan
-   **Deskripsi**: Memantau pencapaian standar mutu
-   **Alur Utama**:
    1. Pengguna mengakses dashboard monitoring
    2. Sistem menampilkan indikator kinerja dan pencapaiannya
    3. Pengguna dapat melihat detail pencapaian per standar
    4. Sistem memberikan notifikasi jika ada standar yang tidak tercapai

### UC-6.2: Evaluasi Program Kerja

-   **Aktor**: Pimpinan Universitas, Dekan, Kaprodi
-   **Deskripsi**: Mengevaluasi program kerja terkait mutu
-   **Alur Utama**:
    1. Pengguna mengakses halaman evaluasi program kerja
    2. Pengguna memilih periode evaluasi
    3. Sistem menampilkan daftar program kerja dan status pencapaiannya
    4. Pengguna dapat menambahkan catatan evaluasi

## 7. Pelaporan

### UC-7.1: Pembuatan Laporan

-   **Aktor**: Tim LPM, Pimpinan
-   **Deskripsi**: Membuat berbagai laporan terkait mutu
-   **Alur Utama**:
    1. Pengguna mengakses halaman pembuatan laporan
    2. Pengguna memilih jenis laporan yang akan dibuat
    3. Pengguna menentukan parameter laporan (periode, unit, dll)
    4. Sistem menghasilkan laporan sesuai parameter
    5. Pengguna dapat mengekspor laporan dalam berbagai format

### UC-7.2: Distribusi Laporan

-   **Aktor**: Tim LPM
-   **Deskripsi**: Mendistribusikan laporan ke pihak terkait
-   **Alur Utama**:
    1. Tim LPM mengakses halaman distribusi laporan
    2. Tim LPM memilih laporan yang akan didistribusikan
    3. Tim LPM menentukan penerima laporan
    4. Sistem mengirimkan notifikasi dan akses laporan ke penerima
