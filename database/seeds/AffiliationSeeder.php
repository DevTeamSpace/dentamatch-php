<?php

use App\Models\Affiliation;
use Illuminate\Database\Seeder;

class AffiliationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Affiliation::query()->truncate();

        Affiliation::query()->insert(["is_active" => 0, "affiliation_name" => "ADA"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "American Dental Association"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "American Dental Hygiene Association"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "National Dental Hygiene Association"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "California Dental Hygiene Assocation"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "American Dental Assisting Association"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "California Dental Assisting Association"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "National Dental Assisting Association"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "Other"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "Dental Hygienists Association of the City of New York"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "Test Dental Association"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "New York Dental Association"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "New Jersey Dental Assoication"]);
        Affiliation::query()->insert(["is_active" => 1, "affiliation_name" => "California Dental Association"]);

    }
}
