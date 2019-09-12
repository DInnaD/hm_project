<?php

use App\Models\user_vacancy;
use App\Models\User;
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
        // factory(user_vacancy::class, 100)->create();
        User::all()->each(function($user){
            $user->vacancies()->attach(rand(1,20));
        });
    }
}
