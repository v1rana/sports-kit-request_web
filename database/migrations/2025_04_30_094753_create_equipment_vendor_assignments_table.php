<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('equipment_vendor_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->string('equipment_name'); // adjust if you later use equipment_id instead
            $table->unsignedBigInteger('vendor_id');
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('sports_kit_requisitions')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_vendor_assignments');
    }
};
