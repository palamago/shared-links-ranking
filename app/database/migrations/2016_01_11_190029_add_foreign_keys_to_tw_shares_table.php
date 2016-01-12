<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTwSharesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tw_shares', function(Blueprint $table)
		{
			$table->foreign('id_link', 'tw_shares_ibfk_1')->references('id')->on('link')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
			$table->dropForeign('tw_shares_ibfk_1');
		});
	}

}
