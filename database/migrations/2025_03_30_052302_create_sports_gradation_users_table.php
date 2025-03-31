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
        Schema::create('sports_gradation_users', function (Blueprint $table) {
            $table->id();
            $table->string('sports_person_name')->nullable();
            $table->date('dob')->nullable(); // Changed to date
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable(); // Changed to enum
            $table->string('email')->unique()->nullable();
            $table->string('mobile_no')->unique()->nullable(); 
            $table->string('otp')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->enum('status', ['1', '0'])->nullable(); // Optional improvement
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sports_gradation_users');
    }
};
