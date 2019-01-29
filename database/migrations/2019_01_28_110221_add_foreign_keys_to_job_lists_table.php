<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToJobListsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('job_lists', function(Blueprint $table)
		{
			$table->foreign('seeker_id', 'job_lists_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('temp_job_id', 'job_lists_ibfk_2')->references('id')->on('temp_job_dates')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('recruiter_job_id', 'job_lists_ibfk_3')->references('id')->on('recruiter_jobs')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('job_lists', function(Blueprint $table)
		{
			$table->dropForeign('job_lists_ibfk_1');
			$table->dropForeign('job_lists_ibfk_2');
			$table->dropForeign('job_lists_ibfk_3');
		});
	}

}
