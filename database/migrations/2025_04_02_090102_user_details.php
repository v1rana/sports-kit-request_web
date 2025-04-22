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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->string('family_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // $table->string('member_id');
            // $table->string('mobile_no');
            $table->string('full_name_en');
            $table->string('full_name_hi')->nullable();
            $table->string('father_name_en');
            $table->string('father_name_hi')->nullable();
            $table->string('mother_name_en');
            $table->string('mother_name_hi')->nullable();
            $table->date('date_of_birth');
            $table->integer('age')->nullable();
            $table->string('gender');
            $table->string('marital_status');
            $table->text('address_landMark')->nullable();
            $table->string('district');
            $table->string('block_town')->nullable();
            $table->string('ward_village');
            $table->string('pincode');
            $table->string('email_id')->nullable();
            $table->string('benchmark_disability')->nullable();
            $table->string('caste_category');
            $table->string('highest_qualification');
            $table->string('current_engagement')->nullable();
            $table->bigInteger('annual_income');
            $table->boolean('income_verified');
            $table->string('alternate_number')->nullable();
            $table->string('alternate_email')->nullable();
            $table->string('application_id')->nullable()->unique(); // create after all steps submit
            $table->tinyInteger('active_step')->default(1); // create after all steps submit
            $table->string('photo')->nullable(); // from personal step
            $table->string('sign')->nullable(); // from personal step
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
