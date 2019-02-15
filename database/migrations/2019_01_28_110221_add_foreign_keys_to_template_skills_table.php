<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTemplateSkillsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('template_skills', function(Blueprint $table)
		{
			$table->foreign('job_template_id', 'template_skills_ibfk_1')->references('id')->on('job_templates')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('skill_id', 'template_skills_ibfk_2')->references('id')->on('skills')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('template_skills', function(Blueprint $table)
		{
			$table->dropForeign('template_skills_ibfk_1');
			$table->dropForeign('template_skills_ibfk_2');
		});
	}

}
