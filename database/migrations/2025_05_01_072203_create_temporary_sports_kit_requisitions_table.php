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
       Schema::create('temporary_sports_kit_requisitions', function (Blueprint $table) {
		$table->id();
		$table->string('applicant_id');
		$table->string('user_id');
		$table->string('name');
		$table->string('district');
		$table->string('block');
		$table->string('area_name');
		$table->string('designation');
		$table->string('specific_designation');
		$table->json('sports_equipment');
		$table->timestamps(); // includes created_at
	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_sports_kit_requisitions');
    }
};
