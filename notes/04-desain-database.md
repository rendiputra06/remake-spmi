# Desain Database Sistem Informasi Penjaminan Mutu Internal (SPMI)

## Skema Database

Berikut adalah struktur tabel database yang diusulkan untuk Sistem Informasi Penjaminan Mutu Internal (SPMI):

### Manajemen Pengguna

#### Tabel: users

| Kolom             | Tipe Data    | Keterangan                      |
| ----------------- | ------------ | ------------------------------- |
| id                | bigint       | Primary Key, Auto Increment     |
| name              | varchar(255) | Nama lengkap pengguna           |
| email             | varchar(255) | Email pengguna (unique)         |
| email_verified_at | timestamp    | Waktu verifikasi email          |
| password          | varchar(255) | Password terenkripsi            |
| remember_token    | varchar(100) | Token untuk fitur "remember me" |
| created_at        | timestamp    | Waktu pembuatan record          |
| updated_at        | timestamp    | Waktu update record             |

#### Tabel: roles (dari spatie/permission)

| Kolom      | Tipe Data    | Keterangan                  |
| ---------- | ------------ | --------------------------- |
| id         | bigint       | Primary Key, Auto Increment |
| name       | varchar(255) | Nama peran                  |
| guard_name | varchar(255) | Guard untuk autentikasi     |
| created_at | timestamp    | Waktu pembuatan record      |
| updated_at | timestamp    | Waktu update record         |

#### Tabel: permissions (dari spatie/permission)

| Kolom      | Tipe Data    | Keterangan                  |
| ---------- | ------------ | --------------------------- |
| id         | bigint       | Primary Key, Auto Increment |
| name       | varchar(255) | Nama permission             |
| guard_name | varchar(255) | Guard untuk autentikasi     |
| created_at | timestamp    | Waktu pembuatan record      |
| updated_at | timestamp    | Waktu update record         |

#### Tabel: model_has_roles (dari spatie/permission)

| Kolom      | Tipe Data    | Keterangan                            |
| ---------- | ------------ | ------------------------------------- |
| role_id    | bigint       | Foreign Key ke roles.id               |
| model_type | varchar(255) | Tipe model (biasanya App\Models\User) |
| model_id   | bigint       | ID dari model (user_id)               |

#### Tabel: model_has_permissions (dari spatie/permission)

| Kolom         | Tipe Data    | Keterangan                            |
| ------------- | ------------ | ------------------------------------- |
| permission_id | bigint       | Foreign Key ke permissions.id         |
| model_type    | varchar(255) | Tipe model (biasanya App\Models\User) |
| model_id      | bigint       | ID dari model (user_id)               |

#### Tabel: role_has_permissions (dari spatie/permission)

| Kolom         | Tipe Data | Keterangan                    |
| ------------- | --------- | ----------------------------- |
| permission_id | bigint    | Foreign Key ke permissions.id |
| role_id       | bigint    | Foreign Key ke roles.id       |

#### Tabel: user_profiles

| Kolom         | Tipe Data    | Keterangan                              |
| ------------- | ------------ | --------------------------------------- |
| id            | bigint       | Primary Key, Auto Increment             |
| user_id       | bigint       | Foreign Key ke users.id                 |
| faculty_id    | bigint       | Foreign Key ke faculties.id, nullable   |
| department_id | bigint       | Foreign Key ke departments.id, nullable |
| position      | varchar(255) | Jabatan pengguna                        |
| phone         | varchar(20)  | Nomor telepon                           |
| address       | text         | Alamat                                  |
| photo         | varchar(255) | Path foto profil, nullable              |
| created_at    | timestamp    | Waktu pembuatan record                  |
| updated_at    | timestamp    | Waktu update record                     |

### Struktur Organisasi

#### Tabel: faculties

