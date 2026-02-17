<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('session_bookings');

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('training_sessions')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['session_id', 'member_id'], 'attendance_session_member_unique');
            $table->index(['session_id', 'created_at'], 'attendance_session_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');

        Schema::create('session_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_package_id')->constrained()->onDelete('cascade');
            $table->foreignId('training_session_slot_id')->constrained('training_session_slots')->onDelete('cascade');
            $table->foreignId('booked_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['member_package_id', 'training_session_slot_id'], 'sb_member_slot_unique');
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_booking_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['present', 'absent'])->default('present');
            $table->foreignId('validated_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('validated_at');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique('session_booking_id');
        });
    }
};
