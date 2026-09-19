<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores the country the phone number was entered for (ISO-3166 alpha-2),
 * next to the number itself which is saved in E.164 form (+66958467417).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('phone_country', 2)->nullable()->after('phone');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('customer_phone_country', 2)->nullable()->after('customer_phone');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->string('phone_country', 2)->nullable()->after('phone');
        });

        Schema::table('agents', function (Blueprint $table) {
            $table->string('phone_country', 2)->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('phone_country');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('phone_country');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('customer_phone_country');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('phone_country');
        });
    }
};
