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
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->enum('type', ['text', 'number', 'multiple_choice', 'checkbox', 'scale', 'dropdown']);
            $table->json('options')->nullable(); // untuk pertanyaan dengan opsi (multiple choice, checkbox, dropdown)
            $table->integer('min_value')->nullable(); // untuk pertanyaan skala
            $table->integer('max_value')->nullable(); // untuk pertanyaan skala
            $table->string('min_label')->nullable(); // untuk label minimum pada skala
            $table->string('max_label')->nullable(); // untuk label maksimum pada skala
            $table->integer('order')->default(0);
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};
