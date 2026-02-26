<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add composite index to member_packages for status computation queries.
 * Required because Member::getStatusAttribute() and scopeEligibleForAttendance()
 * both use (member_id, is_active, end_date) lookups at scale.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member_packages', function (Blueprint $table) {
            // Covers: WHERE member_id = ? AND is_active = 1 AND end_date >= ?
            $table->index(
                ['member_id', 'is_active', 'end_date'],
                'mp_member_active_end_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('member_packages', function (Blueprint $table) {
            $table->dropIndex('mp_member_active_end_idx');
        });
    }
};
