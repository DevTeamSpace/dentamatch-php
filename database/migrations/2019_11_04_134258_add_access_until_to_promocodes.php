<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccessUntilToPromocodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->date('access_until')->nullable()->after('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promocodes', function (Blueprint $table) {
            $table->dropColumn('access_until');
        });
    }
}
