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
        Schema::create('tour_session_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_session_id')->constrained()->onDelete('cascade');
            $table->date('date');               // เช่น 2025-12-01
            $table->boolean('available')->default(1);
            $table->integer('capacity_override')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_session_availability');
    }
};
