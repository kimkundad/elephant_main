<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            // ✅ เพิ่มข้อมูลลูกค้าแบบ guest
            $table->string('customer_name')->nullable()->after('customer_id');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->string('customer_email')->nullable()->after('customer_phone');

            // ✅ ถ้า customer_id เดิม NOT NULL ให้เป็น nullable
            // (ถ้าเจอ error เรื่อง foreign key ดูหมายเหตุด้านล่าง)
            $table->unsignedBigInteger('customer_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['customer_name','customer_phone','customer_email']);
            $table->unsignedBigInteger('customer_id')->nullable(false)->change();
        });
    }
};
