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
        Schema::create('schedule_1_2', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('event_type')->default(0)->comment('1 for individual,2 for team');
            $table->unsignedTinyInteger('event_id');
            $table->string('tournament');
            $table->string('organizing_authority');
            $table->string('organizing_authority_abbr');
            $table->string('gold')->nullable();
            $table->string('silver')->nullable();
            $table->string('bronze')->nullable();
            $table->string('participation')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_1');
    }
};
