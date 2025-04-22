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
        Schema::create('audit_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            $table->foreignId('standard_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['observation', 'minor', 'major', 'opportunity']);
            $table->text('finding');
            $table->text('evidence')->nullable();
            $table->text('recommendation')->nullable();
            $table->text('response')->nullable();
            $table->text('action_plan')->nullable();
            $table->date('response_date')->nullable();
            $table->date('target_completion_date')->nullable();
            $table->enum('status', ['open', 'responded', 'in_progress', 'verified', 'closed'])->default('open');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('responded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_findings');
    }
};
