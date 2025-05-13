<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('district');
            $table->string('block');
            $table->string('gov_type'); // Gram Panchayat or Municipal Body
            $table->string('gp_mb_list');
            $table->string('name');
            $table->string('designation');
            $table->string('declaration_file'); // Store file path
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('registrations');
    }
};
