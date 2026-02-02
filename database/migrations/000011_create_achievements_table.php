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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['member', 'club'])->default('member');
            $table->foreignId('member_id')
                ->nullable()
                ->constrained('members')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('photo_path')->nullable();

            $table->index(['member_id', 'date'], 'idx_achievement_member');
            $table->index(['type', 'date'], 'idx_achievement_type_date');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
