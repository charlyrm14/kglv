<?php

namespace Database\Seeders;

use App\Models\ContentCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContentCategory::updateOrCreate([
            'title' => 'Avisos'
        ], [
            'title' => 'Avisos',
            'slug' => 'avisos'
        ]);

        ContentCategory::updateOrCreate([
            'title' => 'Eventos'
        ], [
            'title' => 'Eventos',
            'slug' => 'eventos'
        ]);
    }
}
