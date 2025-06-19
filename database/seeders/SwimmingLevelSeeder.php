<?php

namespace Database\Seeders;

use App\Models\SwimmingLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SwimmingLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SwimmingLevel::updateOrCreate([
            'name' => 'Foca'
        ], [
            'name' => 'Foca',
            'image' => 'uploads/swimming-categories/foca.png',
            'description' => '¡Felicidades por comenzar tu aventura acuática! Como una foca, ya estás ganando confianza en el agua. Sigue practicando y pronto estarás nadando como un experto. ¡Vamos paso a paso!'
        ]);

        SwimmingLevel::updateOrCreate([
            'name' => 'Tortuga'
        ], [            
            'name' => 'Tortuga',
            'image' => 'uploads/swimming-categories/tortuga.png',
            'description' => '¡Ya estás avanzando firme como una tortuga en su travesía! Tu técnica mejora y se nota tu esfuerzo. Sigue con esa constancia, que cada brazada te acerca a tu próxima meta.'
        ]);

        SwimmingLevel::updateOrCreate([
            'name' => 'Mantarraya'
        ], [
            'name' => 'Mantarraya',
            'image' => 'uploads/swimming-categories/mantarraya.png',
            'description' => '¡Deslizas en el agua como una mantarraya! Has mejorado mucho tu fluidez y control. Mantén tu concentración y disciplina, vas por excelente camino. ¡Sigue brillando!'
        ]);

        SwimmingLevel::updateOrCreate([
            'name' => 'Pez Vela'
        ], [
            'name' => 'Pez Vela',
            'image' => 'uploads/swimming-categories/pez_vela.png',
            'description' => '¡Vuelas sobre el agua como un pez vela! Tu velocidad y técnica están en otro nivel. Estás muy cerca de dominarlo todo. Mantén tu energía al máximo, ¡ya casi llegas!'
        ]);

        SwimmingLevel::updateOrCreate([
            'name' => 'Tiburón'
        ], [
            'name' => 'Tiburón',
            'image' => 'uploads/swimming-categories/shark.png',
            'description' => '¡Eres un tiburón en la piscina! Has llegado al nivel más alto y tu esfuerzo es evidente. Sigue entrenando con pasión, ahora eres un ejemplo para otros nadadores. ¡Orgullo total!'
        ]);
    }
}
