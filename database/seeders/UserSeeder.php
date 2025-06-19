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
        User::updateOrCreate([
            'email' => 'charlyrm14@gmail.com'
        ], [
            'first_name' => 'Carlos I.',
            'last_name' => 'Ramos',
            'mother_last_name' => 'Morales',
            'birth_date' => '1990-12-01',
            'email' => 'charlyrm14@gmail.com',
            'user_code' => '20250616184426',
            'password' => 'Ch@rlyrm07',
            'role_id' => 1
        ]);
    }
}
