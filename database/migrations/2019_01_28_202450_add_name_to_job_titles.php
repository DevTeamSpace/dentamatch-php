<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameToJobTitles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_titles', function (Blueprint $table) {
            $table->string('short_name', 25)->after('jobtitle_name')->nullable();
            $table->unsignedInteger('parent_id')->after('short_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_titles', function (Blueprint $table) {
            $table->dropColumn(['short_name', 'parent_id']);
        });
    }
}
