<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('tour_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('author_email')->nullable()->after('author_name');
            $table->string('ip_address', 45)->nullable()->after('is_active');
            $table->string('user_agent', 255)->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tour_id');
            $table->dropColumn(['author_email', 'ip_address', 'user_agent']);
        });
    }
};
