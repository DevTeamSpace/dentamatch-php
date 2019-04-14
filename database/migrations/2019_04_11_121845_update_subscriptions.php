<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruiter_profiles', function (Blueprint $table) {
            $table->dropColumn('stripe_token');
            $table->dropColumn('free_period');
            $table->dropColumn('auto_renewal');
            $table->dropColumn('validity');

            $table->renameColumn('customer_id', 'stripe_id');
//            $table->string('stripe_id')->nullable()->collation('utf8mb4_bin')->after('customer_id');
            $table->string('card_brand')->nullable()->after('customer_id');
            $table->string('card_last_four', 4)->nullable()->after('card_brand');
            $table->timestamp('trial_ends_at')->nullable()->after('card_last_four');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->renameColumn('recruiter_id', 'user_id');
            $table->timestamp('ends_at')->nullable()->after('recruiter_id');
            $table->timestamp('trial_ends_at')->nullable()->after('recruiter_id');
            $table->integer('quantity')->after('recruiter_id');
            $table->string('stripe_plan')->after('recruiter_id');
            $table->string('stripe_id')->collation('utf8mb4_bin')->after('recruiter_id');
            $table->string('name')->after('recruiter_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruiter_profiles', function (Blueprint $table) {
            $table->string('stripe_token')->nullable();
            $table->integer('free_period')->unsigned()->nullable();
            $table->boolean('auto_renewal')->nullable();
            $table->dateTime('validity')->nullable();

            $table->renameColumn('stripe_id', 'customer_id');

//            $table->dropColumn('stripe_id');
            $table->dropColumn('card_brand');
            $table->dropColumn('card_last_four');
            $table->dropColumn('trial_ends_at');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->renameColumn('user_id', 'recruiter_id');
            $table->dropColumn('name');
            $table->dropColumn('stripe_id');
            $table->dropColumn('stripe_plan');
            $table->dropColumn('quantity');
            $table->dropColumn('trial_ends_at');
            $table->dropColumn('ends_at');
        });
    }
}
