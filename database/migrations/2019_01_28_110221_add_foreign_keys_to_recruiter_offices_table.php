<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRecruiterOfficesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recruiter_offices', function(Blueprint $table)
		{
			$table->foreign('user_id', 'recruiter_offices_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recruiter_offices', function(Blueprint $table)
		{
			$table->dropForeign('recruiter_offices_ibfk_1');
		});
	}

}
