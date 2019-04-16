<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePreferredLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preferred_job_locations', function(Blueprint $table)
        {
            $table->unsignedMediumInteger('anchor_zipcode')->nullable();
            $table->unsignedSmallInteger('radius')->nullable()->comment('in miles');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preferred_job_locations', function(Blueprint $table)
        {
            $table->dropColumn('anchor_zipcode');
            $table->dropColumn('radius');

        });
    }
}
