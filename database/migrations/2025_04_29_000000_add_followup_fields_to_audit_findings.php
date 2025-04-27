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
        Schema::table('audit_findings', function (Blueprint $table) {
            $table->text('followup_action')->nullable()->after('status');
            $table->date('followup_date')->nullable()->after('followup_action');
            $table->unsignedBigInteger('followup_by')->nullable()->after('followup_date');
            $table->text('verification_notes')->nullable()->after('followup_by');
            $table->date('verification_date')->nullable()->after('verification_notes');

            $table->foreign('followup_by', 'audit_findings_followup_by_foreign')
                ->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_findings', function (Blueprint $table) {
            $table->dropForeign(['followup_by']);

            $table->dropColumn([
                'followup_action',
                'followup_date',
                'followup_by',
                'verification_notes',
                'verification_date',
            ]);
        });
    }
};
