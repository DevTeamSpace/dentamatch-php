<?php

use App\Models\Schooling;
use Illuminate\Database\Seeder;

class SchoolingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schooling::query()->truncate();

        Schooling::query()->insert(["is_active" => 1, "parent_id" => null, "school_name" => "Dental School"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => null, "school_name" => "Hygiene School"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => null, "school_name" => "Dental Assisting School"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => null, "school_name" => "Dental Lab Tech School"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "SC 12"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 2, "school_name" => "SC 21"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 2, "school_name" => "SC 22"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 2, "school_name" => "SC 23"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 2, "school_name" => "SC 24"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Acharya Narendra Dev College"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Aditi Mahavidyalaya"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Ahilya Bai College of Nursing"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Amar Jyoti Institute of Physiotherapy"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Atma Ram Sanatan Dharam College"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Bhim Rao Ambedkar College"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Bhaskaracharya College of Applied Sciences"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Bhim Rao Ambedkar College"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Chacha Nehru Bal Chikitsalaya"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "College of Art"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "College of Nursing at Army Hospital (R & R)"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "College of Vocational Studies"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Daulat Ram College"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Deen Dayal Upadhyaya College"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Delhi College of Arts & Commerce"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Delhi Institute of Pharmaceutical Sciences & Research"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Deshbandhu College"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Durga Bai Deshmukh College of Special Education"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Dyal Singh College"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Hans Raj College"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Hindu College"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Holy Family College of Nursing"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Indira Gandhi Institute of Physical Education & Sports Sciences"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Indraprastha College for Women"]);
        Schooling::query()->insert(["is_active" => 1, "parent_id" => 1, "school_name" => "Institute of Home Economics"]);

    }
}
