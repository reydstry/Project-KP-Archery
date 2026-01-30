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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id')
                ->constrained('members')
                ->onDelete('cascade');

            $table->foreignId('training_session_id')
                ->constrained('training_sessions')
                ->onDelete('cascade');

            $table->enum('status', ['hadir', 'tidak hadir'])->default('tidak hadir');

            $table->foreignId('validated_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps('validates_at');

            $table->timestamps();

            $table->unique(['member_id', 'training_session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
