<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_session_slots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('training_session_id')
                ->constrained('training_sessions')
                ->cascadeOnDelete();

            $table->foreignId('session_time_id')
                ->constrained('session_times')
                ->restrictOnDelete();

            $table->unsignedInteger('max_participants');

            $table->timestamps();

            $table->unique(['training_session_id', 'session_time_id'], 'ts_slots_session_time_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_session_slots');
    }
};
