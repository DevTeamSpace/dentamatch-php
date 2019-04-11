<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->renameColumn('payment_id', 'subscription_id');
            $table->renameColumn('payment_response', 'subscription_response');
            $table->boolean('cancel_at_period_end')->default(false)->after('subscription_expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->float('amount', 10, 0);
            $table->renameColumn('subscription_id', 'payment_id');
            $table->renameColumn('subscription_response', 'payment_response');
            $table->dropColumn('cancel_at_period_end');
        });
    }
}
