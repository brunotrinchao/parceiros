<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            User::create([
                'partner_id'    => 1,
                'name'          => 'Bruno Trinchão',
                'email'         => 'brunotrinchao@gmail.com',
                'password'      => bcrypt(123456),
                'date'          => date('Y-m-d'),
                'level'         => 'S',
                'status'        => 'A'
            ]);
    }
}
