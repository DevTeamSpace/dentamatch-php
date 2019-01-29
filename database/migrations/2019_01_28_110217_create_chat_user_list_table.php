<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChatUserListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chat_user_list', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('recruiter_id')->unsigned()->index('recruiter_id');
			$table->integer('seeker_id')->unsigned()->index('seeker_id');
			$table->boolean('recruiter_block')->default(0);
			$table->boolean('seeker_block')->default(0);
			$table->boolean('chat_active')->default(1);
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('chat_user_list');
	}

}
