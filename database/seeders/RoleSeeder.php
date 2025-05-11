<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(['name' => 'administrador'], ['name' => 'administrador']);
        Role::updateOrCreate(['name' => 'profesor'], ['name' => 'profesor']);
        Role::updateOrCreate(['name' => 'estudiante'], ['name' => 'estudiante']);
    }
}
