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
        Schema::create('hosp_user_details', function (Blueprint $table) {
            $table->id();
            $table->string('ppp_id');
            $table->string('mobile_no');
            $table->string('full_name_english');
            $table->string('full_name_hindi')->nullable();
            $table->string('father_name_english');
            $table->string('father_name_hindi')->nullable();
            $table->string('mother_name_english');
            $table->string('mother_name_hindi')->nullable();
            $table->date('date_of_birth');
            $table->integer('age')->nullable();
            $table->string('gender');
            $table->string('marital_status');
            $table->text('address');
            $table->string('district');
            $table->string('block_town')->nullable();
            $table->string('ward_village');
            $table->string('pincode');
            $table->string('email_id')->nullable();
            $table->string('benchmark_disability');
            $table->string('caste_category');
            $table->string('highest_qualification');
            $table->string('current_engagement');
            $table->bigInteger('total_annual_family_income');
            $table->boolean('income_verified');
            $table->string('alternate_number')->nullable();
            $table->string('alternate_email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
