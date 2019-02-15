<?php

use App\Enums\SignupSource;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobseekerProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobseeker_profiles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->string('first_name', 50);
			$table->string('last_name', 50);
			$table->string('profile_pic')->nullable();
			$table->string('zipcode', 8)->nullable()->default('');
			$table->float('latitude', 10, 0)->nullable();
			$table->float('longitude', 10, 0)->nullable();
			$table->integer('preferred_job_location_id')->unsigned()->nullable()->index('jobseeker_profiles_preferred_job_locations_fk');
			$table->text('preferred_job_location', 65535)->nullable();
			$table->integer('job_titile_id')->unsigned()->nullable()->index('jobtitile_id');
			$table->string('dental_state_board')->nullable();
			$table->string('license_number')->nullable();
			$table->string('state')->nullable();
			$table->longText('about_me')->nullable();
			$table->boolean('is_completed')->default(0);
			$table->tinyInteger('is_job_seeker_verified')->default(0)->comment('0=Not Verified,1=>Approved,2=>Reject');
			$table->boolean('is_fulltime')->default(0);
			$table->boolean('is_parttime_monday')->default(0);
			$table->boolean('is_parttime_tuesday')->default(0);
			$table->boolean('is_parttime_wednesday')->default(0);
			$table->boolean('is_parttime_thursday')->default(0);
			$table->boolean('is_parttime_friday')->default(0);
			$table->boolean('is_parttime_saturday')->default(0);
			$table->boolean('is_parttime_sunday')->default(0);
			$table->tinyInteger('signup_source')->default(SignupSource::APP)->comment('1=>App, 2=>Web');
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
			$table->string('preferred_city')->nullable();
			$table->string('preferred_state')->nullable();
			$table->string('preferred_country')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('jobseeker_profiles');
	}

}
