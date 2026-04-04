<?php

use App\Models\Review;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('source', 32)->default(Review::SOURCE_ADMIN)->after('author_email');
        });

        DB::table('reviews')
            ->whereNull('source')
            ->update(['source' => Review::SOURCE_ADMIN]);
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
