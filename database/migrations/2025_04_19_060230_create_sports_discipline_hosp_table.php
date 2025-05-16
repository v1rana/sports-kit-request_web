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
        Schema::create('hosp_sports_discipline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('physical_disability')->default(2)->comment('1 for yes,2 for no');
            $table->unsignedBigInteger('disability_type_id')->nullable();
            $table->foreign('disability_type_id')->references('id')->on('disability_types')->onDelete('cascade');   
            $table->string('disability_doc')->nullable();
            
            $table->string('event_type')->nullable();
            $table->foreignId('tournament_id')->constrained('schedule_1_2')->onDelete('cascade');

            $table->foreignId('game_id')->constrained('games')->nullable();
            $table->string('organizing_committee')->nullable();
            $table->string('tournament_level')->nullable();
            $table->tinyInteger('represented_india')->default(0)->comment('1 for yes, for no');
            $table->date('achievement_date')->nullable();
            $table->string('tournament_venue')->nullable();
            $table->string('medal_won')->nullable();
            $table->string('match_played_by_team')->nullable();
            $table->string('match_played_by_me')->nullable();
           
            $table->string('osp_achivement_certificate_path')->nullable();
            $table->string('international_achievement_Verification_certificate_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sports_discipline_hosp');
    }
};
