<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRssTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rss', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('url', 100)->unique('url_UNIQUE');
			$table->integer('id_newspaper')->index('fk_rss_1_idx');
			$table->timestamps();
			$table->integer('id_tag')->nullable()->index('id_tag');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('rss');
	}

}
