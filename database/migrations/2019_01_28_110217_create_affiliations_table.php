<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAffiliationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('affiliations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('affiliation_name');
			$table->boolean('is_active')->default(0);
			$table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->dateTime('deleted_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('affiliations');
	}

}
