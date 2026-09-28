<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Where the camp is on Google Maps. Admins paste whatever Google hands
     * them: a share link, a place URL, an embed URL or the whole <iframe>
     * snippet, so the column is free text and the model sorts it out.
     */
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->text('map_embed_url')->nullable()->after('pickup_lead_hours');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('map_embed_url');
        });
    }
};
