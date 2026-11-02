<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Where the elephant lives, for the team only: no public page shows it.
        Schema::table('elephants', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable()->after('tour_id')
                ->constrained('provinces')->nullOnDelete();
            $table->string('camp_name')->nullable()->after('province_id');
            $table->text('camp_info')->nullable()->after('camp_name');
        });
    }

    public function down(): void
    {
        Schema::table('elephants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('province_id');
            $table->dropColumn(['camp_name', 'camp_info']);
        });
    }
};
