<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'phone' => '01633749988',
                'email' => 'admin@gmail.com',
                'password' => hash::make('123456'),
                'role' => 'admin',
                'address' => 'mirpur-12'

            ],

            [
                'name' => 'agent',
                'phone' => '01763099954',
                'email' => 'agent@gmail.com',
                'password' => hash::make('123456'),
                'role' => 'agent',
                'address' => 'Mohammotpur'

            ],

            [
                'name' => 'user',
                'phone' => '01822215488',
                'email' => 'user@gmail.com',
                'password' => hash::make('123456'),
                'role' => 'user',
                'address' => 'Rangpur'

            ]
        ]);
    }
}
