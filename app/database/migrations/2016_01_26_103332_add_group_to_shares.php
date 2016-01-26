<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddGroupToShares extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tw_shares', function(Blueprint $table)
		{
			$table->string('id_group', 2)->nullable();
			$table->foreign('id_group', 'tw_shares_group')->references('slug')->on('group')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tw_shares', function(Blueprint $table)
		{
			$table->dropForeign('tw_shares_group');
			$table->dropColumn('id_group');
		});
	}

}
