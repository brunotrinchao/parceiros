<?php

use Illuminate\Database\Seeder;
use App\Models\Imovel\Properties;

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
            'client_id'             =>  1,
            'amount'                => 150.30,
            'type_propertie'        =>  'Apartamento',
            'neighborhood'          =>  'Costa Azul',
            'type'                  =>  'T',
            'note'                  =>  'Teste'
        ]);
    }
}
