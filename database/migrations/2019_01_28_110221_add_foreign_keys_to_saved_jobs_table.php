<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSavedJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('saved_jobs', function(Blueprint $table)
		{
			$table->foreign('recruiter_job_id', 'saved_jobs_ibfk_1')->references('id')->on('recruiter_jobs')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('temp_job_id', 'saved_jobs_ibfk_2')->references('id')->on('temp_job_dates')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('seeker_id', 'saved_jobs_ibfk_3')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('saved_jobs', function(Blueprint $table)
		{
			$table->dropForeign('saved_jobs_ibfk_1');
			$table->dropForeign('saved_jobs_ibfk_2');
			$table->dropForeign('saved_jobs_ibfk_3');
		});
	}

}