| Kolom       | Tipe Data    | Keterangan                        |
| ----------- | ------------ | --------------------------------- |
| id          | bigint       | Primary Key, Auto Increment       |
| name        | varchar(255) | Nama fakultas                     |
| code        | varchar(20)  | Kode fakultas                     |
| description | text         | Deskripsi fakultas, nullable      |
| dean_id     | bigint       | Foreign Key ke users.id, nullable |
| created_at  | timestamp    | Waktu pembuatan record            |
| updated_at  | timestamp    | Waktu update record               |

#### Tabel: departments

| Kolom              | Tipe Data    | Keterangan                        |
| ------------------ | ------------ | --------------------------------- |
| id                 | bigint       | Primary Key, Auto Increment       |
| faculty_id         | bigint       | Foreign Key ke faculties.id       |
| name               | varchar(255) | Nama program studi                |
| code               | varchar(20)  | Kode program studi                |
| description        | text         | Deskripsi program studi, nullable |
| head_id            | bigint       | Foreign Key ke users.id, nullable |
| accreditation      | varchar(50)  | Status akreditasi, nullable       |
| accreditation_date | date         | Tanggal akreditasi, nullable      |
| created_at         | timestamp    | Waktu pembuatan record            |
| updated_at         | timestamp    | Waktu update record               |

#### Tabel: units

| Kolom       | Tipe Data    | Keterangan                        |
| ----------- | ------------ | --------------------------------- |
| id          | bigint       | Primary Key, Auto Increment       |
| name        | varchar(255) | Nama unit                         |
| code        | varchar(20)  | Kode unit                         |
| description | text         | Deskripsi unit, nullable          |
| head_id     | bigint       | Foreign Key ke users.id, nullable |
| created_at  | timestamp    | Waktu pembuatan record            |
| updated_at  | timestamp    | Waktu update record               |

### Standar Mutu

#### Tabel: standard_categories

| Kolom       | Tipe Data    | Keterangan                   |
| ----------- | ------------ | ---------------------------- |
| id          | bigint       | Primary Key, Auto Increment  |
| name        | varchar(255) | Nama kategori standar        |
| code        | varchar(20)  | Kode kategori                |
| description | text         | Deskripsi kategori, nullable |
| created_at  | timestamp    | Waktu pembuatan record       |
| updated_at  | timestamp    | Waktu update record          |

#### Tabel: quality_standards

| Kolom       | Tipe Data    | Keterangan                            |
| ----------- | ------------ | ------------------------------------- |
| id          | bigint       | Primary Key, Auto Increment           |
| category_id | bigint       | Foreign Key ke standard_categories.id |
| name        | varchar(255) | Nama standar mutu                     |
| code        | varchar(20)  | Kode standar                          |
| description | text         | Deskripsi standar                     |
| created_at  | timestamp    | Waktu pembuatan record                |
| updated_at  | timestamp    | Waktu update record                   |

#### Tabel: standard_indicators

| Kolom            | Tipe Data    | Keterangan                          |
| ---------------- | ------------ | ----------------------------------- |
| id               | bigint       | Primary Key, Auto Increment         |
| standard_id      | bigint       | Foreign Key ke quality_standards.id |
| name             | varchar(255) | Nama indikator                      |
| code             | varchar(20)  | Kode indikator                      |
| description      | text         | Deskripsi indikator                 |
| measure_unit     | varchar(50)  | Satuan ukuran                       |
| is_key_indicator | boolean      | Apakah IKU (true) atau IKT (false)  |
| created_at       | timestamp    | Waktu pembuatan record              |
| updated_at       | timestamp    | Waktu update record                 |

#### Tabel: indicator_targets

