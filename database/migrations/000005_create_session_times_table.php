<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_times', function (Blueprint $table) {
            $table->id();

            $table->string('day_of_week');

            $table->time('start_time');       
            $table->time('end_time');         
            
            $table->integer('max_capacity')->default(10);
            $table->foreignId('coach_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_times');
    }
};
