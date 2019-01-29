<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jobs', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('queue', 191);
			$table->longText('payload');
			$table->unsignedTinyInteger('attempts');
			$table->unsignedTinyInteger('reserved');
			$table->integer('reserved_at')->unsigned()->nullable();
			$table->integer('available_at')->unsigned();
			$table->integer('created_at')->unsigned()->useCurrent();
			$table->index(['queue','reserved','reserved_at']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('jobs');
	}

}
