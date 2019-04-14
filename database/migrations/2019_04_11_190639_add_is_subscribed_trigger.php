<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSubscribedTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::getPdo()->exec("
            DROP PROCEDURE IF EXISTS `validate_is_subscribed`;

CREATE PROCEDURE `validate_is_subscribed`(IN `u_id` INT) COMMENT 'Исправление значения recruiter_profile.is_subscribed' 
UPDATE recruiter_profiles 
SET is_subscribed = EXISTS(SELECT id FROM subscriptions WHERE recruiter_profile_id = u_id && (COALESCE(ends_at, CURRENT_TIMESTAMP) >= CURRENT_TIMESTAMP
|| COALESCE(trial_ends_at, CURRENT_TIMESTAMP) > CURRENT_TIMESTAMP))
WHERE id = u_id
        ");

        \DB::getPdo()->exec("
        DROP TRIGGER IF EXISTS `subscription_after_insert`;
        CREATE TRIGGER `subscription_after_insert` AFTER INSERT ON `subscriptions` FOR EACH ROW CALL validate_is_subscribed(NEW.recruiter_profile_id)");

        \DB::getPdo()->exec("
        DROP TRIGGER IF EXISTS `subscription_after_update`;
        CREATE TRIGGER `subscription_after_update` AFTER UPDATE ON `subscriptions` FOR EACH ROW
        BEGIN
            CALL validate_is_subscribed(OLD.user_id);
            IF NEW.recruiter_profile_id <> OLD.recruiter_profile_id THEN
                CALL validate_is_subscribed(NEW.recruiter_profile_id);
            END IF;
        END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::getPdo()->exec("DROP PROCEDURE IF EXISTS `validate_is_subscribed`;");
        \DB::getPdo()->exec("DROP TRIGGER IF EXISTS `subscription_after_insert`;");
        \DB::getPdo()->exec("DROP TRIGGER IF EXISTS `subscription_after_update`;");
    }
}
