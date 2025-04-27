<?php

namespace Database\Seeders;

use App\Models\Accreditation;
use App\Models\AccreditationStandard;
use App\Models\AccreditationDocument;
use App\Models\AccreditationEvaluation;
use App\Models\Department;
use App\Models\Document;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccreditationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan superadmin
        $superAdmin = User::whereHas('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->first();

        // Dapatkan coordinator (kepala-lpm)
        $coordinator = User::whereHas('roles', function ($query) {
            $query->where('name', 'kepala-lpm');
        })->first();

        // Dapatkan faculty dan department
        $ftik = Faculty::where('code', 'FTIK')->first();
        $ti = Department::where('code', 'TI')->first();

        // Membuat contoh akreditasi program studi
        $accreditation = Accreditation::create([
            'title' => 'Akreditasi Program Studi Teknik Informatika 2023',
            'description' => 'Akreditasi Program Studi Teknik Informatika periode 2023-2028',
            'type' => 'department',
            'institution_name' => 'BAN-PT',
            'status' => 'in_progress',
            'grade' => null,
            'submission_date' => now()->addMonths(2),
            'visit_date' => now()->addMonths(3),
            'result_date' => null,
            'expiry_date' => now()->addYears(5),
            'faculty_id' => $ftik?->id,
            'department_id' => $ti?->id,
            'coordinator_id' => $coordinator?->id,
            'created_by' => $superAdmin?->id,
            'updated_by' => $superAdmin?->id,
        ]);

        // Membuat standar akreditasi
        $standards = [
            [
                'code' => 'STD-01',
                'name' => 'Visi, Misi, Tujuan dan Strategi',
                'description' => 'Penilaian terhadap kesesuaian visi, misi, tujuan dan strategi program studi',
                'weight' => 3.0,
                'score' => null,
                'target_score' => 3.5,
            ],
            [
                'code' => 'STD-02',
                'name' => 'Tata Pamong, Tata Kelola dan Kerjasama',
                'description' => 'Penilaian terhadap sistem tata pamong, tata kelola dan kerjasama program studi',
                'weight' => 4.0,
                'score' => null,
                'target_score' => 3.5,
            ],
            [
                'code' => 'STD-03',
                'name' => 'Mahasiswa',
                'description' => 'Penilaian terhadap kualitas input, proses dan output mahasiswa program studi',
                'weight' => 4.0,
                'score' => null,
                'target_score' => 3.5,
            ],
            [
                'code' => 'STD-04',
                'name' => 'Sumber Daya Manusia',
                'description' => 'Penilaian terhadap kualitas dan kuantitas sumber daya manusia program studi',
                'weight' => 4.0,
                'score' => null,
                'target_score' => 4.0,
            ],
            [
                'code' => 'STD-05',
                'name' => 'Keuangan, Sarana dan Prasarana',
                'description' => 'Penilaian terhadap keuangan, sarana dan prasarana program studi',
                'weight' => 3.0,
                'score' => null,
                'target_score' => 3.0,
            ],
            [
                'code' => 'STD-06',
                'name' => 'Pendidikan',
                'description' => 'Penilaian terhadap proses pendidikan program studi',
                'weight' => 5.0,
                'score' => null,
                'target_score' => 4.0,
            ],
            [
                'code' => 'STD-07',
                'name' => 'Penelitian',
                'description' => 'Penilaian terhadap proses dan hasil penelitian program studi',
                'weight' => 4.0,
                'score' => null,
                'target_score' => 3.5,
            ],
            [
                'code' => 'STD-08',
                'name' => 'Pengabdian kepada Masyarakat',
                'description' => 'Penilaian terhadap proses dan hasil pengabdian kepada masyarakat program studi',
                'weight' => 3.0,
                'score' => null,
                'target_score' => 3.0,
            ],
            [
                'code' => 'STD-09',
                'name' => 'Luaran dan Capaian Tridharma',
                'description' => 'Penilaian terhadap luaran dan capaian tridharma program studi',
                'weight' => 5.0,
                'score' => null,
                'target_score' => 4.0,
            ],
        ];

        foreach ($standards as $standard) {
            AccreditationStandard::create([
                'accreditation_id' => $accreditation->id,
                'code' => $standard['code'],
                'name' => $standard['name'],
                'description' => $standard['description'],
                'weight' => $standard['weight'],
                'score' => $standard['score'],
                'target_score' => $standard['target_score'],
            ]);
        }

        // Ambil dokumen untuk dilampirkan ke akreditasi
        $documents = Document::take(3)->get();
        $standardIds = AccreditationStandard::where('accreditation_id', $accreditation->id)
            ->pluck('id')
            ->toArray();

        if ($documents->count() > 0 && count($standardIds) > 0) {
            foreach ($documents as $index => $document) {
                AccreditationDocument::create([
                    'accreditation_id' => $accreditation->id,
                    'accreditation_standard_id' => $standardIds[$index % count($standardIds)],
                    'document_id' => $document->id,
                    'status' => 'submitted',
                    'notes' => null,
                    'reviewer_id' => null,
                    'reviewed_at' => null,
                ]);
            }
        }

        // Membuat evaluasi akreditasi
        AccreditationEvaluation::create([
            'accreditation_id' => $accreditation->id,
            'name' => 'Evaluasi Internal Program Studi',
            'description' => 'Evaluasi internal sebagai persiapan akreditasi',
            'overall_score' => null,
            'strengths' => 'Kualitas lulusan yang baik, kolaborasi dengan industri yang kuat',
            'weaknesses' => 'Publikasi internasional belum optimal, fasilitas laboratorium perlu ditingkatkan',
            'recommendations' => 'Perlu meningkatkan jumlah publikasi internasional dosen dan mahasiswa',
            'created_by' => $coordinator?->id,
            'updated_by' => null,
        ]);
    }
}
