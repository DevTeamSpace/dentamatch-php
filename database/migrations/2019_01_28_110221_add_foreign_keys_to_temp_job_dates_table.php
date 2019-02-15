<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTempJobDatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('temp_job_dates', function(Blueprint $table)
		{
			$table->foreign('recruiter_job_id', 'temp_job_dates_ibfk_1')->references('id')->on('recruiter_jobs')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('temp_job_dates', function(Blueprint $table)
		{
			$table->dropForeign('temp_job_dates_ibfk_1');
		});
	}

}
