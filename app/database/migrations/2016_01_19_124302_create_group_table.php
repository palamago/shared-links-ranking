<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 100);
			$table->string('slug', 100);
			$table->string('logo', 100);
			$table->string('tw_key', 100);
			$table->string('tw_secret', 100);
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
		Schema::drop('group');
	}

}
