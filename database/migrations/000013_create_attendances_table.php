<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_booking_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['present', 'absent'])->default('present');
            $table->foreignId('validated_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('validated_at');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // One attendance per booking
            $table->unique('session_booking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};