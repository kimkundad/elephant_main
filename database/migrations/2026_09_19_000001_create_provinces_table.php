<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name_th');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Nullable so existing rows can be backfilled below; the admin forms require it.
        Schema::table('tours', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable()->after('id')
                ->constrained('provinces')->restrictOnDelete();
        });

        Schema::table('pickup_locations', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable()->after('id')
                ->constrained('provinces')->restrictOnDelete();
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->text('pickup_note')->nullable()->after('pickup_place_address');
        });

        // Everything that exists today is in Chiang Mai.
        $chiangMaiId = DB::table('provinces')->insertGetId([
            'name_th' => 'เชียงใหม่',
            'name_en' => 'Chiang Mai',
            'slug' => 'chiang-mai',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tours')->whereNull('province_id')->update(['province_id' => $chiangMaiId]);
        DB::table('pickup_locations')->whereNull('province_id')->update(['province_id' => $chiangMaiId]);
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('pickup_note');
        });

        Schema::table('pickup_locations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('province_id');
        });

        Schema::table('tours', function (Blueprint $table) {
            $table->dropConstrainedForeignId('province_id');
        });

        Schema::dropIfExists('provinces');
    }
};
