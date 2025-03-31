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
        Schema::create('sports_gradation_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_no')->nullable();
            $table->string('sports_person_name')->nullable();
            $table->string('aadhaar_no')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('mobile_no')->unique()->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('district_sportsperson_belongs')->nullable();
            $table->string('domicile_state')->nullable();
            $table->string('plays_for_statte_org')->nullable();
            $table->string('name_sports_discipline')->nullable();          
            // Best Sports Achievement
            $table->string('tournament_name')->nullable();
            $table->string('month_year')->nullable();
            $table->string('venue_of_tournament')->nullable();
            $table->string('organising_authority')->nullable();
            $table->string('tournament_type')->nullable();
            $table->string('medal_won')->nullable();
            $table->string('participation_level')->nullable();            
            $table->string('granted_grade')->nullable();
            $table->date('date')->nullable();
            $table->string('signature_of_sports_person')->nullable();
            $table->string('district_sports_officer')->nullable();
            $table->string('director_sports')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sports_gradation_certificates');
    }
};
