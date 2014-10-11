<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRssTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('rss', function(Blueprint $table)
		{
			$table->foreign('id_newspaper', 'fk_rss_1')->references('id')->on('newspaper')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('id_tag', 'rss_ibfk_1')->references('id')->on('tags')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('rss', function(Blueprint $table)
		{
			$table->dropForeign('id_newspaper');
			$table->dropForeign('id_tag');
		});
	}

}
