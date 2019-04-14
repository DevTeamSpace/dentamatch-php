<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewUserIdToSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->renameColumn('user_id', 'legacy_user_id');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('recruiter_profile_id')->after('legacy_user_id');
            $table->unsignedInteger('legacy_user_id')->nullable()->change();
        });

        \DB::getPdo()->exec("UPDATE subscriptions s JOIN recruiter_profiles rp ON s.legacy_user_id = rp.user_id SET s.recruiter_profile_id = rp.id ");

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreign('recruiter_profile_id', 'subscr_recruiter_ibfk_2')->references('id')->on('recruiter_profiles')->onUpdate('NO ACTION')->onDelete('CASCADE');
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
            $table->dropForeign('subscr_recruiter_ibfk_2');
            $table->dropColumn('recruiter_profile_id');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->renameColumn('legacy_user_id', 'user_id');
        });
    }
}
