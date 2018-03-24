<?php

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 16; $i <= 52; $i++){
            Contact::create([
                'client_id'    => $i,
                'phone'         => '71987940816'
            ]);
        }
    }
}
