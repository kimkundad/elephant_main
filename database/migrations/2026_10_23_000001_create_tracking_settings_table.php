<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_settings', function (Blueprint $table) {
            $table->id();
            $table->string('ga4_measurement_id')->nullable();
            $table->string('gtm_container_id')->nullable();
            $table->string('meta_pixel_id')->nullable();
            $table->string('tiktok_pixel_id')->nullable();
            $table->string('google_ads_conversion_id')->nullable();
            $table->string('google_ads_purchase_label')->nullable();
            $table->string('facebook_domain_verification')->nullable();
            $table->boolean('track_purchase')->default(true);
            $table->text('head_html')->nullable();
            $table->text('body_html')->nullable();
            $table->timestamps();
        });

        // The measurement id used to be hard coded in the two layouts; carry it
        // over so analytics keeps running the moment this ships.
        DB::table('tracking_settings')->insert([
            'ga4_measurement_id' => 'G-94EZMK5EPY',
            'track_purchase' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_settings');
    }
};