| Kolom         | Tipe Data | Keterangan                              |
| ------------- | --------- | --------------------------------------- |
| id            | bigint    | Primary Key, Auto Increment             |
| indicator_id  | bigint    | Foreign Key ke standard_indicators.id   |
| department_id | bigint    | Foreign Key ke departments.id, nullable |
| faculty_id    | bigint    | Foreign Key ke faculties.id, nullable   |
| unit_id       | bigint    | Foreign Key ke units.id, nullable       |
| year          | year      | Tahun target                            |
| target_value  | float     | Nilai target                            |
| created_by    | bigint    | Foreign Key ke users.id                 |
| created_at    | timestamp | Waktu pembuatan record                  |
| updated_at    | timestamp | Waktu update record                     |

### Audit Mutu Internal

#### Tabel: audit_periods

| Kolom       | Tipe Data    | Keterangan                          |
| ----------- | ------------ | ----------------------------------- |
| id          | bigint       | Primary Key, Auto Increment         |
| name        | varchar(255) | Nama periode audit                  |
| start_date  | date         | Tanggal mulai                       |
| end_date    | date         | Tanggal selesai                     |
| year        | year         | Tahun periode                       |
| description | text         | Deskripsi periode audit             |
| status      | enum         | Draft, Active, Completed, Cancelled |
| created_by  | bigint       | Foreign Key ke users.id             |
| created_at  | timestamp    | Waktu pembuatan record              |
| updated_at  | timestamp    | Waktu update record                 |

#### Tabel: audit_schedules

| Kolom           | Tipe Data | Keterangan                                              |
| --------------- | --------- | ------------------------------------------------------- |
| id              | bigint    | Primary Key, Auto Increment                             |
| period_id       | bigint    | Foreign Key ke audit_periods.id                         |
| department_id   | bigint    | Foreign Key ke departments.id, nullable                 |
| faculty_id      | bigint    | Foreign Key ke faculties.id, nullable                   |
| unit_id         | bigint    | Foreign Key ke units.id, nullable                       |
| audit_date      | date      | Tanggal audit                                           |
| status          | enum      | Scheduled, In Progress, Completed, Postponed, Cancelled |
| lead_auditor_id | bigint    | Foreign Key ke users.id                                 |
| created_by      | bigint    | Foreign Key ke users.id                                 |
| created_at      | timestamp | Waktu pembuatan record                                  |
| updated_at      | timestamp | Waktu update record                                     |

#### Tabel: audit_team_members

| Kolom       | Tipe Data | Keterangan                        |
| ----------- | --------- | --------------------------------- |
| id          | bigint    | Primary Key, Auto Increment       |
| schedule_id | bigint    | Foreign Key ke audit_schedules.id |
| auditor_id  | bigint    | Foreign Key ke users.id           |
| role        | enum      | Lead Auditor, Auditor, Observer   |
| created_at  | timestamp | Waktu pembuatan record            |
| updated_at  | timestamp | Waktu update record               |

#### Tabel: audit_findings

| Kolom             | Tipe Data | Keterangan                                               |
| ----------------- | --------- | -------------------------------------------------------- |
| id                | bigint    | Primary Key, Auto Increment                              |
| schedule_id       | bigint    | Foreign Key ke audit_schedules.id                        |
| indicator_id      | bigint    | Foreign Key ke standard_indicators.id                    |
| finding_type      | enum      | Non-Conformity, Observation, Opportunity for Improvement |
| description       | text      | Deskripsi temuan                                         |
| evidence          | text      | Bukti temuan                                             |
| root_cause        | text      | Akar masalah, nullable                                   |
| correction        | text      | Koreksi, nullable                                        |
| corrective_action | text      | Tindakan perbaikan, nullable                             |
| status            | enum      | Open, In Progress, Verified, Closed                      |
| deadline          | date      | Batas waktu perbaikan                                    |
| created_by        | bigint    | Foreign Key ke users.id                                  |
| created_at        | timestamp | Waktu pembuatan record                                   |
| updated_at        | timestamp | Waktu update record                                      |

#### Tabel: finding_responses

