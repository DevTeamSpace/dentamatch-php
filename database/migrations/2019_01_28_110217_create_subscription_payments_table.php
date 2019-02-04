<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscription_payments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('recruiter_id')->unsigned()->index('recruiter_id');
			$table->float('amount', 10, 0);
			$table->string('payment_id', 55);
			$table->dateTime('subscription_expiry_date');
			$table->text('payment_response', 65535);
            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
			$table->date('trial_end')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subscription_payments');
	}

}
