<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_session_coach', function (Blueprint $table) {
            $table->id();

            $table->foreignId('training_session_id')
                ->constrained('training_sessions')
                ->cascadeOnDelete();

            $table->foreignId('coach_id')
                ->constrained('coaches')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['training_session_id', 'coach_id']);
        });

        // Backfill existing sessions: primary coach becomes an assigned coach too.
        // Avoid duplicates on reruns.
        DB::table('training_sessions')
            ->select(['id', 'coach_id', 'created_at', 'updated_at'])
            ->orderBy('id')
            ->chunkById(500, function ($rows) {
                $inserts = [];
                foreach ($rows as $row) {
                    if (!$row->coach_id) {
                        continue;
                    }

                    $inserts[] = [
                        'training_session_id' => $row->id,
                        'coach_id' => $row->coach_id,
                        'created_at' => $row->created_at ?? now(),
                        'updated_at' => $row->updated_at ?? now(),
                    ];
                }

                if (!empty($inserts)) {
                    DB::table('training_session_coach')->insertOrIgnore($inserts);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_session_coach');
    }
};
