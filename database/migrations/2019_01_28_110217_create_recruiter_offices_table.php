<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecruiterOfficesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recruiter_offices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->string('address')->nullable();
			$table->mediumInteger('zipcode')->unsigned()->nullable();
			$table->float('latitude', 10, 0)->nullable();
			$table->float('longitude', 10, 0)->nullable();
			$table->string('phone_no', 25);
			$table->longText('office_info')->nullable();
			$table->time('work_everyday_start')->nullable();
			$table->time('work_everyday_end')->nullable();
			$table->time('monday_start')->nullable();
			$table->time('monday_end')->nullable();
			$table->time('tuesday_start')->nullable();
			$table->time('tuesday_end')->nullable();
			$table->time('wednesday_start')->nullable();
			$table->time('wednesday_end')->nullable();
			$table->time('thursday_start')->nullable();
			$table->time('thursday_end')->nullable();
			$table->time('friday_start')->nullable();
			$table->time('friday_end')->nullable();
			$table->time('saturday_start')->nullable();
			$table->time('saturday_end')->nullable();
			$table->time('sunday_start')->nullable();
			$table->time('sunday_end')->nullable();
			$table->text('office_location', 65535)->nullable();
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
		Schema::drop('recruiter_offices');
	}

}
