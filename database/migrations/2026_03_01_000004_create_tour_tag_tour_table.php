<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_tag_tour', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->foreignId('tour_tag_id')->constrained('tour_tags')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['tour_id', 'tour_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_tag_tour');
    }
};

