<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRecruiterProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recruiter_profiles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->boolean('is_subscribed')->nullable()->default(0);
			$table->string('stripe_token')->nullable();
			$table->string('customer_id')->nullable();
			$table->boolean('accept_term')->default(0);
			$table->integer('free_period')->unsigned()->nullable();
			$table->boolean('auto_renewal')->nullable();
			$table->dateTime('validity')->nullable();
			$table->string('office_name')->nullable();
			$table->longText('office_desc')->nullable();
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recruiter_profiles');
	}

}
