<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTwitterToNewspaperAndGroup extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('newspaper', function(Blueprint $table)
		{
			$table->string('twitter',100);
		});

		Schema::table('group', function(Blueprint $table)
		{
			$table->string('twitter',100);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('newspaper', function(Blueprint $table)
		{
			$table->dropColumn('twitter');
		});
		Schema::table('group', function(Blueprint $table)
		{
			$table->dropColumn('twitter');
		});
	}

}
