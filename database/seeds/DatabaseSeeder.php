<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(PartnersTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        // $this->call(ClientsTableSeeder::class);
        // $this->call(AddressesTableSeeder::class);
        // $this->call(ContactsTableSeeder::class);
        // $this->call(PropertiesTableSeeder::class);
    }
}
