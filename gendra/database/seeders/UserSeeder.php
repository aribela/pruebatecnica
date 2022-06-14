<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::created([
            'name' => 'Rafa',
            'phone' => '123456789',
            'email' => 'rafa@email.com',
            'profile' => 'admin',
            'status' => 'active',
            'password' => bcrypt('123'),
        ]);

        User::create([
            'name' => 'Melisa',
            'phone' => '223456789',
            'email' => 'melisa@email.com',
            'profile' => 'user',
            'status' => 'active',
            'password' => bcrypt('123'),
        ]);
    }
}
