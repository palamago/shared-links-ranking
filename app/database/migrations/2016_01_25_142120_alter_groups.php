<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterGroups extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('group', function(Blueprint $table)
		{
			//$table->dropPrimary('PRIMARY');
			$table->dropColumn('id');
		});

		Schema::table('group', function(Blueprint $table)
		{
			DB::unprepared('alter table `group` modify slug varchar(2) NOT NULL');
			$table->primary('slug');
		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('group', function(Blueprint $table)
		{
			$table->dropPrimary('PRIMARY');
			$table->integer('id');
		});
	}

}
