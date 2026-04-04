<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->boolean('self_drive')->default(false)->after('pickup_location_id');
            $table->string('pickup_source', 50)->nullable()->after('self_drive');
            $table->string('pickup_place_name')->nullable()->after('pickup_source');
            $table->string('pickup_place_address')->nullable()->after('pickup_place_name');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'self_drive',
                'pickup_source',
                'pickup_place_name',
                'pickup_place_address',
            ]);
        });
    }
};
