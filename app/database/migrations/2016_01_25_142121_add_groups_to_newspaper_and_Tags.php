<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddGroupsToNewspaperAndTags extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('newspaper', function(Blueprint $table)
		{
			$table->string('id_group', 2)->nullable();
			$table->foreign('id_group', 'newspaper_group')->references('slug')->on('group')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
			$table->dropForeign('newspaper_group');
			$table->dropColumn('id_group');
		});
	}

}
