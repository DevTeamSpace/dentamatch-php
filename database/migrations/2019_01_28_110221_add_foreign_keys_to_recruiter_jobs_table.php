<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRecruiterJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recruiter_jobs', function(Blueprint $table)
		{
			$table->foreign('job_template_id', 'recruiter_jobs_ibfk_1')->references('id')->on('job_templates')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('recruiter_office_id', 'recruiter_jobs_ibfk_2')->references('id')->on('recruiter_offices')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recruiter_jobs', function(Blueprint $table)
		{
			$table->dropForeign('recruiter_jobs_ibfk_1');
			$table->dropForeign('recruiter_jobs_ibfk_2');
		});
	}

}
