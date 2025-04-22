# Perancangan UI/UX Sistem Informasi Penjaminan Mutu Internal (SPMI)

## Prinsip Desain

Desain UI/UX untuk Sistem Informasi Penjaminan Mutu Internal mengikuti prinsip-prinsip berikut:

1. **Konsistensi** - Mempertahankan konsistensi dalam layout, warna, tipografi, dan interaksi di seluruh aplikasi
2. **Kesederhanaan** - Menyederhanakan alur kerja dan mengurangi keputusan yang harus dibuat pengguna
3. **Hierarki Visual** - Menggunakan ukuran, warna, dan penempatan untuk menunjukkan hirarki kepentingan
4. **Aksesibilitas** - Memastikan aplikasi mudah digunakan oleh semua pengguna, termasuk yang memiliki keterbatasan
5. **Responsif** - Aplikasi berfungsi dengan baik di berbagai perangkat dan ukuran layar

## Skema Warna

### Warna Utama

-   **Primary**: #336699 (Biru tua) - Warna utama aplikasi, digunakan untuk elemen penting seperti tombol utama dan header
-   **Secondary**: #4A90E2 (Biru muda) - Warna sekunder untuk elemen pendukung dan aksen

### Warna Aksen

-   **Success**: #28A745 (Hijau) - Untuk notifikasi sukses dan indikator pencapaian
-   **Warning**: #FFC107 (Kuning) - Untuk peringatan dan indikator perhatian
-   **Danger**: #DC3545 (Merah) - Untuk kesalahan dan indikator masalah
-   **Info**: #17A2B8 (Biru-hijau) - Untuk informasi tambahan

### Warna Netral

-   **Dark**: #343A40 (Abu-abu gelap) - Untuk teks utama
-   **Gray-700**: #495057 (Abu-abu sedang) - Untuk teks sekunder
-   **Gray-500**: #ADB5BD (Abu-abu terang) - Untuk border dan elemen non-aktif
-   **Gray-200**: #E9ECEF (Abu-abu sangat terang) - Untuk background sekunder
-   **Light**: #F8F9FA (Putih keabu-abuan) - Untuk background utama

## Tipografi

### Font

-   **Heading**: Roboto, sans-serif
-   **Body**: Inter, sans-serif

### Ukuran

-   **Heading 1**: 24px
-   **Heading 2**: 20px
-   **Heading 3**: 18px
-   **Heading 4**: 16px
-   **Body**: 14px
-   **Small**: 12px

## Layout

### Grid System

-   Menggunakan grid system 12 kolom untuk layout
-   Gutter width: 24px
-   Breakpoints:
    -   **xs**: <576px (Ponsel)
    -   **sm**: ≥576px (Ponsel landscape)
    -   **md**: ≥768px (Tablet)
    -   **lg**: ≥992px (Desktop)
    -   **xl**: ≥1200px (Desktop large)

### Template Dasar

1. **Layout Utama**

    - Sidebar navigasi (dapat dikecilkan pada perangkat mobile)
    - Header atas dengan breadcrumb, notifikasi, dan profil pengguna
    - Area konten utama
    - Footer dengan informasi hak cipta dan versi

2. **Layout Dashboard**

    - Kartu statistik pada bagian atas
    - Grafik dan visualisasi data di tengah
    - Tabel/daftar informasi penting di bawah

3. **Layout Form**
    - Label di atas input field
    - Validasi real-time dengan pesan kesalahan inline
    - Tombol aksi di bagian bawah

## Komponen UI

### 1. Navigasi

**Sidebar Menu**

-   Logo universitas di bagian atas
-   Menu navigasi utama dengan ikon dan teks
-   Submenu dengan indentasi
-   Indikator visual untuk menu aktif
-   Toggle untuk mengecilkan sidebar

**Header**

-   Breadcrumb untuk navigasi
-   Dropdown notifikasi
-   Dropdown profil pengguna dengan akses cepat ke pengaturan dan logout

### 2. Card & Container

**Card**

-   Header dengan judul
-   Body untuk konten
-   Footer opsional untuk aksi
-   Variasi: card standar, card statistik, card info

**Panel**

-   Panel dengan header dan toggle collapse
-   Panel grouping untuk FAQ atau informasi terstruktur

### 3. Form Element

**Input Field**

-   Text input
-   Textarea
-   Select dropdown
-   Date picker
-   File upload dengan drag-and-drop

**Button**

-   Primary button
-   Secondary button
-   Outline button
-   Icon button
-   Button group
-   Loading state

### 4. Data Display

**Table**

-   Sortable columns
-   Pagination
-   Column filtering
-   Row selection
-   Expandable rows untuk detail

**Chart & Graph**

-   Line chart
-   Bar chart
-   Pie/Doughnut chart
-   Gauge chart
-   Radar chart

**Dashboard Widget**

-   Statistic card
-   Progress indicator
-   Status indicator

### 5. Notification & Alert

**Toast Notification**

-   Success
-   Warning
-   Error
-   Info

**Modal Dialog**

-   Confirmation modal
-   Form modal
-   Alert modal

**Badge & Tag**

-   Status badge
-   Counter badge
-   Tag dengan opsi close

## Wireframe Utama

### 1. Halaman Login

```
+----------------------------------+
|                                  |
|            [LOGO]                |
|                                  |
|            SPMI SYSTEM           |
|                                  |
|  +----------------------------+  |
|  |      Email/Username       |  |
|  +----------------------------+  |
|                                  |
|  +----------------------------+  |
|  |         Password          |  |
|  +----------------------------+  |
|                                  |
|  [Forgot Password?]              |
|                                  |
|  +----------------------------+  |
|  |           LOGIN           |  |
|  +----------------------------+  |
|                                  |
+----------------------------------+
```

