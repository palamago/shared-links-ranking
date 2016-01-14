<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTriggerTw extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::unprepared('CREATE TRIGGER links_after_insert
			AFTER INSERT
			   ON link FOR EACH ROW

			BEGIN

			   -- Insert record into audit table
			   INSERT INTO tw_shares
			   ( id_link,
				 link,
			     counts,
			     max_id,
			     created_at)
			   VALUES
			   ( NEW.id, NEW.final_url, 0, 0, NOW() );

			END;');
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::unprepared('DROP TRIGGER `links_after_insert`');
	}

}
