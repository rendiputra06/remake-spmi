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
            $table->string('type'); // institution, faculty, department, program
            $table->string('institution_name')->nullable();
            $table->string('grade')->nullable();
            $table->string('status')->default('draft'); // draft, in_progress, submitted, completed
            $table->date('submission_date')->nullable();
            $table->date('visit_date')->nullable();
            $table->date('result_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
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
            $table->foreignId('parent_id')->nullable()->constrained('accreditation_standards')->onDelete('cascade');
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->decimal('weight', 5, 2)->default(1.00);
            $table->decimal('max_score', 5, 2)->default(100.00);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('accreditation_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accreditation_id')->constrained()->onDelete('cascade');
            $table->foreignId('standard_id')->nullable()->constrained('accreditation_standards')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('document_number')->nullable();
            $table->string('document_type');
            $table->string('file_path')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('status')->default('draft'); // draft, review, approved, rejected, expired, archived
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('accreditation_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accreditation_id')->constrained()->onDelete('cascade');
            $table->foreignId('standard_id')->nullable()->constrained('accreditation_standards')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('evaluation_date');
            $table->string('status')->default('draft'); // draft, in_progress, completed, reviewed
            $table->decimal('score', 5, 2)->nullable();
            $table->text('findings')->nullable();
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
