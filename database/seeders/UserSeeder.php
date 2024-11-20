<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = array(
            [
                'name' => 'Basil',
                'email' => 'vonavud@gmail.com',
                'password' => bcrypt('vonavud@gmail.com'),
            ],
            [
                'name' => 'Kate',
                'email' => 'kjob2020@mail.ru',
                'password' => bcrypt('123456'),
            ]
        );
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
