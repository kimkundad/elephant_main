<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
        $table->decimal('subtotal', 10, 2)->default(0)->after('total_price');
        $table->decimal('vat_amount', 10, 2)->default(0)->after('subtotal');
        $table->decimal('fee_amount', 10, 2)->default(0)->after('vat_amount');
        $table->decimal('grand_total', 10, 2)->default(0)->after('fee_amount');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn(['subtotal','vat_amount','fee_amount','grand_total']);
    });
    }
};
