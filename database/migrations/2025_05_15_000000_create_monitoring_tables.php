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
        Schema::create('performance_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('target', 10, 2)->nullable();
            $table->enum('type', ['quality', 'process', 'output', 'outcome', 'impact'])->default('quality');
            $table->enum('category', ['academic', 'research', 'community_service', 'governance', 'infrastructure', 'finance', 'human_resource', 'other'])->default('academic');
            $table->enum('level', ['institution', 'faculty', 'department', 'program'])->default('institution');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('performance_indicators')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('performance_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained('performance_indicators')->onDelete('cascade');
            $table->year('year');
            $table->integer('semester')->nullable();
            $table->date('measurement_date');
            $table->decimal('value', 10, 2);
            $table->decimal('achievement_percentage', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('findings')->nullable();
            $table->text('root_causes')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['monitoring', 'analytics', 'strategic', 'operational', 'other'])->default('monitoring');
            $table->enum('level', ['institution', 'faculty', 'department', 'program'])->default('institution');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->json('configuration')->nullable();
            $table->boolean('is_public')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('dashboard_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dashboard_id')->constrained()->onDelete('cascade');
            $table->foreignId('indicator_id')->constrained('performance_indicators')->onDelete('cascade');
            $table->enum('chart_type', ['line', 'bar', 'pie', 'radar', 'table', 'gauge', 'card', 'other'])->default('line');
            $table->json('chart_config')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('performance_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained('performance_indicators')->onDelete('cascade');
            $table->year('year');
            $table->decimal('target', 10, 2);
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('monitoring_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('type', ['report', 'evidence', 'plan', 'evaluation', 'other'])->default('report');
            $table->year('year');
            $table->integer('semester')->nullable();
            $table->enum('level', ['institution', 'faculty', 'department', 'program'])->default('institution');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('monitoring_document_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('monitoring_documents')->onDelete('cascade');
            $table->foreignId('indicator_id')->constrained('performance_indicators')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_document_indicators');
        Schema::dropIfExists('monitoring_documents');
        Schema::dropIfExists('performance_targets');
        Schema::dropIfExists('dashboard_indicators');
        Schema::dropIfExists('dashboards');
        Schema::dropIfExists('performance_values');
        Schema::dropIfExists('performance_indicators');
    }
};
