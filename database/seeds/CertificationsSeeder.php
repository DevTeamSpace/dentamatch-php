<?php

use App\Models\Certifications;
use Illuminate\Database\Seeder;

class CertificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Certifications::query()->truncate();

        Certifications::query()->insert(["is_active" => 1, "certificate_name" => "OSHA"]);
        Certifications::query()->insert(["is_active" => 1, "certificate_name" => "Infection control"]);
        Certifications::query()->insert(["is_active" => 1, "certificate_name" => "Radiology certification"]);
        Certifications::query()->insert(["is_active" => 0, "certificate_name" => "License/passport"]);
        Certifications::query()->insert(["is_active" => 0, "certificate_name" => "I9"]);
        Certifications::query()->insert(["is_active" => 1, "certificate_name" => "CPR"]);
        Certifications::query()->insert(["is_active" => 1, "certificate_name" => "Resume"]);
    }
}
