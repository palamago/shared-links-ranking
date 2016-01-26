<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddGroupToLog extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('log', function(Blueprint $table)
		{
			$table->string('id_group', 2)->nullable();
			$table->foreign('id_group', 'log_group')->references('slug')->on('group')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('log', function(Blueprint $table)
		{
			$table->dropForeign('log_group');
			$table->dropColumn('id_group');
		});
	}

}
