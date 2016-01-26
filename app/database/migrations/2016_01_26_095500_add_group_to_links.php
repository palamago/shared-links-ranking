<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddGroupToLinks extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('link', function(Blueprint $table)
		{
			$table->string('id_group', 2)->nullable();
			$table->foreign('id_group', 'link_group')->references('slug')->on('group')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
		Schema::table('history', function(Blueprint $table)
		{
			$table->string('id_group', 2)->nullable();
			$table->foreign('id_group', 'history_group')->references('slug')->on('group')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('link', function(Blueprint $table)
		{
			$table->dropForeign('link_group');
			$table->dropColumn('id_group');
		});

		Schema::table('history', function(Blueprint $table)
		{
			$table->dropForeign('history_group');
			$table->dropColumn('id_group');
		});

	}

}
