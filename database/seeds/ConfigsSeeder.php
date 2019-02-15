<?php

use App\Models\Configs;
use Illuminate\Database\Seeder;

class ConfigsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configs::query()->truncate();

        Configs::query()->insert(["config_name" => "RATESEEKER", "config_data" => 5, "config_desc" => "Notify the recuriter X days after the temp job completion to rate the job seekers"]);
        Configs::query()->insert(["config_name" => "RECURITERNOTIFY", "config_data" => 7, "config_desc" => "Notify X days before the temp job expires"]);
        Configs::query()->insert(["config_name" => "CERTEXP", "config_data" => 21, "config_desc" => "Notify X days before the expiry of certificates"]);
        Configs::query()->insert(["config_name" => "SEARCHRADIUS", "config_data" => 50, "config_desc" => "Readius X miles search job"]);
        Configs::query()->insert(["config_name" => "PAYRATE", "config_data" => "payrate/15bf2780b57503.jpeg", "config_desc" => "File pay-rate"]);
    }
}
