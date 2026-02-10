<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_package_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_session_slot_id')->constrained('training_session_slots')->onDelete('cascade');
            $table->foreignId('booked_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Prevent duplicate bookings
            $table->unique(['member_package_id', 'training_session_slot_id'], 'sb_member_slot_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_bookings');
    }
};