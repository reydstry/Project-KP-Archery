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
        Schema::create('absents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            
            $table->foreignId('member_id')
                ->constrained('members')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('training_session_id')
                ->constrained('training_sessions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->date('date');

            $table->unique(['member_id', 'training_session_id', 'date'], 'unique_absent_per_session_per_day');
            $table->index(['member_id', 'training_session_id', 'date'], 'idx_absent_member_session_date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absents');
    }
};
