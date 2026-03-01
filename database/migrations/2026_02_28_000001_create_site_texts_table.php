<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_texts', function (Blueprint $table) {
            $table->id();
            $table->string('page', 50)->nullable()->index();
            $table->string('section', 50)->nullable()->index();
            $table->string('key', 150)->index();
            $table->string('locale', 5)->index();
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['key', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_texts');
    }
};
