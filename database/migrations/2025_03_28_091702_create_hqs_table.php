<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('hqs', function (Blueprint $table) {
            $table->id();
            $table->string('hq_name')->unique();
            $table->enum('status', ['approve', 'reject']);
            $table->timestamps(); // Automatically creates created_at & updated_at
        });
    }

    public function down() {
        Schema::dropIfExists('hqs');
    }
};
