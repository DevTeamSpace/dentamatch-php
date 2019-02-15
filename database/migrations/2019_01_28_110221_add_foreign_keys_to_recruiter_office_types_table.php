<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRecruiterOfficeTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recruiter_office_types', function(Blueprint $table)
		{
			$table->foreign('recruiter_office_id', 'recruiter_office_types_ibfk_1')->references('id')->on('recruiter_offices')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('office_type_id', 'recruiter_office_types_ibfk_2')->references('id')->on('office_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recruiter_office_types', function(Blueprint $table)
		{
			$table->dropForeign('recruiter_office_types_ibfk_1');
			$table->dropForeign('recruiter_office_types_ibfk_2');
		});
	}

}
