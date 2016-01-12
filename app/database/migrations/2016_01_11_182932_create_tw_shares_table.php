<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTwSharesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tw_shares', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('link', 200);
			$table->string('counts', 45)->default('0');
			$table->string('max_id', 45)->default('0');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tw_shares');
	}

}
