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
        Schema::table('tour_sessions', function (Blueprint $table) {
            //
            $table->string('name')->nullable()->after('title');
            $table->time('start_time')->nullable()->after('session_time');
            $table->time('end_time')->nullable()->after('start_time');
            $table->integer('capacity')->nullable()->after('default_capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_sessions', function (Blueprint $table) {
            //
            $table->dropColumn([
                'name',
                'start_time',
                'end_time',
                'capacity',
            ]);
        });
    }
};