| Kolom             | Tipe Data | Keterangan                        |
| ----------------- | --------- | --------------------------------- |
| id                | bigint    | Primary Key, Auto Increment       |
| finding_id        | bigint    | Foreign Key ke audit_findings.id  |
| response          | text      | Respons terhadap temuan           |
| root_cause        | text      | Akar masalah                      |
| correction        | text      | Koreksi yang dilakukan            |
| corrective_action | text      | Tindakan perbaikan jangka panjang |
| created_by        | bigint    | Foreign Key ke users.id           |
| created_at        | timestamp | Waktu pembuatan record            |
| updated_at        | timestamp | Waktu update record               |

#### Tabel: finding_verifications

| Kolom              | Tipe Data | Keterangan                       |
| ------------------ | --------- | -------------------------------- |
| id                 | bigint    | Primary Key, Auto Increment      |
| finding_id         | bigint    | Foreign Key ke audit_findings.id |
| is_verified        | boolean   | Apakah telah diverifikasi        |
| verification_notes | text      | Catatan verifikasi               |
| verification_date  | date      | Tanggal verifikasi               |
| verified_by        | bigint    | Foreign Key ke users.id          |
| created_at         | timestamp | Waktu pembuatan record           |
| updated_at         | timestamp | Waktu update record              |

### Dokumen

#### Tabel: document_categories

| Kolom       | Tipe Data    | Keterangan                   |
| ----------- | ------------ | ---------------------------- |
| id          | bigint       | Primary Key, Auto Increment  |
| name        | varchar(255) | Nama kategori dokumen        |
| description | text         | Deskripsi kategori, nullable |
| created_at  | timestamp    | Waktu pembuatan record       |
| updated_at  | timestamp    | Waktu update record          |

#### Tabel: documents

| Kolom         | Tipe Data    | Keterangan                              |
| ------------- | ------------ | --------------------------------------- |
| id            | bigint       | Primary Key, Auto Increment             |
| category_id   | bigint       | Foreign Key ke document_categories.id   |
| title         | varchar(255) | Judul dokumen                           |
| description   | text         | Deskripsi dokumen                       |
| file_path     | varchar(255) | Path file dokumen                       |
| file_type     | varchar(50)  | Tipe file                               |
| file_size     | integer      | Ukuran file (KB)                        |
| version       | varchar(20)  | Versi dokumen                           |
| is_active     | boolean      | Status aktif dokumen                    |
| department_id | bigint       | Foreign Key ke departments.id, nullable |
| faculty_id    | bigint       | Foreign Key ke faculties.id, nullable   |
| unit_id       | bigint       | Foreign Key ke units.id, nullable       |
| created_by    | bigint       | Foreign Key ke users.id                 |
| created_at    | timestamp    | Waktu pembuatan record                  |
| updated_at    | timestamp    | Waktu update record                     |

#### Tabel: document_versions

| Kolom       | Tipe Data    | Keterangan                  |
| ----------- | ------------ | --------------------------- |
| id          | bigint       | Primary Key, Auto Increment |
| document_id | bigint       | Foreign Key ke documents.id |
| version     | varchar(20)  | Versi dokumen               |
| file_path   | varchar(255) | Path file dokumen           |
| changes     | text         | Perubahan pada versi ini    |
| created_by  | bigint       | Foreign Key ke users.id     |
| created_at  | timestamp    | Waktu pembuatan record      |
| updated_at  | timestamp    | Waktu update record         |

### Survei

#### Tabel: survey_templates

| Kolom           | Tipe Data    | Keterangan                                 |
| --------------- | ------------ | ------------------------------------------ |
| id              | bigint       | Primary Key, Auto Increment                |
| title           | varchar(255) | Judul template survei                      |
| description     | text         | Deskripsi template survei                  |
| respondent_type | enum         | Student, Lecturer, Alumni, Employer, Staff |
| is_active       | boolean      | Status aktif template                      |
| created_by      | bigint       | Foreign Key ke users.id                    |
| created_at      | timestamp    | Waktu pembuatan record                     |
| updated_at      | timestamp    | Waktu update record                        |

