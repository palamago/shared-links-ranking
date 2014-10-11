<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stats', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('id_link')->index('id_link');
			$table->timestamps();
			$table->integer('facebook')->default(0);
			$table->integer('twitter')->default(0);
			$table->integer('linkedin')->default(0);
			$table->integer('googleplus')->default(0);
			$table->integer('total')->index('total');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stats');
	}

}
