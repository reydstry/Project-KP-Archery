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
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('session_time_id')
                ->constrained('session_times')
                ->restrictOnDelete();

            $table->date('date');

            $table->foreignId('coach_id')
                ->constrained('coaches')
                ->restrictOnDelete();

            $table->integer('max_participants');
            $table->enum('status', ['open', 'closed', 'canceled'])->default('open');
 
            $table->timestamps();

            $table->unique(['session_time_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
