<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tours priced adults from min_price and children at half of it, while the
 * admin form labelled min_price "Child Price" and max_price "Adult Price".
 * The two prices get their own columns, filled from what the form meant, and
 * the old min/max columns go away.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->decimal('price_adult', 10, 2)->default(0)->after('description');
            $table->decimal('price_child', 10, 2)->default(0)->after('price_adult');
        });

        DB::table('tours')->update([
            'price_adult' => DB::raw('max_price'),
            'price_child' => DB::raw('min_price'),
        ]);

        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['min_price', 'max_price']);
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->decimal('min_price', 10, 2)->default(0)->after('description');
            $table->decimal('max_price', 10, 2)->default(0)->after('min_price');
        });

        DB::table('tours')->update([
            'min_price' => DB::raw('price_child'),
            'max_price' => DB::raw('price_adult'),
        ]);

        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn(['price_adult', 'price_child']);
        });
    }
};
