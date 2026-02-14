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
        Schema::table('training_sessions', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['coach_id']);
            
            // Drop the unique constraint on (coach_id, date)
            $table->dropUnique(['coach_id', 'date']);
            
            // Drop the coach_id column
            $table->dropColumn('coach_id');
            
            // Add created_by column for audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            // Remove created_by
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
            
            // Restore coach_id
            $table->foreignId('coach_id')->constrained('coaches')->onDelete('cascade');
            $table->unique(['coach_id', 'date']);
        });
    }
};
