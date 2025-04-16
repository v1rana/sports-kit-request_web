<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('dsos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('district'); // DSO's assigned district
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('mob');
            $table->string('otp')->nullable();
            $table->string('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dsos');
    }
};
