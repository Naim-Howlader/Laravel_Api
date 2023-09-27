<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            ['name'=>'Adnan','email'=>'adnan123@gmail.com','password'=>'1234'],
            ['name'=>'Robiul','email'=>'robiul123@gmail.com','password'=>'1234'],
            ['name'=>'Mahadi','email'=>'mahadi123@gmail.com','password'=>'1234'],
        ];
        User::insert($user);
    }
}
