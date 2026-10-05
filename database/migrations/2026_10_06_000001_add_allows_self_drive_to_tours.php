<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Some camps cannot be reached without the tour's own transport, so the
     * booking page must not offer "I will travel by myself" for them. True
     * keeps every existing tour as it behaves today until an admin says
     * otherwise.
     */
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->boolean('allows_self_drive')->default(true)->after('pickup_lead_hours');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn('allows_self_drive');
        });
    }
};
