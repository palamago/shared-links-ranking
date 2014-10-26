<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForeignFix extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('link', function(Blueprint $table)
		{
			$table->dropForeign('fk_link_1');
			$table->dropForeign('link_ibfk_1');
			$table->dropForeign('link_ibfk_2');
			$table->foreign('id_rss', 'fk_link_1')->references('id')->on('rss')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('id_newspaper', 'link_ibfk_1')->references('id')->on('newspaper')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('id_tag', 'link_ibfk_2')->references('id')->on('tags')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});

		Schema::table('rss', function(Blueprint $table)
		{
			$table->dropForeign('fk_rss_1');
			$table->dropForeign('rss_ibfk_1');
			$table->foreign('id_newspaper', 'fk_rss_1')->references('id')->on('newspaper')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('id_tag', 'rss_ibfk_1')->references('id')->on('tags')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});

		Schema::table('stats', function(Blueprint $table)
		{
			$table->dropForeign('stats_ibfk_1');
			$table->foreign('id_link', 'stats_ibfk_1')->references('id')->on('link')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
