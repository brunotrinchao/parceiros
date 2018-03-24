<?php

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        for($i = 0; $i < 40; $i++){
            Client::create([
                'user_id'   =>  1,
                'name'      =>  'teste-' . rand(),
                'email'     =>  'fulano@teste.com',
                'birth'     =>  date('1983-04-17'),
                'sex'       =>  'M',
                'type'      =>  'F',
                'type'      =>  'F',
                'cpf_cnpj'  =>  '0000000000'.$i,
                'date'      =>  date('Y-m-d')
            ]);
        }
    }
}