#### Tabel: survey_questions

| Kolom         | Tipe Data | Keterangan                                             |
| ------------- | --------- | ------------------------------------------------------ |
| id            | bigint    | Primary Key, Auto Increment                            |
| template_id   | bigint    | Foreign Key ke survey_templates.id                     |
| question      | text      | Pertanyaan survei                                      |
| question_type | enum      | Multiple Choice, Checkbox, Rating, Text                |
| options       | json      | Opsi jawaban (untuk tipe multiple choice dan checkbox) |
| is_required   | boolean   | Apakah wajib dijawab                                   |
| order         | integer   | Urutan pertanyaan                                      |
| created_at    | timestamp | Waktu pembuatan record                                 |
| updated_at    | timestamp | Waktu update record                                    |

#### Tabel: surveys

| Kolom         | Tipe Data    | Keterangan                              |
| ------------- | ------------ | --------------------------------------- |
| id            | bigint       | Primary Key, Auto Increment             |
| template_id   | bigint       | Foreign Key ke survey_templates.id      |
| title         | varchar(255) | Judul survei                            |
| description   | text         | Deskripsi survei                        |
| start_date    | date         | Tanggal mulai                           |
| end_date      | date         | Tanggal selesai                         |
| department_id | bigint       | Foreign Key ke departments.id, nullable |
| faculty_id    | bigint       | Foreign Key ke faculties.id, nullable   |
| unit_id       | bigint       | Foreign Key ke units.id, nullable       |
| status        | enum         | Draft, Active, Closed, Analyzed         |
| created_by    | bigint       | Foreign Key ke users.id                 |
| created_at    | timestamp    | Waktu pembuatan record                  |
| updated_at    | timestamp    | Waktu update record                     |

#### Tabel: survey_respondents

| Kolom        | Tipe Data    | Keterangan                                  |
| ------------ | ------------ | ------------------------------------------- |
| id           | bigint       | Primary Key, Auto Increment                 |
| survey_id    | bigint       | Foreign Key ke surveys.id                   |
| user_id      | bigint       | Foreign Key ke users.id, nullable           |
| email        | varchar(255) | Email responden (untuk responden eksternal) |
| token        | varchar(100) | Token untuk akses survei                    |
| is_completed | boolean      | Status penyelesaian survei                  |
| completed_at | timestamp    | Waktu penyelesaian survei                   |
| created_at   | timestamp    | Waktu pembuatan record                      |
| updated_at   | timestamp    | Waktu update record                         |

#### Tabel: survey_responses

| Kolom         | Tipe Data | Keterangan                           |
| ------------- | --------- | ------------------------------------ |
| id            | bigint    | Primary Key, Auto Increment          |
| respondent_id | bigint    | Foreign Key ke survey_respondents.id |
| question_id   | bigint    | Foreign Key ke survey_questions.id   |
| answer        | text      | Jawaban pertanyaan                   |
| created_at    | timestamp | Waktu pembuatan record               |
| updated_at    | timestamp | Waktu update record                  |

### Pelaporan

#### Tabel: report_templates

| Kolom         | Tipe Data    | Keterangan                                |
| ------------- | ------------ | ----------------------------------------- |
| id            | bigint       | Primary Key, Auto Increment               |
| name          | varchar(255) | Nama template laporan                     |
| description   | text         | Deskripsi template                        |
| type          | enum         | Audit, Survey, Performance, Accreditation |
| template_path | varchar(255) | Path file template                        |
| created_by    | bigint       | Foreign Key ke users.id                   |
| created_at    | timestamp    | Waktu pembuatan record                    |
| updated_at    | timestamp    | Waktu update record                       |

#### Tabel: reports

