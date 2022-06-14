<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Denomination;
use App\Models\Product;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Rafa',
            'phone' => '123456789',
            'email' => 'rafa@email.com',
            'profile' => 'admin',
            'status' => 'active',
            'password' => bcrypt('123'),
        ]);
        // \App\Models\User::factory(10)->create();
        // $this->call(DenominationSeeder::class);
        $this->call(CategorySeeder::class);
        Denomination::factory(10)->create();
        $this->call(ProductSeeder::class);
        $this->call(UserSeeder::class);
    }
}
