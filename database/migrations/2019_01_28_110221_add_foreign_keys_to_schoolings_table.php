<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSchoolingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('schoolings', function(Blueprint $table)
		{
			$table->foreign('parent_id', 'schoolings_ibfk_1')->references('id')->on('schoolings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('schoolings', function(Blueprint $table)
		{
			$table->dropForeign('schoolings_ibfk_1');
		});
	}

}
