<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFavouritesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('favourites', function(Blueprint $table)
		{
			$table->foreign('recruiter_id', 'favourites_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('seeker_id', 'favourites_ibfk_2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('favourites', function(Blueprint $table)
		{
			$table->dropForeign('favourites_ibfk_1');
			$table->dropForeign('favourites_ibfk_2');
		});
	}

}
