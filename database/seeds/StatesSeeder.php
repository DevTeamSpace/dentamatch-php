<?php

use App\Models\State;
use Illuminate\Database\Seeder;

class StatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::truncate();

        State::query()->insert(["is_active" => 1, "state_name" => "Alabama"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Alaska"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Arizona"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Arkansas"]);
        State::query()->insert(["is_active" => 1, "state_name" => "California"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Colorado"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Connecticut"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Delaware"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Florida"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Georgia"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Hawaii"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Idaho"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Illinois"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Indiana"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Iowa"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Kansas"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Kentucky"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Louisiana"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Maine"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Maryland"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Massachusetts"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Michigan"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Minnesota"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Mississippi"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Missouri"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Montana"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Nebraska"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Nevada"]);
        State::query()->insert(["is_active" => 1, "state_name" => "New Hampshire"]);
        State::query()->insert(["is_active" => 1, "state_name" => "New Jersey"]);
        State::query()->insert(["is_active" => 1, "state_name" => "New Mexico"]);
        State::query()->insert(["is_active" => 1, "state_name" => "New York"]);
        State::query()->insert(["is_active" => 1, "state_name" => "North Carolina"]);
        State::query()->insert(["is_active" => 1, "state_name" => "North Dakota"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Ohio"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Oklahoma"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Oregon"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Pennsylvania"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Rhode Island"]);
        State::query()->insert(["is_active" => 1, "state_name" => "South Carolina"]);
        State::query()->insert(["is_active" => 1, "state_name" => "South Dakota"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Tennessee"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Texas"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Utah"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Vermont"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Virginia"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Washington"]);
        State::query()->insert(["is_active" => 1, "state_name" => "West Virginia"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Wisconsin"]);
        State::query()->insert(["is_active" => 1, "state_name" => "Wyoming"]);

    }
}
