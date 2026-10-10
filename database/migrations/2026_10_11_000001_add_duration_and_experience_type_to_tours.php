<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Two of the four filter groups guests pick from. Location is the tour's
     * province and the activities are its tags; these two had nowhere to live.
     *
     * Nullable: a tour that has not been classified yet simply does not answer
     * those filters.
     */
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->string('duration', 32)->nullable()->after('pickup_lead_hours');
            $table->string('experience_type', 32)->nullable()->after('duration');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['duration', 'experience_type']);
        });
    }
};
