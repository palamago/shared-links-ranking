<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLinkTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('link', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('url', 300)->unique('url_UNIQUE');
			$table->string('title', 200);
			$table->timestamps();
			$table->integer('id_rss')->index('fk_link_1_idx');
			$table->dateTime('date')->index('date');
			$table->integer('facebook')->default(0);
			$table->integer('twitter')->default(0);
			$table->integer('linkedin')->default(0);
			$table->integer('googleplus')->default(0);
			$table->integer('id_newspaper');
			$table->integer('id_tag')->index('id_tag');
			$table->integer('total')->default(0)->index('total');
			$table->index(['id_newspaper','id_tag'], 'id_newspaper');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('link');
	}

}
