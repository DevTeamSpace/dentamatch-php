<?php

use App\Models\OfficeType;
use Illuminate\Database\Seeder;

class OfficeTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OfficeType::query()->truncate();

        OfficeType::query()->insert(["is_active" => 1, "officetype_name" => "Orthodontist"]);
        OfficeType::query()->insert(["is_active" => 1, "officetype_name" => "Periodontist"]);
        OfficeType::query()->insert(["is_active" => 1, "officetype_name" => "Oral Surgeon"]);
        OfficeType::query()->insert(["is_active" => 1, "officetype_name" => "Pedodontist"]);
        OfficeType::query()->insert(["is_active" => 1, "officetype_name" => "Endodontist"]);
        OfficeType::query()->insert(["is_active" => 1, "officetype_name" => "Prosthodontist"]);
        OfficeType::query()->insert(["is_active" => 0, "officetype_name" => "Dental Lab"]);
        OfficeType::query()->insert(["is_active" => 1, "officetype_name" => "General Dentistry"]);
        OfficeType::query()->insert(["is_active" => 1, "officetype_name" => "Special needs/Hospital Dentistry"]);
        OfficeType::query()->insert(["is_active" => 1, "officetype_name" => "Lab technician"]);
        OfficeType::query()->insert(["is_active" => 1, "officetype_name" => "Other"]);
    }
}