| Kolom         | Tipe Data    | Keterangan                              |
| ------------- | ------------ | --------------------------------------- |
| id            | bigint       | Primary Key, Auto Increment             |
| template_id   | bigint       | Foreign Key ke report_templates.id      |
| title         | varchar(255) | Judul laporan                           |
| description   | text         | Deskripsi laporan                       |
| period        | varchar(100) | Periode laporan                         |
| department_id | bigint       | Foreign Key ke departments.id, nullable |
| faculty_id    | bigint       | Foreign Key ke faculties.id, nullable   |
| unit_id       | bigint       | Foreign Key ke units.id, nullable       |
| file_path     | varchar(255) | Path file laporan                       |
| created_by    | bigint       | Foreign Key ke users.id                 |
| created_at    | timestamp    | Waktu pembuatan record                  |
| updated_at    | timestamp    | Waktu update record                     |

#### Tabel: report_distributions

| Kolom        | Tipe Data | Keterangan                  |
| ------------ | --------- | --------------------------- |
| id           | bigint    | Primary Key, Auto Increment |
| report_id    | bigint    | Foreign Key ke reports.id   |
| recipient_id | bigint    | Foreign Key ke users.id     |
| status       | enum      | Sent, Read, Acknowledged    |
| sent_at      | timestamp | Waktu pengiriman            |
| read_at      | timestamp | Waktu dibaca                |
| created_at   | timestamp | Waktu pembuatan record      |
| updated_at   | timestamp | Waktu update record         |

### Notifikasi

#### Tabel: notifications

| Kolom           | Tipe Data    | Keterangan                         |
| --------------- | ------------ | ---------------------------------- |
| id              | uuid         | Primary Key                        |
| type            | varchar(255) | Tipe notifikasi (class notifikasi) |
| notifiable_type | varchar(255) | Jenis model penerima notifikasi    |
| notifiable_id   | bigint       | ID penerima notifikasi             |
| data            | json         | Data notifikasi                    |
| read_at         | timestamp    | Waktu dibaca, nullable             |
| created_at      | timestamp    | Waktu pembuatan record             |
| updated_at      | timestamp    | Waktu update record                |

## Relasi Database

1. **Users & Roles**:

    - User memiliki banyak roles (many-to-many)
    - User memiliki banyak permissions (many-to-many)
    - Role memiliki banyak permissions (many-to-many)
    - User memiliki satu user_profile (one-to-one)

2. **Organisasi**:

    - Faculty memiliki banyak departments (one-to-many)
    - Faculty/Department/Unit memiliki satu kepala (one-to-one)
    - User dapat berada dalam faculty, department, atau unit (many-to-one)

3. **Standar Mutu**:

    - standard_category memiliki banyak quality_standards (one-to-many)
    - quality_standard memiliki banyak standard_indicators (one-to-many)
    - standard_indicator memiliki banyak indicator_targets (one-to-many)

4. **Audit**:

    - audit_period memiliki banyak audit_schedules (one-to-many)
    - audit_schedule memiliki banyak audit_team_members (one-to-many)
    - audit_schedule memiliki banyak audit_findings (one-to-many)
    - audit_finding memiliki satu finding_response (one-to-one)
    - audit_finding memiliki satu finding_verification (one-to-one)

5. **Dokumen**:

    - document_category memiliki banyak documents (one-to-many)
    - document memiliki banyak document_versions (one-to-many)
    - document dapat dimiliki oleh faculty/department/unit (many-to-one)

6. **Survei**:

    - survey_template memiliki banyak survey_questions (one-to-many)
    - survey_template memiliki banyak surveys (one-to-many)
    - survey memiliki banyak survey_respondents (one-to-many)
    - survey_respondent memiliki banyak survey_responses (one-to-many)
    - survey dapat dimiliki oleh faculty/department/unit (many-to-one)

7. **Laporan**:
    - report_template memiliki banyak reports (one-to-many)
    - report memiliki banyak report_distributions (one-to-many)
    - report dapat dimiliki oleh faculty/department/unit (many-to-one)
