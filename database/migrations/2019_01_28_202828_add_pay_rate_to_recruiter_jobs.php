<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayRateToRecruiterJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruiter_jobs', function (Blueprint $table) {
            $table->unsignedInteger('pay_rate')->after('no_of_jobs')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruiter_jobs', function (Blueprint $table) {
            $table->dropColumn('pay_rate');
        });
    }
}
