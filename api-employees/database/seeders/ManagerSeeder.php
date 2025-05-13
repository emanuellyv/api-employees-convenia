<?php

namespace Database\Seeders;

use App\Models\Manager;
use Illuminate\Database\Seeder;

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
                'email' => 'emanuellyvalenga.dev@gmail.com'
            ]);
        }

        if (!Manager::where('email', 'isabellegomes0120@gmail.com')->exists()) {
            Manager::create([
                'name' => 'Isabelle',
                'email' => 'isabellegomes0120@gmail.com'
            ]);
        }
    }
}
