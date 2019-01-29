<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToJobseekerSkillsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobseeker_skills', function(Blueprint $table)
		{
			$table->foreign('user_id', 'jobseeker_skills_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('skill_id', 'jobseeker_skills_ibfk_2')->references('id')->on('skills')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jobseeker_skills', function(Blueprint $table)
		{
			$table->dropForeign('jobseeker_skills_ibfk_1');
			$table->dropForeign('jobseeker_skills_ibfk_2');
		});
	}

}
