<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tramite;
use App\Models\User;
use Illuminate\Support\Str;

class TramiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los usuarios existentes
        $users = User::all();

        // Verificar si hay usuarios
        if ($users->isEmpty()) {
            $this->command->info('No hay usuarios disponibles. Por favor, crea usuarios antes de ejecutar este seeder.');
            return;
        }

        // Generar 50 trámites aleatorios
        Tramite::factory(8)->create([
            'user_id' => $users->random()->id, // Asignar un usuario aleatorio a cada trámite
        ]);
    }
}
