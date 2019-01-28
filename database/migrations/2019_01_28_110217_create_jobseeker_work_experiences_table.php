<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobseekerWorkExperiencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobseeker_work_experiences', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->integer('job_title_id')->unsigned()->index('jobtitle_id');
			$table->smallInteger('months_of_expereince');
			$table->string('office_name');
			$table->string('office_address');
			$table->string('city');
			$table->string('reference1_name')->nullable()->default('');
			$table->string('reference1_mobile', 25)->nullable()->default('');
			$table->string('reference1_email')->nullable()->default('');
			$table->string('reference2_name')->nullable()->default('');
			$table->string('reference2_mobile', 25)->nullable()->default('');
			$table->string('reference2_email')->nullable()->default('');
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
		Schema::drop('jobseeker_work_experiences');
	}

}
