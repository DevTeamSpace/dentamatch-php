<?php

use Illuminate\Database\Seeder;

class GroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Db::table('groups')->truncate();

        \Db::table('groups')->insert(["group_name" => "Admin"]);
        \Db::table('groups')->insert(["group_name" => "Recruiter"]);
        \Db::table('groups')->insert(["group_name" => "jobseeker"]);
    }
}
