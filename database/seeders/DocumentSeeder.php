<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Standard;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DocumentSeeder extends Seeder
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

        $kepalaPm = User::whereHas('roles', function ($query) {
            $query->where('name', 'kepala-lpm');
        })->first();

        // Dapatkan standar pertama
        $standardPendidikan = Standard::where('category', 'Pendidikan')->first();
        $standardPenelitian = Standard::where('category', 'Penelitian')->first();

        // Buat direktori jika belum ada
        if (!Storage::exists('documents')) {
            Storage::makeDirectory('documents');
        }

        // Seed dokumen (catatan: file tidak akan benar-benar ada, hanya data di database)
        Document::create([
            'title' => 'Pedoman Audit Mutu Internal',
            'description' => 'Dokumen panduan pelaksanaan audit mutu internal di lingkungan universitas',
            'file_path' => 'documents/pedoman_ami.pdf',
            'file_name' => 'pedoman_ami.pdf',
            'file_type' => 'application/pdf',
            'file_size' => '2.5 MB',
            'category' => 'Pedoman',
            'visibility' => 'public',
            'standard_id' => $standardPendidikan?->id,
            'uploaded_by' => $superAdmin?->id,
        ]);

        Document::create([
            'title' => 'SOP Penyusunan Dokumen Mutu',
            'description' => 'Standar operasional prosedur dalam penyusunan dokumen mutu',
            'file_path' => 'documents/sop_dokumen_mutu.pdf',
            'file_name' => 'sop_dokumen_mutu.pdf',
            'file_type' => 'application/pdf',
            'file_size' => '1.8 MB',
            'category' => 'SOP',
            'visibility' => 'public',
            'standard_id' => $standardPendidikan?->id,
            'uploaded_by' => $kepalaPm?->id,
        ]);

        Document::create([
            'title' => 'Kebijakan Sistem Penjaminan Mutu Internal',
            'description' => 'Dokumen kebijakan SPMI yang berlaku di universitas',
            'file_path' => 'documents/kebijakan_spmi.pdf',
            'file_name' => 'kebijakan_spmi.pdf',
            'file_type' => 'application/pdf',
            'file_size' => '3.2 MB',
            'category' => 'Kebijakan',
            'visibility' => 'public',
            'uploaded_by' => $superAdmin?->id,
        ]);

        Document::create([
            'title' => 'Formulir Evaluasi Pembelajaran',
            'description' => 'Formulir yang digunakan untuk evaluasi pembelajaran oleh mahasiswa',
            'file_path' => 'documents/formulir_evaluasi.docx',
            'file_name' => 'formulir_evaluasi.docx',
            'file_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'file_size' => '540 KB',
            'category' => 'Formulir',
            'visibility' => 'restricted',
            'standard_id' => $standardPendidikan?->id,
            'uploaded_by' => $kepalaPm?->id,
        ]);

        Document::create([
            'title' => 'Panduan Penelitian Dosen',
            'description' => 'Panduan penyelenggaraan penelitian untuk dosen',
            'file_path' => 'documents/panduan_penelitian.pdf',
            'file_name' => 'panduan_penelitian.pdf',
            'file_type' => 'application/pdf',
            'file_size' => '4.1 MB',
            'category' => 'Pedoman',
            'visibility' => 'public',
            'standard_id' => $standardPenelitian?->id,
            'uploaded_by' => $superAdmin?->id,
        ]);
    }
}
