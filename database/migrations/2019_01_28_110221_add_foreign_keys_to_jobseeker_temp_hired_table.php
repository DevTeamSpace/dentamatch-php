<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToJobseekerTempHiredTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobseeker_temp_hired', function(Blueprint $table)
		{
			$table->foreign('jobseeker_id', 'fk_temp_hired_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jobseeker_temp_hired', function(Blueprint $table)
		{
			$table->dropForeign('fk_temp_hired_users');
		});
	}

}
