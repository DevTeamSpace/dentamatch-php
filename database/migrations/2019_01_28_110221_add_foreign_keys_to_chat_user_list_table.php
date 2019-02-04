<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToChatUserListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('chat_user_list', function(Blueprint $table)
		{
			$table->foreign('recruiter_id', 'chat_user_list_recuriter_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('seeker_id', 'chat_user_list_seeker_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('chat_user_list', function(Blueprint $table)
		{
			$table->dropForeign('chat_user_list_recuriter_id');
			$table->dropForeign('chat_user_list_seeker_id');
		});
	}

}
