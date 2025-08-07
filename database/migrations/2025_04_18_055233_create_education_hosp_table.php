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
        Schema::create('hosp_education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // $table->foreignId('application_id')->constrained('user_details')->onDelete('cascade');
            $table->string('application_id'); 
            $table->foreign('application_id')->references('application_id')->on('user_details')->onDelete('cascade');
            $table->string('qualification');
            $table->string('other_qualification')->nullable();
            $table->string('certificate_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_hosp');
    }
};
