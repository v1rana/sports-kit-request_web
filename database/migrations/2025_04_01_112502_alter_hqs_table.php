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
        Schema::table('hqs', function (Blueprint $table) {
            if (!Schema::hasColumn('hqs', 'otp')) {
            $table->string('otp')->nullable();
            $table->string('expires_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hqs', function (Blueprint $table) {
            $table->dropColumn('otp');
            $table->dropColumn('expires_at');
        });
    }
};
