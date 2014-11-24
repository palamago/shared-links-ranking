<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DiffField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stats', function($table)
		{
		    $table->integer('dif_total')->default(0)->index('dif_total');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('stats', function($table)
		{
		    $table->dropColumn('dif_total');
		});
	}

}
