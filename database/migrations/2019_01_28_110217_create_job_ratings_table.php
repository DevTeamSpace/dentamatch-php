<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobRatingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('job_ratings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('recruiter_job_id')->unsigned()->index('recruiter_job_id');
			$table->integer('temp_job_id')->unsigned()->nullable()->index('temp_job_id');
			$table->integer('seeker_id')->unsigned()->index('seeker_id');
			$table->boolean('punctuality')->default(0);
			$table->boolean('time_management')->default(0);
			$table->boolean('skills')->default(0);
			$table->boolean('teamwork')->default(0);
			$table->boolean('onemore')->default(0);
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
		Schema::drop('job_ratings');
	}

}
