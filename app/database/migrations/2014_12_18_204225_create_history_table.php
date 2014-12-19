<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('history', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('url', 300)->index('index_history_url');
			$table->string('final_url',300);
			$table->string('title', 200);
			$table->timestamps();
			$table->dateTime('date')->index('index_history_date');
			$table->string('image',300)->default(null);

			$table->integer('facebook')->default(0);
			$table->integer('twitter')->default(0);
			$table->integer('linkedin')->default(0);
			$table->integer('googleplus')->default(0);
			$table->integer('total')->default(0);
			
			$table->integer('total_day')->default(0)->index('index_history_total_day');

			$table->integer('id_newspaper')->index('index_history_newspaper');
			$table->integer('id_tag')->index('index_history_tag');

		});

		Schema::table('history', function(Blueprint $table)
		{
			$table->foreign('id_newspaper', 'fk_history_2')->references('id')->on('newspaper')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('id_tag', 'fk_history_3')->references('id')->on('tags')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('history');
	}

}
