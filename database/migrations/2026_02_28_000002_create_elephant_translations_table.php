<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elephant_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('elephant_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5)->index();
            $table->string('name', 255);
            $table->text('history')->nullable();
            $table->timestamps();

            $table->unique(['elephant_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elephant_translations');
    }
};
