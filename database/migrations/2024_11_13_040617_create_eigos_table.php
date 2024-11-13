<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eigos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('genre');
            $table->year('year_released');
            $table->integer('rating')->default(0);
            $table->integer('times_watched')->default(0);
            $table->boolean('recommended')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eigos');
    }
};
