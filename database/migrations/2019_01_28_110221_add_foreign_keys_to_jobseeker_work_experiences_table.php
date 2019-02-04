<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToJobseekerWorkExperiencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobseeker_work_experiences', function(Blueprint $table)
		{
			$table->foreign('user_id', 'jobseeker_work_experiences_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('job_title_id', 'jobseeker_work_experiences_ibfk_2')->references('id')->on('job_titles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jobseeker_work_experiences', function(Blueprint $table)
		{
			$table->dropForeign('jobseeker_work_experiences_ibfk_1');
			$table->dropForeign('jobseeker_work_experiences_ibfk_2');
		});
	}

}
