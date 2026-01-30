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
        Schema::create('member_packages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id')
                ->constrained('members')
                ->onDelete('cascade');

            $table->foreignId('package_id')
                ->constrained('packages')
                ->restrictOnDelete();

            $table->integer('total_quota');
            $table->integer('remaining_quota');

            $table->date('start_date');
            $table->date('end_date');

            $table->enum('status', ['pending', 'active', 'expired']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_packages');
    }
};
