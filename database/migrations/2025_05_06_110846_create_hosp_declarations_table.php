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
        Schema::create('hosp_declarations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('application_id'); 
            $table->foreign('application_id')->references('application_id')->on('user_details')->onDelete('cascade');
            $table->foreignId('declaration_id')->constrained('declarations')->onDelete('cascade');
     
            // $table->json('points');
            $table->string('declaration_file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosp_declarations');
    }
};
