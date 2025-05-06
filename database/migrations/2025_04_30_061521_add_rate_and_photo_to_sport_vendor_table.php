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
		Schema::table('sport_vendor', function (Blueprint $table) {
			$table->decimal('rate', 10, 2)->nullable()->after('sport_id');
			$table->string('photo')->nullable()->after('rate');
		});
	}

    /**
     * Reverse the migrations.
     */
   public function down()
	{
		Schema::table('sport_vendor', function (Blueprint $table) {
			$table->dropColumn(['rate', 'photo']);
		});
	}
};
