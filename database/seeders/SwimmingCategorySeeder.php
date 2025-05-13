<?php

namespace Database\Seeders;

use App\Models\SwimmingCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SwimmingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SwimmingCategory::updateOrCreate([
            'title' => 'Foca'
        ], [
            'title' => 'Foca',
            'image' => 'uploads/swimming-categories/foca.png'
        ]);

        SwimmingCategory::updateOrCreate([
            'title' => 'Tortuga'
        ], [
            'title' => 'Tortuga',
            'image' => 'uploads/swimming-categories/tortuga.png'
        ]);

        SwimmingCategory::updateOrCreate([
            'title' => 'Mantarraya'
        ], [
            'title' => 'Mantarraya',
            'image' => 'uploads/swimming-categories/mantarraya.png'
        ]);

        SwimmingCategory::updateOrCreate([
            'title' => 'Pez Vela'
        ], [
            'title' => 'Pez Vela',
            'image' => 'uploads/swimming-categories/pez_vela.png'
        ]);

        SwimmingCategory::updateOrCreate([
            'title' => 'Tiburón'
        ], [
            'title' => 'Tiburón',
            'image' => 'uploads/swimming-categories/tiburon.png'
        ]);
    }
}
