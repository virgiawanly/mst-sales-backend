<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            'name' => 'Sales User 1',
            'email' => 'sales1@salestesting.com',
            'username' => 'sales1',
            'password' => bcrypt('password'), // @note: temporary password (should be changed on production)
        ];

        User::create($user);
    }
}
