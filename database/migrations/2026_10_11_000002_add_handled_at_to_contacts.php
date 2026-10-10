<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Enquiries pile up in one list, so staff need to see which ones have
     * been answered without keeping that in their heads.
     */
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->timestamp('handled_at')->nullable()->after('submitted_at');
            $table->string('handled_by')->nullable()->after('handled_at');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['handled_at', 'handled_by']);
        });
    }
};
