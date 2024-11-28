<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Tramite;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los usuarios y trámites existentes
        $users = User::all();
        $tramites = Tramite::all();

        if ($tramites->isEmpty()) {
            $this->command->warn('No se encontraron trámites. Asegúrate de tener trámites en la base de datos.');
            return;
        }

        // Crear 30 documentos con tramite_id = 1
        for ($i = 0; $i < 8; $i++) {
            DB::table('documents')->insert([
                'user_id' => $users->random()->id, // Seleccionar un usuario aleatorio
                'tramite_id' => 1, // Asignar tramite_id fijo
                'type' => $this->getRandomType(),
                'file_path' => 'documents/' . Str::random(10) . '.pdf', // Ruta de archivo ficticia
                'status' => $this->getRandomStatus(),
                'observation' => $this->getRandomObservation(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Crear el resto de los documentos asignando tramite_id al azar
        $remainingDocuments = 5; // Total 50 documentos
        for ($i = 0; $i < $remainingDocuments; $i++) {
            DB::table('documents')->insert([
                'user_id' => $users->random()->id, // Seleccionar un usuario aleatorio
                'tramite_id' => $tramites->random()->id, // Seleccionar un trámite aleatorio
                'type' => $this->getRandomType(),
                'file_path' => 'documents/' . Str::random(10) . '.pdf', // Ruta de archivo ficticia
                'status' => $this->getRandomStatus(),
                'observation' => $this->getRandomObservation(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Obtener un tipo de documento aleatorio.
     */
    private function getRandomType(): string
    {
        $types = ['Factura', 'Contrato', 'Certificado', 'Recibo', 'Solicitud'];
        return $types[array_rand($types)];
    }

    /**
     * Obtener un estado aleatorio.
     */
    private function getRandomStatus(): string
    {
        $statuses = ['Pendiente', 'Aprobado', 'Rechazado'];
        return $statuses[array_rand($statuses)];
    }

    /**
     * Generar una observación aleatoria o null.
     */
    private function getRandomObservation(): ?string
    {
        $observations = [
            'Revisar el documento',
            'Faltan datos en la página 2',
            'Cumple con los requisitos',
            null,
        ];
        return $observations[array_rand($observations)];
    }
}
