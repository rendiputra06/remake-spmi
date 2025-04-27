<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accreditations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['institution', 'faculty', 'department', 'program']);
            $table->string('institution_name')->nullable(); // Nama lembaga akreditasi
            $table->string('status')->nullable(); // Status akreditasi (draft, in_progress, submitted, completed)
            $table->string('grade')->nullable(); // Nilai akreditasi (A, B, C, dll)
            $table->date('submission_date')->nullable();
            $table->date('visit_date')->nullable();
            $table->date('result_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->foreignId('faculty_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('coordinator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('accreditation_standards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accreditation_id')->constrained()->onDelete('cascade');
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('weight', 5, 2)->default(1); // Bobot standar
            $table->decimal('score', 5, 2)->nullable(); // Nilai saat ini
            $table->decimal('target_score', 5, 2)->nullable(); // Nilai target
            $table->timestamps();
        });

        Schema::create('accreditation_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accreditation_id')->constrained()->onDelete('cascade');
            $table->foreignId('accreditation_standard_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('submitted'); // submitted, reviewed, approved, rejected
            $table->text('notes')->nullable();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('accreditation_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accreditation_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('overall_score', 5, 2)->nullable();
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('recommendations')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accreditation_evaluations');
        Schema::dropIfExists('accreditation_documents');
        Schema::dropIfExists('accreditation_standards');
        Schema::dropIfExists('accreditations');
    }
};
