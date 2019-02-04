<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_groups', function(Blueprint $table)
		{
			$table->foreign('group_id', 'user_groups_ibfk_1')->references('id')->on('groups')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('user_id', 'user_groups_ibfk_2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_groups', function(Blueprint $table)
		{
			$table->dropForeign('user_groups_ibfk_1');
			$table->dropForeign('user_groups_ibfk_2');
		});
	}

}
