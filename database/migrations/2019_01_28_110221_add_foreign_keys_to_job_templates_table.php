<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToJobTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('job_templates', function(Blueprint $table)
		{
			$table->foreign('user_id', 'job_templates_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('job_title_id', 'job_templates_ibfk_2')->references('id')->on('job_titles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('job_templates', function(Blueprint $table)
		{
			$table->dropForeign('job_templates_ibfk_1');
			$table->dropForeign('job_templates_ibfk_2');
		});
	}

}
