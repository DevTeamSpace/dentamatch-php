<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToJobseekerCertificatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jobseeker_certificates', function(Blueprint $table)
		{
			$table->foreign('user_id', 'jobseeker_certificates_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('certificate_id', 'jobseeker_certificates_ibfk_2')->references('id')->on('certifications')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jobseeker_certificates', function(Blueprint $table)
		{
			$table->dropForeign('jobseeker_certificates_ibfk_1');
			$table->dropForeign('jobseeker_certificates_ibfk_2');
		});
	}

}
