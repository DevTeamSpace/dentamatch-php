<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('app_messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedTinyInteger('message_to')->comment('1=>All,2=>Recruiter,3=>Job Seeker');
			$table->text('message', 65535);
			$table->boolean('message_sent')->nullable()->default(0);
			$table->boolean('cron_message_sent')->nullable()->default(0);
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('app_messages');
	}

}
