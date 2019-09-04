<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActionLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('action_log', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('category');
            $table->tinyInteger('type');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('to_user_id')->unsigned()->nullable();
            $table->integer('job_id')->unsigned()->nullable();
            $table->integer('job_list_id')->unsigned()->nullable();
            $table->text('request_data')->nullable();
            $table->text('response_data')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('to_user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('job_id')->references('id')->on('recruiter_jobs')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('job_list_id')->references('id')->on('job_lists')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('action_log');
    }
}
