<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLinkTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('link', function(Blueprint $table)
		{
			$table->foreign('id_rss', 'fk_link_1')->references('id')->on('rss')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('id_newspaper', 'link_ibfk_1')->references('id')->on('newspaper')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('id_tag', 'link_ibfk_2')->references('id')->on('tags')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('link', function(Blueprint $table)
		{
			$table->dropForeign('id_rss');
			$table->dropForeign('id_newspaper');
			$table->dropForeign('id_tag');
		});
	}

}
