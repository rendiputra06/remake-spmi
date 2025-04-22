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
        Schema::table('survey_answers', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('answer_text');
            $table->dropColumn('answer_number');
            $table->dropColumn('answer_options');

            // Tambah kolom baru
            $table->text('answer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_answers', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn('answer');

            // Kembalikan kolom lama
            $table->text('answer_text')->nullable();
            $table->integer('answer_number')->nullable();
            $table->json('answer_options')->nullable();
        });
    }
};
