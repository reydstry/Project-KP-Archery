<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create pivot table for slot-coach assignment
        Schema::create('training_session_slot_coach', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_slot_id')->constrained('training_session_slots')->onDelete('cascade');
            $table->foreignId('coach_id')->constrained('coaches')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['training_session_slot_id', 'coach_id'], 'slot_coach_unique');
        });

        // Migrate existing data from training_session_coach to training_session_slot_coach
        // For each training_session, copy coaches to all its slots
        DB::statement('
            INSERT INTO training_session_slot_coach (training_session_slot_id, coach_id, created_at, updated_at)
            SELECT 
                tss.id as training_session_slot_id,
                tsc.coach_id,
                NOW() as created_at,
                NOW() as updated_at
            FROM training_session_coach tsc
            JOIN training_session_slots tss ON tss.training_session_id = tsc.training_session_id
        ');

        // Drop the old training_session_coach pivot table
        Schema::dropIfExists('training_session_coach');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate training_session_coach pivot table
        Schema::create('training_session_coach', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_session_id')->constrained('training_sessions')->onDelete('cascade');
            $table->foreignId('coach_id')->constrained('coaches')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['training_session_id', 'coach_id']);
        });

        // Migrate data back (one coach per session - take first coach from each slot)
        DB::statement('
            INSERT INTO training_session_coach (training_session_id, coach_id, created_at, updated_at)
            SELECT DISTINCT
                tss.training_session_id,
                tssc.coach_id,
                NOW() as created_at,
                NOW() as updated_at
            FROM training_session_slot_coach tssc
            JOIN training_session_slots tss ON tss.id = tssc.training_session_slot_id
            GROUP BY tss.training_session_id, tssc.coach_id
        ');

        // Drop the per-slot pivot table
        Schema::dropIfExists('training_session_slot_coach');
    }
};
