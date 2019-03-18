<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfficeNameAndDescriptionToOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruiter_offices', function (Blueprint $table) {
            $table->string('office_name')->after('user_id')->nullable();
            $table->longText('office_desc')->after('phone_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruiter_offices', function (Blueprint $table) {
            $table->dropColumn('office_name');
            $table->dropColumn('office_desc');
        });
    }
}
