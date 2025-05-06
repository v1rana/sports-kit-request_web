<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('pan_of_owner')->nullable();
            $table->string('firm_address')->nullable();
            $table->string('district')->nullable();
            $table->string('pincode')->nullable();
            $table->string('vendor_assigned_document')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
