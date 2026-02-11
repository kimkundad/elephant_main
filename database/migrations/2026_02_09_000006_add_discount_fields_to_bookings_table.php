<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('agent_id')->nullable()->after('created_by')->constrained('agents')->nullOnDelete();
            $table->foreignId('discount_code_id')->nullable()->after('agent_id')->constrained('discount_codes')->nullOnDelete();
            $table->string('discount_code')->nullable()->after('discount_code_id');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_code');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropForeign(['discount_code_id']);
            $table->dropColumn(['agent_id', 'discount_code_id', 'discount_code', 'discount_amount']);
        });
    }
};
