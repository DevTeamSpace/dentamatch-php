<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notification_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('sender_id')->unsigned()->nullable()->index('sender_id');
			$table->integer('receiver_id')->unsigned()->index('receiver_id');
			$table->integer('job_list_id')->unsigned()->nullable();
			$table->text('notification_data', 65535);
			$table->boolean('seen')->default(0);
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();
			$table->tinyInteger('notification_type')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notification_logs');
	}

}
