<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Denomination;

class DenominationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Denomination::create([
            'type' => 'billete',
            'value' => '1000',
        ]);

        Denomination::create([
            'type' => 'billete',
            'value' => '500',
        ]);

        Denomination::create([
            'type' => 'billete',
            'value' => '200',
        ]);

        Denomination::create([
            'type' => 'billete',
            'value' => '100',
        ]);

        Denomination::create([
            'type' => 'billete',
            'value' => '50',
        ]);

        Denomination::create([
            'type' => 'moneda',
            'value' => '10',
        ]);

        Denomination::create([
            'type' => 'moneda',
            'value' => '5',
        ]);

        Denomination::create([
            'type' => 'moneda',
            'value' => '2',
        ]);

        Denomination::create([
            'type' => 'moneda',
            'value' => '1',
        ]);

        Denomination::create([
            'type' => 'otro',
            'value' => '0',
        ]);
    }
}
