<?php

use Illuminate\Database\Seeder;
use App\Models\Partner;

class PartnersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Partner::create([
            'name'      =>  'Supercredito',
            'status'    =>  'A',
            'date'      =>  date('Y-m-d')  
        ]);
    }
}
