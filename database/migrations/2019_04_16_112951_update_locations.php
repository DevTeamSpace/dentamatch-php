<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function(Blueprint $table)
        {
            $table->dropColumn('free_trial_period');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('county')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('distance')->nullable();
            $table->unsignedInteger('area_id')->nullable();
            $table->foreign('area_id', 'locations_area_1')->references('id')->on('preferred_job_locations')->onUpdate('NO ACTION')->onDelete('CASCADE');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function(Blueprint $table)
        {
            $table->boolean('free_trial_period');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('county');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('distance');
            $table->dropForeign('locations_area_1');
            $table->dropColumn('area_id');
        });
    }
}
