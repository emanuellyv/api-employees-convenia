<?php

namespace Database\Seeders;

use App\Models\Manager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Manager::where('email', 'emanuellyvalenga.dev@gmail.com')->exists()) {
            Manager::create([
                'name' => 'Emanuelly',
                'email' => 'emanuellyvalenga.dev@gmail.com',
                'password' => bcrypt('123456abc', ['rounds' => 12])
            ]);
        }

        if (!Manager::where('email', 'manager-teste@gmail.com')->exists()) {
            Manager::create([
                'name' => 'Manager',
                'email' => 'manager-teste@gmail.com',
                'password' => bcrypt('123456abc', ['rounds' => 12])
            ]);
        }
    }
}
