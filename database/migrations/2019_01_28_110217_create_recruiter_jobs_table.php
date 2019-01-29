<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecruiterJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recruiter_jobs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('job_template_id')->unsigned()->index('jobtemplate_id');
			$table->integer('recruiter_office_id')->unsigned()->index('recruiter_office_id');
			$table->integer('preferred_job_location_id')->unsigned();
			$table->tinyInteger('job_type')->comment('\'1\'=>Full time,\'2\'=>Part time,\'3\'=>Temp');
			$table->integer('no_of_jobs')->unsigned()->nullable()->default(0);
			$table->boolean('is_monday')->nullable();
			$table->boolean('is_tuesday')->nullable();
			$table->boolean('is_wednesday')->nullable();
			$table->boolean('is_thursday')->nullable();
			$table->boolean('is_friday')->nullable();
			$table->boolean('is_saturday')->nullable();
			$table->boolean('is_sunday')->nullable();
			$table->boolean('is_published')->nullable();
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
		Schema::drop('recruiter_jobs');
	}

}
