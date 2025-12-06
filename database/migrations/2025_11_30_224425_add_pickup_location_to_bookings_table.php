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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('pickup_location_id')
                ->nullable()
                ->after('status')
                ->constrained('pickup_locations')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['pickup_location_id']);
            $table->dropColumn('pickup_location_id');
        });
    }
};
