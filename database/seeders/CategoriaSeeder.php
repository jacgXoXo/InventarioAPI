<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Seed the application's categories.
     */
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Alimentos', 'descripcion' => 'Productos alimenticios.'],
            ['nombre' => 'Electrónica', 'descripcion' => 'Dispositivos y accesorios electrónicos.'],
            ['nombre' => 'Limpieza', 'descripcion' => 'Productos para limpieza y aseo.'],
            ['nombre' => 'Mascotas', 'descripcion' => 'Productos para el cuidado de mascotas.'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['nombre' => $categoria['nombre']],
                ['descripcion' => $categoria['descripcion']]
            );
        }
    }
}
