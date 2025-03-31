<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('hq_sports_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hq_id')->constrained('hqs')->onDelete('cascade'); 
            $table->foreignId('sports_kit_requisition_id')->constrained('sports_kit_requisitions')->onDelete('cascade');
            $table->enum('status', ['approved', 'rejected'])->default('approved');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('hq_sports_requests');
    }
};
