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
        Schema::create('swimming_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->string('image', 150)->nullable();
            $table->string('skill_1', 50);
            $table->string('skill_2', 50);
            $table->string('skill_3', 50);
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swimming_levels');
    }
};
