<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNotificationLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('notification_logs', function(Blueprint $table)
		{
			$table->foreign('sender_id', 'notification_logs_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('receiver_id', 'notification_logs_ibfk_2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notification_logs', function(Blueprint $table)
		{
			$table->dropForeign('notification_logs_ibfk_1');
			$table->dropForeign('notification_logs_ibfk_2');
		});
	}

}
