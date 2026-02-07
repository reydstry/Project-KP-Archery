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
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('registered_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->string('name');
            $table->string('phone')->nullable();

            $table->boolean('is_self')->default(true)
                ->comment('true = member dewasa, false = anak yang didaftarkan ortu');

            $table->boolean('is_active')->default(true);

            $table->string('status')->default('pending')
                ->comment('pending, active, inactive, banned');
            
            $table->index(['name', 'user_id', 'is_active']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
