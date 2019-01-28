<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRecruiterProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recruiter_profiles', function(Blueprint $table)
		{
			$table->foreign('user_id', 'recruiter_profiles_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recruiter_profiles', function(Blueprint $table)
		{
			$table->dropForeign('recruiter_profiles_ibfk_1');
		});
	}

}
