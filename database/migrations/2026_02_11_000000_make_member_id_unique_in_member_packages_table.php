<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cleanup duplicates first (DML). Avoid wrapping Schema changes (DDL) in a transaction
        // because some DB engines/driver configurations implicitly commit on DDL.
        DB::beginTransaction();
        try {
            // If there are duplicate member_packages for a member, keep the newest one
            // and re-point session_bookings to it before deleting the older duplicates.
            $duplicateMembers = DB::table('member_packages')
                ->select('member_id', DB::raw('COUNT(*) as cnt'))
                ->groupBy('member_id')
                ->having('cnt', '>', 1)
                ->get();

            foreach ($duplicateMembers as $row) {
                $ids = DB::table('member_packages')
                    ->where('member_id', $row->member_id)
                    ->orderByDesc('id')
                    ->pluck('id')
                    ->all();

                $keepId = array_shift($ids);

                foreach ($ids as $oldId) {
                    // Prevent unique conflicts on session_bookings(member_package_id, training_session_slot_id)
                    $conflictingSlotIds = DB::table('session_bookings')
                        ->where('member_package_id', $keepId)
                        ->pluck('training_session_slot_id')
                        ->all();

                    if (!empty($conflictingSlotIds)) {
                        DB::table('session_bookings')
                            ->where('member_package_id', $oldId)
                            ->whereIn('training_session_slot_id', $conflictingSlotIds)
                            ->delete();
                    }

                    DB::table('session_bookings')
                        ->where('member_package_id', $oldId)
                        ->update(['member_package_id' => $keepId]);

                    DB::table('member_packages')
                        ->where('id', $oldId)
                        ->delete();
                }
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        Schema::table('member_packages', function (Blueprint $table) {
            // One member can only have one row in member_packages
            $table->unique('member_id');
        });
    }

    public function down(): void
    {
        Schema::table('member_packages', function (Blueprint $table) {
            $table->dropUnique(['member_id']);
        });
    }
};
