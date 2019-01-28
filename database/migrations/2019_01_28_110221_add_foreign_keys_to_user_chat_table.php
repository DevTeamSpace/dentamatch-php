<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserChatTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_chat', function(Blueprint $table)
		{
			$table->foreign('from_id', 'user_chat_from_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('to_id', 'user_chat_to_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_chat', function(Blueprint $table)
		{
			$table->dropForeign('user_chat_from_id');
			$table->dropForeign('user_chat_to_id');
		});
	}

}
