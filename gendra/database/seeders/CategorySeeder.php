<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Cursos',
            'image' => 'https://dummyimage.com/200x150/32d6c6/fff.png&text=cursos',
        ]);

        Category::create([
            'name' => 'Tenis',
            'image' => 'https://dummyimage.com/200x150/32d6c6/fff.png&text=tenis',
        ]);

        Category::create([
            'name' => 'Celulares',
            'image' => 'https://dummyimage.com/200x150/32d6c6/fff.png&text=celularea',
        ]);

        Category::create([
            'name' => 'Computadoras',
            'image' => 'https://dummyimage.com/200x150/32d6c6/fff.png&text=computadoras',
        ]);
    }
}
