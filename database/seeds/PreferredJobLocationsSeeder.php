<?php

use App\Models\PreferredJobLocation;
use Illuminate\Database\Seeder;

class PreferredJobLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PreferredJobLocation::query()->truncate();

        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "San Francisco Bay Area"]);
        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "North Bay and Sacramento"]);
        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "Greater Los Angeles Area"]);
        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "Greater New York Area"]);
        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "Chicago Metropolitan Area"]);
        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "Greater Washington D.C. Area"]);
        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "Greater Philadelphia Area"]);
        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "Greater Boston Area"]);
        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "Seattle and Redmond Areas"]);
        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "Greater Atlanta Area"]);
        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "Greater Dallas Area"]);
        PreferredJobLocation::query()->insert(["is_active" => 1, "preferred_location_name" => "Greater Phoenix Area"]);

    }
}
