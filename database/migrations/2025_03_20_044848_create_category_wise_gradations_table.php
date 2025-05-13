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
        Schema::create('category_wise_gradations', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('tournament');
            $table->string('organising_authority');
            $table->string('medal')->nullable();
            $table->string('gradation')->nullable();
            $table->string('participation')->nullable();
            $table->string('gradation_participation')->nullable(); // Renamed to avoid duplicate column names
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_wise_gradation');
    }
};
