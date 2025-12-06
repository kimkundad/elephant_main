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
        Schema::table('tour_session_availability', function (Blueprint $table) {

            // 1) เพิ่ม tour_id (เพื่อ lookup ให้เร็วขึ้น)
            if (!Schema::hasColumn('tour_session_availability', 'tour_id')) {
                $table->foreignId('tour_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('tours')
                    ->onDelete('cascade');
            }

            // 2) rename available → is_open
            if (Schema::hasColumn('tour_session_availability', 'available')) {
                $table->renameColumn('available', 'is_open');
            }

            // 3) rename tour_session_id → session_id
            if (Schema::hasColumn('tour_session_availability', 'tour_session_id')) {
                $table->renameColumn('tour_session_id', 'session_id');
            }

            // 4) index ป้องกันซ้ำ (เฉพาะถ้าไม่มีอยู่แล้ว)
            $exists = DB::select("
                SHOW INDEX FROM tour_session_availability
                WHERE Key_name = 'tour_session_unique'
            ");

            if (empty($exists)) {
                $table->unique(['tour_id', 'session_id', 'date'], 'tour_session_unique');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
