<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    /**
     * Seed the application's suppliers.
     */
    public function run(): void
    {
        $proveedores = [
            'Distribuidora ABC',
            'Comercial Lima',
            'Importaciones Perú',
        ];

        foreach ($proveedores as $nombre) {
            Proveedor::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
