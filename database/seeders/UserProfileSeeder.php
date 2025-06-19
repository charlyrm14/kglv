<?php

namespace Database\Seeders;

use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserProfile::updateOrCreate([
            'user_id' => 1,
            'type' => 'biography',
        ], [
            'user_id' => 1,
            'type' => 'biography',
            'content' => 'Soy el desarrollador de la app King Dreams. Me apasiona la tecnología, la programación y crear soluciones que hagan la vida más fácil. Siempre estoy buscando aprender y mejorar en lo que más me gusta hacer.',
            'visible_to' => 'public'
        ]);

        UserProfile::updateOrCreate([
            'user_id' => 2,
            'type' => 'biography',
        ], [
            'user_id' => 2,
            'type' => 'biography',
            'content' => 'Virginia es la administradora de la escuela de natación. Amante de las aventuras extremas, ha saltado en paracaídas y recorrido Europa. Disfruta enseñar a nadar a los niños y compartir su pasión por el agua con alegría y dedicación.',
            'visible_to' => 'public'
        ]);

        UserProfile::updateOrCreate([
            'user_id' => 3,
            'type' => 'biography',
        ], [
            'user_id' => 3,
            'type' => 'biography',
            'content' => 'Director de la escuela de natación. Aunque siempre parece serio, en realidad es un gran profesor con pasión por enseñar. Amante del ciclismo, cambia el mal humor por una sonrisa cuando se sube a su bicicleta.',
            'visible_to' => 'public'
        ]);

        UserProfile::updateOrCreate([
            'user_id' => 4,
            'type' => 'biography',
        ], [
            'user_id' => 4,
            'type' => 'biography',
            'content' => 'Estudiante de nivel semi avanzado, curiosa y valiente. Le encantan los insectos y siempre sorprende con lo rápido que aprende. Sus maestros la felicitan seguido por su inteligencia y dedicación en cada clase.',
            'visible_to' => 'public'
        ]);
    }
}
