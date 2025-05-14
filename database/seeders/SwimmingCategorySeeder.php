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
            'image' => 'uploads/swimming-categories/foca.png',
            'message' => '¡Felicidades por comenzar tu aventura acuática! Como una foca, ya estás ganando confianza en el agua. Sigue practicando y pronto estarás nadando como un experto. ¡Vamos paso a paso!'
        ]);

        SwimmingCategory::updateOrCreate([
            'title' => 'Tortuga'
        ], [
            'title' => 'Tortuga',
            'image' => 'uploads/swimming-categories/tortuga.png',
            'message' => '¡Ya estás avanzando firme como una tortuga en su travesía! Tu técnica mejora y se nota tu esfuerzo. Sigue con esa constancia, que cada brazada te acerca a tu próxima meta.'
        ]);

        SwimmingCategory::updateOrCreate([
            'title' => 'Mantarraya'
        ], [
            'title' => 'Mantarraya',
            'image' => 'uploads/swimming-categories/mantarraya.png',
            'message' => '¡Deslizas en el agua como una mantarraya! Has mejorado mucho tu fluidez y control. Mantén tu concentración y disciplina, vas por excelente camino. ¡Sigue brillando!',
        ]);

        SwimmingCategory::updateOrCreate([
            'title' => 'Pez Vela'
        ], [
            'title' => 'Pez Vela',
            'image' => 'uploads/swimming-categories/pez_vela.png',
            'message' => '¡Vuelas sobre el agua como un pez vela! Tu velocidad y técnica están en otro nivel. Estás muy cerca de dominarlo todo. Mantén tu energía al máximo, ¡ya casi llegas!'
        ]);

        SwimmingCategory::updateOrCreate([
            'title' => 'Tiburón'
        ], [
            'title' => 'Tiburón',
            'image' => 'uploads/swimming-categories/tiburon.png',
            'message' => '¡Eres un tiburón en la piscina! Has llegado al nivel más alto y tu esfuerzo es evidente. Sigue entrenando con pasión, ahora eres un ejemplo para otros nadadores. ¡Orgullo total!'
        ]);
    }
}
