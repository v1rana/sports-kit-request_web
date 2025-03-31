<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('municipal_body_member', function (Blueprint $table) {
            $table->id();  // Primary Key (Auto Increment)
            $table->string('district', 50)->nullable();
            $table->string('block', 50)->nullable();
            $table->string('municipal_area', 50)->nullable();
            $table->string('wardmember', 100)->nullable();
            $table->string('mob', 15)->unique(); // Mobile number should be unique
            $table->string('otp', 6)->nullable(); // OTP column (6-digit)
            $table->timestamp('otp_expires_at')->nullable(); // OTP expiration timestamp
            $table->timestamps(); // Adds created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('municipal_body_member');
    }
};
