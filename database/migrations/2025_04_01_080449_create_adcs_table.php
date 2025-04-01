<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('adcs', function (Blueprint $table) { // Ensure correct table name
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->string('district');
            $table->string('mob');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('otp');
            $table->string('expires_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('adcs');
    }
};