### 2. Dashboard

```
+----------------------------------+
| LOGO | [Search] [Notif] [User ▼] |
+------+---------------------------+
|      |                           |
| MENU | DASHBOARD > Main          |
|      |                           |
|      | +-------+ +-------+       |
|      | |Standar| |Audit  |       |
|      | |  30   | |  12   |       |
|      | +-------+ +-------+       |
|      |                           |
|      | +-------+ +-------+       |
|      | |Dokumen| |Aktivitas|     |
|      | |  120  | |   45    |     |
|      | +-------+ +-------+       |
|      |                           |
|      | [CHART: PENCAPAIAN MUTU]  |
|      |                           |
|      | +-------------------------+|
|      | | AKTIVITAS TERBARU       ||
|      | | - Audit Prodi X (10/10) ||
|      | | - Survei Kepuasan (9/10)||
|      | | - Upload Dokumen (8/10) ||
|      | +-------------------------+|
|      |                           |
+------+---------------------------+
```

### 3. Halaman Standar Mutu

```
+----------------------------------+
| LOGO | [Search] [Notif] [User ▼] |
+------+---------------------------+
|      |                           |
| MENU | STANDAR MUTU > Daftar     |
|      |                           |
|      | [Filter ▼] [+ Tambah]     |
|      |                           |
|      | +-------------------------+|
|      | | TABEL STANDAR MUTU      ||
|      | |-------------------------||
|      | | Kode | Nama | Kategori  ||
|      | |-------------------------||
|      | | SM01 | ... | Akademik   ||
|      | | SM02 | ... | Akademik   ||
|      | | SM03 | ... | Non-Akd    ||
|      | | ...  | ... | ...        ||
|      | +-------------------------+|
|      |                           |
|      | [< 1 2 3 ... >]           |
|      |                           |
+------+---------------------------+
```

### 4. Halaman Detail Standar

```
+----------------------------------+
| LOGO | [Search] [Notif] [User ▼] |
+------+---------------------------+
|      |                           |
| MENU | STANDAR > Detail > SM01   |
|      |                           |
|      | +-------------------------+|
|      | | Informasi Standar       ||
|      | | Kode: SM01              ||
|      | | Nama: Standar Kurikulum ||
|      | | Kategori: Akademik      ||
|      | | Deskripsi: ...          ||
|      | +-------------------------+|
|      |                           |
|      | +-------------------------+|
|      | | INDIKATOR                ||
|      | |-------------------------||
|      | | Kode | Nama | Target    ||
|      | |-------------------------||
|      | | I001 | ... | 80%        ||
|      | | I002 | ... | 100%       ||
|      | | [+ Tambah Indikator]    ||
|      | +-------------------------+|
|      |                           |
|      | [Edit] [Hapus] [Kembali]  |
|      |                           |
+------+---------------------------+
```

### 5. Halaman Audit

```
+----------------------------------+
| LOGO | [Search] [Notif] [User ▼] |
+------+---------------------------+
|      |                           |
| MENU | AUDIT > Jadwal            |
|      |                           |
|      | [Filter ▼] [+ Tambah]     |
|      |                           |
|      | +-------------------------+|
|      | | TABEL JADWAL AUDIT      ||
|      | |-------------------------||
|      | | Tanggal | Unit | Status ||
|      | |-------------------------||
|      | | 10/10/23| P.SI | Aktif  ||
|      | | 12/10/23| P.TI | Draft  ||
|      | | ...     | ...  | ...    ||
|      | +-------------------------+|
|      |                           |
|      | [CALENDAR VIEW]           |
|      |                           |
+------+---------------------------+
```

## Prototipe Interaktif

Untuk pengembangan lebih lanjut, prototipe interaktif akan dibuat menggunakan Figma dengan memperhatikan beberapa alur pengguna utama:

1. Alur login dan pengaturan profil
2. Alur pengelolaan standar mutu
3. Alur perencanaan dan pelaksanaan audit
4. Alur pengisian survei
5. Alur pembuatan laporan

## Responsive Design

Desain responsif akan memastikan aplikasi berfungsi dengan baik pada berbagai perangkat:

1. **Desktop View** (≥992px)

    - Sidebar menu expanded
    - Multi-column layout
    - Full data tables

2. **Tablet View** (768px-991px)

    - Sidebar menu collapsible
    - Simplified layout dengan lebih sedikit kolom
    - Scrollable tables

3. **Mobile View** (<768px)
    - Sidebar menu hidden by default (activated by hamburger menu)
    - Single column layout
    - Card view untuk data tabel
    - Simplified charts dan visualisasi

## Guidelines Interaksi

1. **Form Submission**

    - Validasi real-time dengan pesan error yang jelas
    - Konfirmasi sebelum mengirim data penting
    - Feedback visual setelah submission

2. **Data Loading**

    - Skeleton loading untuk konten yang sedang dimuat
    - Infinite scroll untuk daftar panjang
    - Pagination untuk tabel data

3. **Notifikasi**

    - Toast notification untuk feedback cepat (3-5 detik)
    - Sticky notification untuk informasi penting
    - Badge counter untuk notifikasi yang belum dibaca

4. **Navigation**
    - Keep breadcrumb trail untuk navigasi bertingkat
    - Recently visited untuk navigasi cepat
    - Save state untuk form yang kompleks
