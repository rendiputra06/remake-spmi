<?php

namespace Database\Seeders;

use App\Models\Faculty;
use App\Models\Department;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan superadmin dan kepala LPM
        $superAdmin = User::whereHas('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->first();

        $kepalaPm = User::whereHas('roles', function ($query) {
            $query->where('name', 'kepala-lpm');
        })->first();

        // Dapatkan fakultas dan departemen
        $faculty = Faculty::first();
        $department = Department::first();

        // 1. Survey evaluasi dosen
        $surveyDosen = Survey::create([
            'title' => 'Evaluasi Kinerja Dosen Semester Ganjil 2024/2025',
            'description' => 'Survey evaluasi kinerja dosen oleh mahasiswa untuk peningkatan kualitas pembelajaran',
            'status' => 'active',
            'visibility' => 'public',
            'start_date' => now()->subDays(5),
            'end_date' => now()->addMonths(1),
            'target_audience' => 'mahasiswa',
            'category' => 'evaluasi dosen',
            'is_anonymous' => true,
            'created_by' => $kepalaPm?->id,
            'faculty_id' => $faculty?->id,
            'department_id' => $department?->id,
        ]);

        if ($surveyDosen) {
            // Buat pertanyaan untuk survey dosen
            SurveyQuestion::create([
                'survey_id' => $surveyDosen->id,
                'question' => 'Dosen menyampaikan materi dengan jelas',
                'type' => 'scale',
                'min_value' => 1,
                'max_value' => 5,
                'min_label' => 'Sangat Tidak Setuju',
                'max_label' => 'Sangat Setuju',
                'order' => 1,
                'is_required' => true,
            ]);

            SurveyQuestion::create([
                'survey_id' => $surveyDosen->id,
                'question' => 'Dosen memberikan umpan balik terhadap hasil pekerjaan mahasiswa',
                'type' => 'scale',
                'min_value' => 1,
                'max_value' => 5,
                'min_label' => 'Sangat Tidak Setuju',
                'max_label' => 'Sangat Setuju',
                'order' => 2,
                'is_required' => true,
            ]);

            SurveyQuestion::create([
                'survey_id' => $surveyDosen->id,
                'question' => 'Metode pembelajaran apa yang paling efektif dilakukan dosen?',
                'type' => 'multiple_choice',
                'options' => json_encode(['Ceramah', 'Diskusi', 'Praktikum', 'Project Based', 'Blended Learning']),
                'order' => 3,
                'is_required' => true,
            ]);

            SurveyQuestion::create([
                'survey_id' => $surveyDosen->id,
                'question' => 'Berikan saran untuk peningkatan kualitas pembelajaran',
                'type' => 'text',
                'order' => 4,
                'is_required' => false,
            ]);
        }

        // 2. Survey kepuasan layanan
        $surveyLayanan = Survey::create([
            'title' => 'Survey Kepuasan Layanan Akademik',
            'description' => 'Survey untuk mengevaluasi kualitas layanan akademik',
            'status' => 'draft',
            'visibility' => 'private',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addMonths(1)->addDays(10),
            'target_audience' => 'mahasiswa,dosen',
            'category' => 'layanan',
            'is_anonymous' => true,
            'created_by' => $superAdmin?->id,
        ]);

        if ($surveyLayanan) {
            // Buat pertanyaan untuk survey layanan
            SurveyQuestion::create([
                'survey_id' => $surveyLayanan->id,
                'question' => 'Bagaimana tingkat kepuasan Anda terhadap layanan administrasi akademik?',
                'type' => 'scale',
                'min_value' => 1,
                'max_value' => 5,
                'min_label' => 'Sangat Tidak Puas',
                'max_label' => 'Sangat Puas',
                'order' => 1,
                'is_required' => true,
            ]);

            SurveyQuestion::create([
                'survey_id' => $surveyLayanan->id,
                'question' => 'Layanan apa yang perlu ditingkatkan?',
                'type' => 'checkbox',
                'options' => json_encode(['Pendaftaran Mata Kuliah', 'Pembayaran SPP', 'Pengurusan Surat', 'Konsultasi Akademik', 'Fasilitas Kampus']),
                'order' => 2,
                'is_required' => true,
            ]);

            SurveyQuestion::create([
                'survey_id' => $surveyLayanan->id,
                'question' => 'Berikan saran untuk peningkatan layanan',
                'type' => 'text',
                'order' => 3,
                'is_required' => false,
            ]);
        }

        // 3. Survey kepuasan alumni
        $surveyAlumni = Survey::create([
            'title' => 'Tracer Study Alumni 2024',
            'description' => 'Survey untuk mengetahui perkembangan karir alumni dan umpan balik terhadap kurikulum',
            'status' => 'closed',
            'visibility' => 'public',
            'start_date' => now()->subMonths(3),
            'end_date' => now()->subDays(15),
            'target_audience' => 'alumni',
            'category' => 'tracer study',
            'is_anonymous' => false,
            'created_by' => $kepalaPm?->id,
            'faculty_id' => $faculty?->id,
        ]);

        if ($surveyAlumni) {
            // Buat pertanyaan untuk survey alumni
            SurveyQuestion::create([
                'survey_id' => $surveyAlumni->id,
                'question' => 'Tahun lulus',
                'type' => 'dropdown',
                'options' => json_encode(['2018', '2019', '2020', '2021', '2022', '2023']),
                'order' => 1,
                'is_required' => true,
            ]);

            SurveyQuestion::create([
                'survey_id' => $surveyAlumni->id,
                'question' => 'Berapa lama waktu tunggu sampai mendapatkan pekerjaan pertama?',
                'type' => 'multiple_choice',
                'options' => json_encode(['< 3 bulan', '3-6 bulan', '6-12 bulan', '> 12 bulan', 'Belum bekerja']),
                'order' => 2,
                'is_required' => true,
            ]);

            SurveyQuestion::create([
                'survey_id' => $surveyAlumni->id,
                'question' => 'Seberapa relevan kurikulum dengan kebutuhan dunia kerja?',
                'type' => 'scale',
                'min_value' => 1,
                'max_value' => 5,
                'min_label' => 'Sangat Tidak Relevan',
                'max_label' => 'Sangat Relevan',
                'order' => 3,
                'is_required' => true,
            ]);

            SurveyQuestion::create([
                'survey_id' => $surveyAlumni->id,
                'question' => 'Kompetensi apa yang perlu ditingkatkan dalam kurikulum?',
                'type' => 'text',
                'order' => 4,
                'is_required' => false,
            ]);
        }
    }
}
