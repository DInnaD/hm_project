<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(OrganizationTableSeeder::class);
        $this->call(VacancyTableSeeder::class);
        $this->call(UserVacancyTableSeeder::class);
    }
}
