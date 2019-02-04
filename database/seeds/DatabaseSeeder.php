<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call(AffiliationSeeder::class);
        $this->call(CertificationsSeeder::class);
        $this->call(ConfigsSeeder::class);
        $this->call(GroupsSeeder::class);
        $this->call(LocationsSeeder::class);
        $this->call(OfficeTypesSeeder::class);
        $this->call(PreferredJobLocationsSeeder::class);
        $this->call(SchoolingsSeeder::class);
        $this->call(SkillsSeeder::class);
        $this->call(StatesSeeder::class);
        $this->call(UserSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
