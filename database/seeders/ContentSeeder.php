<?php

namespace Database\Seeders;

use App\Models\Content;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Content::updateOrCreate([
            'slug' => 'perfecciona-tu-tecnica-desde-lo-basico'
        ], [
            'title' => 'Perfecciona tu técnica desde lo básico',
            'content' => 'Antes de aumentar la velocidad o la resistencia, asegúrate de tener una buena postura, brazada eficiente y coordinación básica en el agua.',
            'slug' => 'perfecciona-tu-tecnica-desde-lo-basico',
            'cover_image' => 'uploads/swimming-categories/tip-1.png',
            'active' => 1,
            'content_type_id' => 3
        ]);

        Content::updateOrCreate([
            'slug' => 'trabaja-tu-respiracion'
        ], [
            'title' => 'Trabaja tu respiración',
            'content' => 'Aprende a respirar de forma lateral, sincronizada con tus brazadas, para conservar energía y mantener un ritmo constante.',
            'slug' => 'trabaja-tu-respiracion',
            'cover_image' => 'uploads/swimming-categories/tip-2.png',
            'active' => 1,
            'content_type_id' => 3
        ]);

        Content::updateOrCreate([
            'slug' => 'refuerza-tu-fuerza-fuera-del-agua'
        ], [
            'title' => 'Refuerza tu fuerza fuera del agua',
            'content' => 'Ejercicios fuera del agua, como abdominales, flexiones y movilidad, fortalecen los músculos clave y mejoran tu rendimiento en el agua.',
            'slug' => 'refuerza-tu-fuerza-fuera-del-agua',
            'cover_image' => 'uploads/swimming-categories/tip-3.png',
            'active' => 1,
            'content_type_id' => 3
        ]);

        Content::updateOrCreate([
            'slug' => 'no-descuides-la-patada'
        ], [
            'title' => 'No descuides la patada',
            'content' => 'Una patada ligera, constante y desde la cadera ayuda a estabilizar tu cuerpo y a reducir la resistencia en el agua.',
            'slug' => 'no-descuides-la-patada',
            'cover_image' => 'uploads/swimming-categories/tip-4.png',
            'active' => 1,
            'content_type_id' => 3
        ]);

        Content::updateOrCreate([
            'slug' => 'entrena-con-objetivos'
        ], [
            'title' => 'Entrena con objetivos claros',
            'content' => 'Define metas específicas para cada entrenamiento: técnica, resistencia, velocidad o recuperación. Esto hará tu progreso más medible.',
            'slug' => 'entrena-con-objetivos',
            'cover_image' => 'uploads/swimming-categories/tip-5.png',
            'active' => 1,
            'content_type_id' => 3
        ]);

        Content::updateOrCreate([
            'slug' => 'grabate-nadando-y-analiza-tu-tecnica'
        ], [
            'title' => 'Grábate nadando y analiza tu técnica',
            'content' => 'Usa videos para detectar errores y mejorar tu técnica. Compararte con nadadores expertos también puede ser muy útil.',
            'slug' => 'grabate-nadando-y-analiza-tu-tecnica',
            'cover_image' => 'uploads/swimming-categories/tip-6.png',
            'active' => 1,
            'content_type_id' => 3
        ]);
    }
}
