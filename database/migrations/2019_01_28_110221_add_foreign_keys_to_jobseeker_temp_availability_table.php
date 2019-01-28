<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToJobseekerTempAvailabilityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobseeker_temp_availability', function(Blueprint $table)
		{
			$table->foreign('user_id', 'jobseeker_temp_availability_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jobseeker_temp_availability', function(Blueprint $table)
		{
			$table->dropForeign('jobseeker_temp_availability_ibfk_1');
		});
	}

}
