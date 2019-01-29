<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToJobseekerAffiliationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobseeker_affiliations', function(Blueprint $table)
		{
			$table->foreign('user_id', 'jobseeker_affiliations_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('affiliation_id', 'jobseeker_affiliations_ibfk_2')->references('id')->on('affiliations')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jobseeker_affiliations', function(Blueprint $table)
		{
			$table->dropForeign('jobseeker_affiliations_ibfk_1');
			$table->dropForeign('jobseeker_affiliations_ibfk_2');
		});
	}

}
