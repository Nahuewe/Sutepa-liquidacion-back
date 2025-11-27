<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EstadosSeeder::class,
            RolesSeeder::class,
            SeccionalSeeder::class,
            ConceptosSeeder::class,
            EmpleadosSeeder::class,
            LiquidacionesSeeder::class,
            UsuarioSeeder::class,
        ]);
    }
}
