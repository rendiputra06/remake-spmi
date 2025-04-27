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
        Schema::create('monitoring_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status'); // draft, active, completed
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('monitoring_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // Akademik, Penelitian, SDM, Keuangan, dll
            $table->string('unit'); // %, angka, Ya/Tidak, dll
            $table->decimal('target_value', 10, 2)->nullable();
            $table->decimal('minimum_value', 10, 2)->nullable();
            $table->foreignId('standard_id')->nullable()->constrained()->onDelete('set null');
            $table->string('formula')->nullable(); // Rumus perhitungan jika ada
            $table->string('data_source')->nullable(); // Sumber data (manual, sistem, dll)
            $table->string('frequency')->nullable(); // Frekuensi pengukuran (bulanan, semester, tahunan)
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('monitoring_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_period_id')->constrained()->onDelete('cascade');
            $table->foreignId('monitoring_indicator_id')->constrained()->onDelete('cascade');
            $table->foreignId('faculty_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('actual_value', 10, 2)->nullable();
            $table->string('status')->nullable(); // Tercapai, Belum Tercapai, Dalam Proses
            $table->text('remarks')->nullable();
            $table->text('achievements')->nullable(); // Pencapaian
            $table->text('obstacles')->nullable(); // Kendala
            $table->text('follow_up')->nullable(); // Tindak lanjut
            $table->foreignId('document_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('monitoring_dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // chart, table, card, dll
            $table->string('category')->nullable(); // Akademik, Penelitian, SDM, Keuangan, dll
            $table->json('config')->nullable(); // Konfigurasi dashboard dalam format JSON
            $table->boolean('is_public')->default(false);
            $table->json('filters')->nullable(); // Filter yang tersedia untuk dashboard
            $table->integer('display_order')->default(0);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('monitoring_dashboard_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_dashboard_id')->constrained()->onDelete('cascade');
            $table->foreignId('monitoring_indicator_id')->constrained()->onDelete('cascade');
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->unique(['monitoring_dashboard_id', 'monitoring_indicator_id'], 'dashboard_indicator_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_dashboard_indicators');
        Schema::dropIfExists('monitoring_dashboards');
        Schema::dropIfExists('monitoring_measurements');
        Schema::dropIfExists('monitoring_indicators');
        Schema::dropIfExists('monitoring_periods');
    }
};
