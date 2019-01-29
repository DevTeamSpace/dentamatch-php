<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToJobseekerProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobseeker_profiles', function(Blueprint $table)
		{
			$table->foreign('user_id', 'jobseeker_profiles_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('job_titile_id', 'jobseeker_profiles_ibfk_2')->references('id')->on('job_titles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('preferred_job_location_id', 'jobseeker_profiles_preferred_job_locations_fk')->references('id')->on('preferred_job_locations')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jobseeker_profiles', function(Blueprint $table)
		{
			$table->dropForeign('jobseeker_profiles_ibfk_1');
			$table->dropForeign('jobseeker_profiles_ibfk_2');
			$table->dropForeign('jobseeker_profiles_preferred_job_locations_fk');
		});
	}

}
