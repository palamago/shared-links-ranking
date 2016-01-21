<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTwitterKeys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('group', function(Blueprint $table)
		{
		    $table->string('tw_user_key',100);
		    $table->string('tw_user_secret',100);
		    $table->string('tw_user_token',100);
		    $table->string('tw_user_token_secret',100);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('group', function(Blueprint $table)
		{
		    $table->dropColumn('tw_user_key');
		    $table->dropColumn('tw_user_secret');
		    $table->dropColumn('tw_user_token');
		    $table->dropColumn('tw_user_token_secret');
		});
	}

}
