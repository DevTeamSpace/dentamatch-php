<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSavedJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('saved_jobs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('recruiter_job_id')->unsigned()->index('recruiter_job_id');
			$table->integer('temp_job_id')->unsigned()->nullable()->index('temp_job_id');
			$table->integer('seeker_id')->unsigned()->index('seeker_id');
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
		Schema::drop('saved_jobs');
	}

}
