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
            'skill_1' => 'Flota como una foca feliz',
            'skill_2' => 'Manos al agua sin miedo',
            'skill_3' => 'Respira y relájate',
            'description' => '¡Felicidades por comenzar tu aventura acuática! Como una foca, ya estás ganando confianza en el agua. Sigue practicando y pronto estarás nadando como un experto. ¡Vamos paso a paso!'
        ]);

        SwimmingLevel::updateOrCreate([
            'name' => 'Tortuga'
        ], [            
            'name' => 'Tortuga',
            'image' => 'uploads/swimming-categories/tortuga.png',
            'skill_1' => 'Patadas firmes y seguras',
            'skill_2' => 'Brazos lentos pero fuertes',
            'skill_3' => 'Avanza sin prisa, sin pausa',
            'description' => '¡Ya estás avanzando firme como una tortuga en su travesía! Tu técnica mejora y se nota tu esfuerzo. Sigue con esa constancia, que cada brazada te acerca a tu próxima meta.'
        ]);

        SwimmingLevel::updateOrCreate([
            'name' => 'Mantarraya'
        ], [
            'name' => 'Mantarraya',
            'image' => 'uploads/swimming-categories/mantarraya.png',
            'skill_1' => 'Desliza como una sombra',
            'skill_2' => 'Controla cada brazada',
            'skill_3' => 'Coordinación perfecta',
            'description' => '¡Deslizas en el agua como una mantarraya! Has mejorado mucho tu fluidez y control. Mantén tu concentración y disciplina, vas por excelente camino. ¡Sigue brillando!'
        ]);

        SwimmingLevel::updateOrCreate([
            'name' => 'Pez Vela'
        ], [
            'name' => 'Pez Vela',
            'image' => 'uploads/swimming-categories/pez_vela.png',
            'skill_1' => 'Velocidad bajo control',
            'skill_2' => 'Potencia en cada vuelta',
            'skill_3' => 'Resistencia como un pro',
            'description' => '¡Vuelas sobre el agua como un pez vela! Tu velocidad y técnica están en otro nivel. Estás muy cerca de dominarlo todo. Mantén tu energía al máximo, ¡ya casi llegas!'
        ]);

        SwimmingLevel::updateOrCreate([
            'name' => 'Tiburón'
        ], [
            'name' => 'Tiburón',
            'image' => 'uploads/swimming-categories/shark.png',
            'skill_1' => 'Nada como un depredador',
            'skill_2' => 'Dominio total del agua',
            'skill_3' => 'Estrategia y potencia',
            'description' => '¡Eres un tiburón en la piscina! Has llegado al nivel más alto y tu esfuerzo es evidente. Sigue entrenando con pasión, ahora eres un ejemplo para otros nadadores. ¡Orgullo total!'
        ]);
    }
}
