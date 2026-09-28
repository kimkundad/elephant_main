<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * How long before a session starts the guests are picked up, in hours
     * (1 = one hour, 2.5 = two and a half). Null means the tour has not set
     * one yet, so no pickup time is shown.
     */
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->decimal('pickup_lead_hours', 4, 2)->nullable()->after('price_child');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('pickup_lead_hours');
        });
    }
};
