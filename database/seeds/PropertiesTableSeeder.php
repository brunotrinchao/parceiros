<?php

use Illuminate\Database\Seeder;
use App\Models\Properties;

class PropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Properties::create([
            'id_client'     =>  1,
            'amount'        => 150.30,
            'input'         =>  40.00,
            'plots'         =>  30,
            'deadline'      =>  60,
            'type'          =>  'P',
            'note'          =>  'Teste'
        ]);
    }
}
