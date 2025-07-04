<?php

namespace Database\Seeders;

use App\Models\ContentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContentType::updateOrCreate([
            'title' => 'Avisos'
        ], [
            'title' => 'Avisos',
            'slug' => 'avisos'
        ]);

        ContentType::updateOrCreate([
            'title' => 'Eventos'
        ], [
            'title' => 'Eventos',
            'slug' => 'eventos'
        ]);

        ContentType::updateOrCreate([
            'title' => 'Tips'
        ], [
            'title' => 'Tips',
            'slug' => 'tips'
        ]);
    }
}
