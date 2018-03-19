<?php

use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::create([
            'id_client'     =>  1,
            'street'        =>  'Tv. Mata Atlântica 1',
            'complement'    =>  'Cond. Mata Atlântica 1',
            'number'        =>  26,
            'neighborhood'  =>  'Canabrava',
            'city'          =>  'Salvador',
            'state'         =>  'BA',
            'zip_code'      =>  '41260205',
            'date'          =>  date('Y-m-d')
        ]);
    }
}
