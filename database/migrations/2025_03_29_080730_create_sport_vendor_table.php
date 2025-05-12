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
    Schema::create('sport_vendor', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
        $table->foreignId('sport_id')->constrained('sports')->onDelete('cascade');
        $table->decimal('rate',8,2);
        $table->string('equipment')->nullable();
        $table->string('photo')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sport_vendor');
    }
};
