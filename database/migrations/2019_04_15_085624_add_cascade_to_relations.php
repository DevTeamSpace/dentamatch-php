<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCascadeToRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_user_list', function(Blueprint $table)
        {
            $table->dropForeign('chat_user_list_recuriter_id');
            $table->dropForeign('chat_user_list_seeker_id');
            $table->foreign('recruiter_id', 'chat_user_list_recuriter_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('seeker_id', 'chat_user_list_seeker_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('devices', function (Blueprint $table) {
            $table->dropForeign('devices_ibfk_1');
            $table->foreign('user_id', 'devices_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('favourites', function(Blueprint $table)
        {
            $table->dropForeign('favourites_ibfk_1');
            $table->dropForeign('favourites_ibfk_2');
            $table->foreign('recruiter_id', 'favourites_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('seeker_id', 'favourites_ibfk_2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('jobseeker_affiliations', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_affiliations_ibfk_1');
            $table->foreign('user_id', 'jobseeker_affiliations_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('jobseeker_certificates', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_certificates_ibfk_1');
            $table->foreign('user_id', 'jobseeker_certificates_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('jobseeker_profiles', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_profiles_ibfk_1');
            $table->foreign('user_id', 'jobseeker_profiles_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('jobseeker_schoolings', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_schoolings_ibfk_1');
            $table->foreign('user_id', 'jobseeker_schoolings_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('jobseeker_skills', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_skills_ibfk_1');
            $table->foreign('user_id', 'jobseeker_skills_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('jobseeker_temp_availability', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_temp_availability_ibfk_1');
            $table->foreign('user_id', 'jobseeker_temp_availability_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('jobseeker_temp_hired', function(Blueprint $table)
        {
            $table->dropForeign('fk_temp_hired_users');
            $table->foreign('jobseeker_id', 'fk_temp_hired_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('jobseeker_work_experiences', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_work_experiences_ibfk_1');
            $table->foreign('user_id', 'jobseeker_work_experiences_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('job_lists', function(Blueprint $table)
        {
            $table->dropForeign('job_lists_ibfk_1');
            $table->dropForeign('job_lists_ibfk_3');
            $table->foreign('seeker_id', 'job_lists_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('recruiter_job_id', 'job_lists_ibfk_3')->references('id')->on('recruiter_jobs')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('job_ratings', function(Blueprint $table)
        {
            $table->dropForeign('job_ratings_ibfk_1');
            $table->dropForeign('job_ratings_ibfk_2');
            $table->dropForeign('job_ratings_ibfk_3');
            $table->foreign('seeker_id', 'job_ratings_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('recruiter_job_id', 'job_ratings_ibfk_2')->references('id')->on('recruiter_jobs')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('temp_job_id', 'job_ratings_ibfk_3')->references('id')->on('temp_job_dates')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('job_templates', function(Blueprint $table)
        {
            $table->dropForeign('job_templates_ibfk_1');
            $table->foreign('user_id', 'job_templates_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('notification_logs', function(Blueprint $table)
        {
            $table->dropForeign('notification_logs_ibfk_1');
            $table->dropForeign('notification_logs_ibfk_2');
            $table->foreign('sender_id', 'notification_logs_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('receiver_id', 'notification_logs_ibfk_2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('password_resets', function(Blueprint $table)
        {
            $table->dropForeign('password_resets_user_id_foreign');
            $table->foreign('user_id', 'password_resets_user_id_foreign')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('recruiter_jobs', function(Blueprint $table)
        {
            $table->dropForeign('recruiter_jobs_ibfk_1');
            $table->dropForeign('recruiter_jobs_ibfk_2');
            $table->foreign('job_template_id', 'recruiter_jobs_ibfk_1')->references('id')->on('job_templates')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('recruiter_office_id', 'recruiter_jobs_ibfk_2')->references('id')->on('recruiter_offices')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('preferred_job_location_id', 'recruiter_jobs_ibfk_3')->references('id')->on('preferred_job_locations')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('recruiter_offices', function(Blueprint $table)
        {
            $table->dropForeign('recruiter_offices_ibfk_1');
            $table->foreign('user_id', 'recruiter_offices_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('recruiter_profiles', function(Blueprint $table)
        {
            $table->dropForeign('recruiter_profiles_ibfk_1');
            $table->foreign('user_id', 'recruiter_profiles_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('saved_jobs', function(Blueprint $table)
        {
            $table->dropForeign('saved_jobs_ibfk_1');
            $table->dropForeign('saved_jobs_ibfk_2');
            $table->dropForeign('saved_jobs_ibfk_3');
            $table->foreign('recruiter_job_id', 'saved_jobs_ibfk_1')->references('id')->on('recruiter_jobs')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('temp_job_id', 'saved_jobs_ibfk_2')->references('id')->on('temp_job_dates')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('seeker_id', 'saved_jobs_ibfk_3')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });

        Schema::table('search_logs', function(Blueprint $table)
        {
            $table->dropForeign('search_logs_ibfk_1');
            $table->foreign('user_id', 'search_logs_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign('subscriptions_ibfk_1');
            $table->foreign('legacy_user_id', 'subscriptions_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('template_skills', function(Blueprint $table)
        {
            $table->dropForeign('template_skills_ibfk_1');
            $table->foreign('job_template_id', 'template_skills_ibfk_1')->references('id')->on('job_templates')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('user_chat', function(Blueprint $table)
        {
            $table->dropForeign('user_chat_from_id');
            $table->dropForeign('user_chat_to_id');
            $table->foreign('from_id', 'user_chat_from_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('to_id', 'user_chat_to_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });

        Schema::table('user_groups', function(Blueprint $table)
        {
            $table->dropForeign('user_groups_ibfk_2');
            $table->foreign('user_id', 'user_groups_ibfk_2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_user_list', function(Blueprint $table)
        {
            $table->dropForeign('chat_user_list_recuriter_id');
            $table->dropForeign('chat_user_list_seeker_id');
            $table->foreign('recruiter_id', 'chat_user_list_recuriter_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('seeker_id', 'chat_user_list_seeker_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('devices', function (Blueprint $table) {
            $table->dropForeign('devices_ibfk_1');
            $table->foreign('user_id', 'devices_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('favourites', function(Blueprint $table)
        {
            $table->dropForeign('favourites_ibfk_1');
            $table->dropForeign('favourites_ibfk_2');
            $table->foreign('recruiter_id', 'favourites_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('seeker_id', 'favourites_ibfk_2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('jobseeker_affiliations', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_affiliations_ibfk_1');
            $table->foreign('user_id', 'jobseeker_affiliations_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('jobseeker_certificates', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_certificates_ibfk_1');
            $table->foreign('user_id', 'jobseeker_certificates_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('jobseeker_profiles', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_profiles_ibfk_1');
            $table->foreign('user_id', 'jobseeker_profiles_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('jobseeker_schoolings', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_schoolings_ibfk_1');
            $table->foreign('user_id', 'jobseeker_schoolings_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('jobseeker_skills', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_skills_ibfk_1');
            $table->foreign('user_id', 'jobseeker_skills_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('jobseeker_temp_availability', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_temp_availability_ibfk_1');
            $table->foreign('user_id', 'jobseeker_temp_availability_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('jobseeker_temp_hired', function(Blueprint $table)
        {
            $table->dropForeign('fk_temp_hired_users');
            $table->foreign('jobseeker_id', 'fk_temp_hired_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('jobseeker_work_experiences', function(Blueprint $table)
        {
            $table->dropForeign('jobseeker_work_experiences_ibfk_1');
            $table->foreign('user_id', 'jobseeker_work_experiences_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('job_lists', function(Blueprint $table)
        {
            $table->dropForeign('job_lists_ibfk_1');
            $table->dropForeign('job_lists_ibfk_3');
            $table->foreign('seeker_id', 'job_lists_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('recruiter_job_id', 'job_lists_ibfk_3')->references('id')->on('recruiter_jobs')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('job_ratings', function(Blueprint $table)
        {
            $table->dropForeign('job_ratings_ibfk_1');
            $table->dropForeign('job_ratings_ibfk_2');
            $table->dropForeign('job_ratings_ibfk_3');
            $table->foreign('seeker_id', 'job_ratings_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('recruiter_job_id', 'job_ratings_ibfk_2')->references('id')->on('recruiter_jobs')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('temp_job_id', 'job_ratings_ibfk_3')->references('id')->on('temp_job_dates')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('job_templates', function(Blueprint $table)
        {
            $table->dropForeign('job_templates_ibfk_1');
            $table->foreign('user_id', 'job_templates_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('notification_logs', function(Blueprint $table)
        {
            $table->dropForeign('notification_logs_ibfk_1');
            $table->dropForeign('notification_logs_ibfk_2');
            $table->foreign('sender_id', 'notification_logs_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('receiver_id', 'notification_logs_ibfk_2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('password_resets', function(Blueprint $table)
        {
            $table->dropForeign('password_resets_user_id_foreign');
            $table->foreign('user_id', 'password_resets_user_id_foreign')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('recruiter_jobs', function(Blueprint $table)
        {
            $table->dropForeign('recruiter_jobs_ibfk_1');
            $table->dropForeign('recruiter_jobs_ibfk_2');
            $table->dropForeign('recruiter_jobs_ibfk_3');
            $table->foreign('job_template_id', 'recruiter_jobs_ibfk_1')->references('id')->on('job_templates')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('recruiter_office_id', 'recruiter_jobs_ibfk_2')->references('id')->on('recruiter_offices')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('recruiter_offices', function(Blueprint $table)
        {
            $table->dropForeign('recruiter_offices_ibfk_1');
            $table->foreign('user_id', 'recruiter_offices_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('recruiter_profiles', function(Blueprint $table)
        {
            $table->dropForeign('recruiter_profiles_ibfk_1');
            $table->foreign('user_id', 'recruiter_profiles_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('saved_jobs', function(Blueprint $table)
        {
            $table->dropForeign('saved_jobs_ibfk_1');
            $table->dropForeign('saved_jobs_ibfk_2');
            $table->dropForeign('saved_jobs_ibfk_3');
            $table->foreign('recruiter_job_id', 'saved_jobs_ibfk_1')->references('id')->on('recruiter_jobs')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('temp_job_id', 'saved_jobs_ibfk_2')->references('id')->on('temp_job_dates')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('seeker_id', 'saved_jobs_ibfk_3')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });

        Schema::table('search_logs', function(Blueprint $table)
        {
            $table->dropForeign('search_logs_ibfk_1');
            $table->foreign('user_id', 'search_logs_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign('subscriptions_ibfk_1');
            $table->foreign('legacy_user_id', 'subscriptions_ibfk_1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('template_skills', function(Blueprint $table)
        {
            $table->dropForeign('template_skills_ibfk_1');
            $table->foreign('job_template_id', 'template_skills_ibfk_1')->references('id')->on('job_templates')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('user_chat', function(Blueprint $table)
        {
            $table->dropForeign('user_chat_from_id');
            $table->dropForeign('user_chat_to_id');
            $table->foreign('from_id', 'user_chat_from_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('to_id', 'user_chat_to_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('user_groups', function(Blueprint $table)
        {
            $table->dropForeign('user_groups_ibfk_2');
            $table->foreign('user_id', 'user_groups_ibfk_2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }
}
