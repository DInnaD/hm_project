<?php

use App\Models\user_vacancy;
use Illuminate\Database\Seeder;

class UserVacancyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(user_vacancy::class, 20)->create();
    }
}
