<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'role' => 'admin',
            'country' => 'USA',
            'city' => 'NY',
            'phone' => '555-5555-520',
            'email' => 'admin@localhost',
            'password' => Hash::make(123456)
        ];
        User::create($admin);
        factory(User::class, 100)->create();
    }
}
