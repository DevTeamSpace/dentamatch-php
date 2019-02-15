<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSeekerCardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('seeker_cards', function(Blueprint $table)
		{
			$table->foreign('seeker_id', 'seeker_cards_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('seeker_cards', function(Blueprint $table)
		{
			$table->dropForeign('seeker_cards_ibfk_1');
		});
	}

}
