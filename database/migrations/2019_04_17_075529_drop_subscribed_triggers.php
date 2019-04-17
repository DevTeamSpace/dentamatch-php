<?php

use Illuminate\Database\Migrations\Migration;

class DropSubscribedTriggers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::getPdo()->exec("DROP TRIGGER IF EXISTS `subscription_after_insert`;");
        \DB::getPdo()->exec("DROP TRIGGER IF EXISTS `subscription_after_update`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
