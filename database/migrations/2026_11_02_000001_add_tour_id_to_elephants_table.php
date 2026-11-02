<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nullable: elephants that predate this have no program until an admin
        // picks one. The admin form is what makes it required.
        Schema::table('elephants', function (Blueprint $table) {
            $table->foreignId('tour_id')->nullable()->after('slug')
                ->constrained('tours')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('elephants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tour_id');
        });
    }
};
