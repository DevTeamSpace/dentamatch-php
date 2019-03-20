<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddress2ToOffices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruiter_offices', function (Blueprint $table) {
            $table->string('address_second_line', 300)->after('address')->nullable();
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
            $table->dropColumn('address_second_line');
        });
    }
}
