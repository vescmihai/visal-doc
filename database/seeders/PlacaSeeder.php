<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Placa;
use App\Models\Tramite;
use Illuminate\Support\Str;

class PlacaSeeder extends Seeder
{
    public function run()
    {
        // Asegúrate de que exista al menos un trámite con ID = 1
        if (!Tramite::find(1)) {
            $this->command->warn('No existe un trámite con ID 1. Crea trámites antes de ejecutar este seeder.');
            return;
        }

        // Generar 30 placas asociadas al trámite con tramite_id = 1
        for ($i = 0; $i < 30; $i++) {
            Placa::create([
                'tramite_id' => 1,
                'placa' => Str::upper(Str::random(8)), // Generar una placa alfanumérica aleatoria
                'motor' => 'M' . rand(1000, 9999), // Número de motor aleatorio
                'chasis' => 'C' . rand(10000, 99999), // Número de chasis aleatorio
                'poliza' => 'P' . rand(10000, 99999), // Número de póliza aleatorio
                'pago' => rand(0, 1) ? 'Pendiente' : 'Pagado', // Estado de pago aleatorio
            ]);
        }

        // Obtener todos los IDs de trámites excepto el ID 1
        $otherTramiteIds = Tramite::where('id', '!=', 1)->pluck('id');

        // Generar 20 placas asociadas a trámites aleatorios
        for ($i = 0; $i < 20; $i++) {
            Placa::create([
                'tramite_id' => $otherTramiteIds->random(), // Seleccionar un trámite aleatorio
                'placa' => Str::upper(Str::random(8)),
                'motor' => 'M' . rand(1000, 9999),
                'chasis' => 'C' . rand(10000, 99999),
                'poliza' => 'P' . rand(10000, 99999),
                'pago' => rand(0, 1) ? 'Pendiente' : 'Pagado',
            ]);
        }
    }
}
