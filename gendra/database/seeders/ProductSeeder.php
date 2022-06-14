<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Larael y livewire',
            'cost' => 200,
            'price' => 350,
            'barcode' => '123456789',
            'stock' => 1000,
            'alerts' => 10,
            'image' => 'curso.png',
            'category_id' => 1,
        ]);

        Product::create([
            'name' => 'Tenis de futbol',
            'cost' => 250,
            'price' => 400,
            'barcode' => '223456789',
            'stock' => 1000,
            'alerts' => 10,
            'image' => 'curso.png',
            'category_id' => 2,
        ]);

        Product::create([
            'name' => 'Moto e7 power',
            'cost' => 2500,
            'price' => 3098,
            'barcode' => '323456789',
            'stock' => 1000,
            'alerts' => 10,
            'image' => 'curso.png',
            'category_id' => 3,
        ]);

        Product::create([
            'name' => 'Hp pavilion',
            'cost' => 5000,
            'price' => 7500,
            'barcode' => '423456789',
            'stock' => 1000,
            'alerts' => 10,
            'image' => 'curso.png',
            'category_id' => 4,
        ]);
    }
}
