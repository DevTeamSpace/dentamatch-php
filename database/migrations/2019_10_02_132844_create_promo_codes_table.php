<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 100)->unique();
            $table->string('name', 220);
            $table->string('valid_days_from_sign_up', 100)->nullable();
            $table->date('valid_until')->nullable();
            $table->unsignedMediumInteger('free_days')->nullable();
            $table->unsignedTinyInteger('discount_on_subscription')->nullable();
            $table->boolean('active')->default(true);
            $table->string('subscription', 100);

            $table->dateTime('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_codes');
    }
}
