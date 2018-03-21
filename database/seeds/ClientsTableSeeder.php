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
        Client::create([
            'user_id'   =>  1,
            'name'      =>  'Fulano',
            'email'     =>  'fulano@teste.com',
            'birth'     =>  date('1983-04-17'),
            'sex'       =>  'M',
            'type'      =>  'F',
            'type'      =>  'F',
            'cpf_cnpj'  =>  '01023644517',
            'date'      =>  date('Y-m-d')
        ]);
    }
}
